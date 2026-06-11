@props(['img'])

<div class="col-sm-6 col-md-4 col-lg-3 col-xl-3 mb-4" id="media-card-{{ $img->id }}">
    <div class="media-card">
        <div class="media-card-preview">
            <!-- Checkbox overlay -->
            <div class="media-checkbox-wrapper">
                <input type="checkbox" name="ids[]" value="{{ $img->id }}" class="media-checkbox image-checkbox" id="cb-{{ $img->id }}">
            </div>

            <!-- Type badge -->
            @if($img->media_type === 'youtube')
                <div class="media-badge youtube">
                    <i class="fab fa-youtube text-danger"></i> يوتيوب
                </div>
            @elseif($img->media_type === 'pdf')
                <div class="media-badge pdf">
                    <i class="fas fa-file-pdf text-warning"></i> PDF
                </div>
            @else
                <div class="media-badge image">
                    <i class="fas fa-image text-primary"></i> صورة
                </div>
            @endif

            <!-- Preview Content (Lazy Loaded) -->
            <div class="preview-clickable" onclick="triggerMediaView({{ $img->id }}, '{{ $img->media_type }}', '{{ $img->youtube_url }}', '{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}', '{{ addslashes($img->name ?? 'ملف') }}')">
                @if($img->media_type === 'youtube' && $img->youtube_url)
                    <img src="{{ $img->getYouTubeThumbnail() }}" alt="{{ $img->name }}" loading="lazy" class="lazyload-media">
                    <div class="play-overlay">
                        <i class="fab fa-youtube"></i>
                    </div>
                @elseif($img->media_type === 'pdf' && $img->path)
                    <div class="pdf-preview-container d-flex flex-column align-items-center justify-content-center h-100 text-white" style="position:absolute; width:100%; height:100%; background:#2b3035;">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                        <span class="badge badge-secondary">{{ $img->getFormattedFileSize() }}</span>
                    </div>
                @else
                    <img src="{{ $img->path ? asset('storage/' . $img->path) : asset('img/no-image.png') }}" alt="{{ $img->name }}" loading="lazy" class="lazyload-media">
                @endif
            </div>
        </div>
        
        <div class="media-card-body">
            <div>
                <h5 class="media-card-title" title="{{ $img->name ?? '-' }}">{{ $img->name ?? '-' }}</h5>
                
                <!-- Mentioned Persons -->
                <div class="media-card-mentions">
                    <div class="mentioned-persons-container" data-image-id="{{ $img->id }}">
                        <div class="sortable-list d-flex flex-wrap" id="sortable-{{ $img->id }}">
                            @foreach($img->mentionedPersons as $person)
                                <div class="badge badge-info mentioned-person-item m-1 py-1 px-2 d-flex align-items-center" data-person-id="{{ $person->id }}">
                                    <i class="fas fa-grip-vertical drag-handle mr-1 text-light" style="display: none; cursor: grab;"></i>
                                    <span>{{ $person->full_name }}</span>
                                    <button type="button" class="btn-remove-mention ml-1" onclick="removePersonFromImage({{ $img->id }}, {{ $person->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions and Reorder trigger -->
            <div class="media-card-actions mt-3">
                <div class="btn-group w-100" role="group">
                    @if($img->mentionedPersons->count() > 1)
                        <button type="button" class="btn btn-sm btn-outline-info btn-reorder-toggle" onclick="toggleReorderMode({{ $img->id }})">
                            <i class="fas fa-sort"></i> ترتيب
                        </button>
                    @endif
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editImage({{ $img->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadImage({{ $img->id }}, '{{ $img->name ?? 'صورة' }}')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSingleImage({{ $img->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
