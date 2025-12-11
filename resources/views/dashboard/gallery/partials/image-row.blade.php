<tr>
    <td><input type="checkbox" name="ids[]" value="{{ $img->id }}" class="image-checkbox"></td>
    <td>
        @if($img->media_type === 'youtube' && $img->youtube_url)
            <div style="width:120px;height:90px;position:relative;background:#000;border-radius:4px;overflow:hidden;">
                <img src="{{ $img->getYouTubeThumbnail() }}"
                     style="width:100%;height:100%;object-fit:cover;"
                     class="img-thumbnail">
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:24px;">
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        @elseif($img->media_type === 'pdf' && $img->path)
            <div style="width:120px;height:90px;position:relative;background:#f8f9fa;border-radius:4px;overflow:hidden;border:1px solid #dee2e6;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <svg style="width:40px;height:40px;color:#dc3545;margin-bottom:5px;" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                </svg>
                <div style="font-size:10px;color:#6c757d;font-weight:bold;">PDF</div>
                @if($img->file_size)
                    <div style="font-size:8px;color:#6c757d;">{{ $img->getFormattedFileSize() }}</div>
                @endif
            </div>
        @else
            <img src="{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}"
                style="width:120px;height:90px;object-fit:cover;" class="img-thumbnail">
        @endif
    </td>
    <td>{{ $img->name ?? '-' }}</td>
    <td>
        @if($img->mentionedPersons->count() > 0)
            <div class="mentioned-persons-container" data-image-id="{{ $img->id }}">
                <div class="sortable-list" id="sortable-{{ $img->id }}">
                    @foreach($img->mentionedPersons as $person)
                        <div class="badge badge-info mentioned-person-item"
                             data-person-id="{{ $person->id }}">
                            <i class="fas fa-grip-vertical text-muted mr-1"></i>
                            {{ $person->full_name }}
                            <button type="button" class="btn btn-sm btn-outline-danger ml-1"
                                    onclick="removePersonFromImage({{ $img->id }}, {{ $person->id }})"
                                    style="padding: 2px 6px; font-size: 10px; border-radius: 50%;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                @if($img->mentionedPersons->count() > 1)
                    <button type="button" class="btn btn-sm btn-outline-primary mt-1"
                            onclick="toggleReorderMode({{ $img->id }})">
                        <i class="fas fa-sort"></i> إعادة ترتيب
                    </button>
                @endif
            </div>
        @else
            <span class="text-muted">لا يوجد</span>
        @endif
    </td>
    <td>
        <div class="btn-group" role="group">
            @if($img->media_type === 'youtube' && $img->youtube_url)
                <button type="button" class="btn btn-sm btn-danger" onclick="viewYouTube({{ $img->id }}, '{{ $img->youtube_url }}', '{{ $img->name ?? 'فيديو يوتيوب' }}')">
                    <i class="fab fa-youtube"></i> مشاهدة
                </button>
            @else
                <button type="button" class="btn btn-sm btn-info" onclick="viewImage({{ $img->id }}, '{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}', '{{ $img->name ?? 'صورة' }}')">
                    <i class="fas fa-search-plus"></i> تكبير
                </button>
            @endif
            <button type="button" class="btn btn-sm btn-success" onclick="downloadImage({{ $img->id }}, '{{ $img->name ?? 'صورة' }}')">
                <i class="fas fa-download"></i> تحميل
            </button>
            <button type="button" class="btn btn-sm btn-primary" onclick="editImage({{ $img->id }})">
                <i class="fas fa-edit"></i> تعديل
            </button>
        </div>
    </td>
</tr>

