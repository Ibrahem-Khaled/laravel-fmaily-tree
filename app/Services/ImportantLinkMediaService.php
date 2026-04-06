<?php

namespace App\Services;

use App\Models\ImportantLink;
use App\Models\ImportantLinkMedia;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImportantLinkMediaService
{
    /**
     * إرفاق ملفات وسائط جديدة من الطلب (بعد حفظ الرابط).
     */
    public function attachFromRequest(Request $request, ImportantLink $link): void
    {
        $files = $request->file('media_files', []);
        if (! is_array($files)) {
            return;
        }

        $kinds = $request->input('media_kinds', []);
        $titles = $request->input('media_titles', []);
        $descriptions = $request->input('media_descriptions', []);

        $maxOrder = (int) $link->media()->max('sort_order');

        $toProcess = [];
        foreach ($files as $i => $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $kind = $kinds[$i] ?? 'image';
            if (! in_array($kind, ['image', 'video'], true)) {
                $kind = 'image';
            }

            $rules = $kind === 'image'
                ? ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
                : ['required', 'mimes:mp4,webm,mov', 'max:51200'];

            Validator::make(['media' => $file], ['media' => $rules])->validate();

            $toProcess[] = [
                'file' => $file,
                'kind' => $kind,
                'title' => isset($titles[$i]) && $titles[$i] !== '' ? mb_substr((string) $titles[$i], 0, 255) : null,
                'description' => isset($descriptions[$i]) && $descriptions[$i] !== '' ? mb_substr((string) $descriptions[$i], 0, 2000) : null,
            ];
        }

        foreach ($toProcess as $item) {
            $maxOrder++;
            $path = $item['file']->store('important-links', 'public');

            ImportantLinkMedia::create([
                'important_link_id' => $link->id,
                'kind' => $item['kind'],
                'path' => $path,
                'title' => $item['title'],
                'description' => $item['description'],
                'sort_order' => $maxOrder,
            ]);
        }
    }

    /**
     * حذف وسائط محددة بالمعرّفات (مع الملفات من التخزين).
     *
     * @param  array<int|string>  $ids
     */
    public function deleteMediaByIds(ImportantLink $link, array $ids): void
    {
        $ids = array_filter(array_map('intval', $ids));
        if ($ids === []) {
            return;
        }

        $items = $link->media()->whereIn('id', $ids)->get();
        foreach ($items as $media) {
            Storage::disk('public')->delete($media->path);
            $media->delete();
        }
    }
}
