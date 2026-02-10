<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UltramsgService
{
    private string $baseUrl;
    private string $token;
    private string $instanceId;

    public function __construct()
    {
        $this->instanceId = config('services.ultramsg.instance_id', '');
        $this->token = config('services.ultramsg.token', '');
        $this->baseUrl = 'https://api.ultramsg.com/' . $this->instanceId;
    }

    /**
     * Normalize phone to international format (digits only).
     */
    public function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Send text message.
     */
    public function sendText(string $to, string $body): array
    {
        $to = $this->normalizePhone($to);
        Log::channel('ultramsg')->info('sendText', ['to' => $to, 'body_length' => mb_strlen($body)]);

        $response = $this->post('/messages/chat', [
            'to' => $to,
            'body' => $body,
        ]);

        return $this->parseResponse($response, 'sendText', $to);
    }

    /**
     * Send image with optional caption.
     * Supports: public HTTP URL or base64-encoded string.
     */
    public function sendImage(string $to, string $imageSource, ?string $caption = null): array
    {
        $to = $this->normalizePhone($to);
        Log::channel('ultramsg')->info('sendImage', [
            'to' => $to,
            'source_type' => str_starts_with($imageSource, 'data:') ? 'base64' : 'url',
            'caption_length' => $caption ? mb_strlen($caption) : 0,
        ]);

        $payload = ['to' => $to, 'image' => $imageSource];
        if ($caption !== null && $caption !== '') {
            $payload['caption'] = $caption;
        }

        $response = $this->post('/messages/image', $payload);
        return $this->parseResponse($response, 'sendImage', $to);
    }

    /**
     * Send video with optional caption.
     */
    public function sendVideo(string $to, string $videoUrl, ?string $caption = null): array
    {
        $to = $this->normalizePhone($to);
        Log::channel('ultramsg')->info('sendVideo', ['to' => $to, 'url' => $videoUrl]);

        $payload = ['to' => $to, 'video' => $videoUrl];
        if ($caption !== null && $caption !== '') {
            $payload['caption'] = $caption;
        }

        $response = $this->post('/messages/video', $payload, 60);
        return $this->parseResponse($response, 'sendVideo', $to);
    }

    /**
     * Send voice/audio message.
     */
    public function sendVoice(string $to, string $audioUrl): array
    {
        $to = $this->normalizePhone($to);
        Log::channel('ultramsg')->info('sendVoice', ['to' => $to, 'url' => $audioUrl]);

        $response = $this->post('/messages/voice', ['to' => $to, 'audio' => $audioUrl]);
        return $this->parseResponse($response, 'sendVoice', $to);
    }

    /**
     * Send a document.
     */
    public function sendDocument(string $to, string $documentSource, ?string $filename = null, ?string $caption = null): array
    {
        $to = $this->normalizePhone($to);
        Log::channel('ultramsg')->info('sendDocument', ['to' => $to, 'filename' => $filename]);

        $payload = ['to' => $to, 'document' => $documentSource];
        if ($filename) $payload['filename'] = $filename;
        if ($caption) $payload['caption'] = $caption;

        $response = $this->post('/messages/document', $payload);
        return $this->parseResponse($response, 'sendDocument', $to);
    }

    /**
     * Central HTTP post with token injection.
     */
    private function post(string $endpoint, array $payload, int $timeout = 30)
    {
        $payload['token'] = $this->token;

        try {
            return Http::timeout($timeout)->asForm()->post($this->baseUrl . $endpoint, $payload);
        } catch (\Throwable $e) {
            Log::channel('ultramsg')->error('HTTP Exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Parse UltraMSG response and log result.
     */
    private function parseResponse($response, string $context, string $to = ''): array
    {
        if ($response === null) {
            Log::channel('ultramsg')->error("{$context} FAILED: no response (connection error)", ['to' => $to]);
            return ['success' => false, 'error' => 'Connection error — no response from UltraMSG'];
        }

        $status = $response->status();
        $body = $response->body();
        $data = $response->json();

        if ($response->failed()) {
            Log::channel('ultramsg')->error("{$context} FAILED: HTTP {$status}", [
                'to' => $to,
                'response_body' => mb_substr($body, 0, 500),
            ]);
            return ['success' => false, 'error' => "HTTP {$status}: " . mb_substr($body, 0, 300)];
        }

        if (isset($data['error'])) {
            $err = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
            Log::channel('ultramsg')->warning("{$context} API error", [
                'to' => $to,
                'error' => $err,
                'response' => $data,
            ]);
            return ['success' => false, 'error' => $err];
        }

        $id = $data['id'] ?? $data['messageId'] ?? $data['sent'] ?? null;
        Log::channel('ultramsg')->info("{$context} SUCCESS", ['to' => $to, 'id' => $id]);

        return ['success' => true, 'id' => $id];
    }
}
