<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\NotificationDelivery;
use App\Models\UltramsgSendLog;
use App\Services\UltramsgService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendWhatsAppInvitationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public int $notificationId,
        public int $personId,
        public string $toNumber,
        public string $messageBody,
        public ?string $mediaType = null,
        public ?string $mediaPath = null,
    ) {}

    public function handle(UltramsgService $ultramsg): void
    {
        $logContext = [
            'notification_id' => $this->notificationId,
            'person_id' => $this->personId,
            'to' => $this->toNumber,
            'media_type' => $this->mediaType,
            'media_path' => $this->mediaPath,
        ];

        Log::channel('ultramsg')->info('Job started', $logContext);

        $notification = Notification::find($this->notificationId);
        if (!$notification) {
            Log::channel('ultramsg')->error('Notification not found, aborting', $logContext);
            return;
        }

        $log = UltramsgSendLog::create([
            'notification_id' => $this->notificationId,
            'person_id' => $this->personId,
            'to_number' => $this->toNumber,
            'message_sent' => $this->messageBody,
            'media_type' => $this->mediaType,
            'media_path' => $this->mediaPath,
            'status' => 'pending',
        ]);

        try {
            $result = $this->send($ultramsg, $logContext);
        } catch (\Throwable $e) {
            Log::channel('ultramsg')->error('Job exception', array_merge($logContext, [
                'exception' => $e->getMessage(),
                'trace' => mb_substr($e->getTraceAsString(), 0, 500),
            ]));
            $result = ['success' => false, 'error' => 'Exception: ' . $e->getMessage()];
        }

        if ($result['success']) {
            $log->update([
                'status' => 'sent',
                'ultramsg_id' => $result['id'] ?? null,
                'sent_at' => now(),
            ]);
            Log::channel('ultramsg')->info('Message sent OK', $logContext);
        } else {
            $log->update([
                'status' => 'failed',
                'error_message' => $result['error'] ?? 'Unknown error',
            ]);
            Log::channel('ultramsg')->warning('Message FAILED', array_merge($logContext, [
                'error' => $result['error'] ?? 'Unknown',
            ]));
        }

        NotificationDelivery::create([
            'notification_id' => $this->notificationId,
            'deliverable_type' => UltramsgSendLog::class,
            'deliverable_id' => $log->id,
        ]);

        $this->updateNotificationStatus($notification);
    }

    /**
     * Determine how to send (text, image w/ base64, video, voice) and execute.
     */
    private function send(UltramsgService $ultramsg, array $logContext): array
    {
        $mediaSource = null;

        if ($this->mediaPath && $this->mediaType) {
            $fullPath = storage_path('app/public/' . $this->mediaPath);
            if (!file_exists($fullPath)) {
                Log::channel('ultramsg')->error('Media file NOT found on disk', array_merge($logContext, [
                    'path' => $this->mediaPath,
                    'full_path' => $fullPath,
                ]));
                return ['success' => false, 'error' => 'Media file not found: ' . $this->mediaPath];
            }
            $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

            if ($this->mediaType === 'image') {
                // Use base64 for images (works without public URL)
                $contents = file_get_contents($fullPath);
                $mediaSource = 'data:' . $mime . ';base64,' . base64_encode($contents);
                Log::channel('ultramsg')->info('Image encoded as base64', array_merge($logContext, [
                    'mime' => $mime,
                    'size_kb' => round(strlen($contents) / 1024),
                ]));
            } else {
                // Video/voice: need a public URL
                $appUrl = rtrim(config('app.url', 'http://localhost'), '/');
                $mediaSource = $appUrl . '/storage/' . $this->mediaPath;
                Log::channel('ultramsg')->info('Media URL generated', array_merge($logContext, [
                    'url' => $mediaSource,
                    'is_localhost' => str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1'),
                ]));

                if (str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
                    Log::channel('ultramsg')->warning('APP_URL is localhost — UltraMSG cannot access video/voice files. Set APP_URL to a public domain.', $logContext);
                }
            }
        }

        // Execute send
        if ($this->mediaType === 'image' && $mediaSource) {
            return $ultramsg->sendImage($this->toNumber, $mediaSource, $this->messageBody);
        }

        if ($this->mediaType === 'video' && $mediaSource) {
            return $ultramsg->sendVideo($this->toNumber, $mediaSource, $this->messageBody);
        }

        if ($this->mediaType === 'voice' && $mediaSource) {
            $result = $ultramsg->sendVoice($this->toNumber, $mediaSource);
            // Send text separately after voice
            if ($result['success'] && $this->messageBody) {
                $ultramsg->sendText($this->toNumber, $this->messageBody);
            }
            return $result;
        }

        // Text only
        return $ultramsg->sendText($this->toNumber, $this->messageBody);
    }

    private function updateNotificationStatus(Notification $notification): void
    {
        $expectedCount = $notification->recipients()->count();
        $logs = UltramsgSendLog::where('notification_id', $notification->id)->get();
        if ($logs->count() < $expectedCount) {
            return;
        }
        $sent = $logs->where('status', 'sent')->count();
        $failed = $logs->where('status', 'failed')->count();
        $total = $logs->count();

        if ($sent === $total) {
            $notification->update(['status' => 'sent', 'sent_at' => now()]);
        } elseif ($failed === $total) {
            $notification->update(['status' => 'failed']);
        } else {
            $notification->update(['status' => 'partial']);
        }
    }
}
