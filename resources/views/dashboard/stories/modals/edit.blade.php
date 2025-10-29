<div class="modal fade" id="editStoryModal{{ $story->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editStoryModalLabel{{ $story->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStoryModalLabel{{ $story->id }}">تعديل القصة: {{ $story->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('stories.update', $story->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title{{ $story->id }}">العنوان <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title{{ $story->id }}" name="title"
                                    value="{{ old('title', $story->title) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="story_owner_id{{ $story->id }}">صاحب القصة</label>
                                <select class="form-control select2" id="story_owner_id{{ $story->id }}" name="story_owner_id">
                                    <option value="">اختر صاحب القصة</option>
                                    @foreach(\App\Models\Person::orderBy('first_name')->get() as $person)
                                        <option value="{{ $person->id }}" {{ old('story_owner_id', $story->story_owner_id) == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="narrators{{ $story->id }}">الرواة</label>
                                <select class="form-control select2" id="narrators{{ $story->id }}" name="narrators[]" multiple>
                                    @foreach(\App\Models\Person::orderBy('first_name')->get() as $person)
                                        <option value="{{ $person->id }}" {{ in_array($person->id, old('narrators', $story->narrators->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $person->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">يمكن اختيار أكثر من راوٍ</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="content{{ $story->id }}">محتوى القصة (النص)</label>
                        <textarea class="form-control" id="content{{ $story->id }}" name="content" rows="6"
                            placeholder="اكتب محتوى القصة هنا...">{{ old('content', $story->content) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="audio_path{{ $story->id }}">ملف صوتي</label>
                                @if($story->audio_path)
                                    <div class="mb-2">
                                        <audio controls class="w-100">
                                            <source src="{{ $story->getAudioUrl() }}" type="audio/mpeg">
                                            المتصفح لا يدعم العنصر الصوتي.
                                        </audio>
                                    </div>
                                    <small class="text-muted">الملف الحالي</small>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="audio_path{{ $story->id }}" name="audio_path"
                                        accept="audio/*">
                                    <label class="custom-file-label" for="audio_path{{ $story->id }}">
                                        {{ $story->audio_path ? 'تغيير الملف الصوتي...' : 'اختر ملف صوتي...' }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">امتدادات مسموحة: mp3, wav, ogg, m4a (حد أقصى 10MB)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoType{{ $story->id }}">نوع الفيديو</label>
                                <select class="form-control" id="videoType{{ $story->id }}" name="video_type">
                                    <option value="none" {{ !$story->hasVideo() ? 'selected' : '' }}>لا يوجد فيديو</option>
                                    <option value="url" {{ $story->hasExternalVideo() ? 'selected' : '' }}>رابط يوتيوب أو منصة خارجية</option>
                                    <option value="file" {{ $story->hasUploadedVideo() ? 'selected' : '' }}>رفع ملف فيديو</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" id="videoUrlGroup{{ $story->id }}" style="display: {{ $story->hasExternalVideo() ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="video_url{{ $story->id }}">رابط الفيديو</label>
                                @if($story->hasExternalVideo())
                                    <div class="mb-2">
                                        <a href="{{ $story->video_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fab fa-youtube"></i> عرض الفيديو الحالي
                                        </a>
                                    </div>
                                @endif
                                <input type="url" class="form-control" id="video_url{{ $story->id }}" name="video_url"
                                    value="{{ old('video_url', $story->video_url) }}"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <small class="form-text text-muted">ضع رابط يوتيوب أو أي منصة فيديو</small>
                            </div>
                        </div>
                        <div class="col-md-6" id="videoFileGroup{{ $story->id }}" style="display: {{ $story->hasUploadedVideo() ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="video_path{{ $story->id }}">ملف فيديو</label>
                                @if($story->hasUploadedVideo())
                                    <div class="mb-2">
                                        <video controls class="w-100" style="max-height: 200px;">
                                            <source src="{{ $story->getUploadedVideoUrl() }}" type="video/mp4">
                                            المتصفح لا يدعم العنصر الفيديوي.
                                        </video>
                                        <small class="text-muted">الملف الحالي</small>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="video_path{{ $story->id }}" name="video_path"
                                        accept="video/*">
                                    <label class="custom-file-label" for="video_path{{ $story->id }}">
                                        {{ $story->video_path ? 'تغيير ملف الفيديو...' : 'اختر ملف فيديو...' }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">امتدادات مسموحة: mp4, avi, mov, wmv, flv (حد أقصى 100MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // للتبديل بين رابط يوتيوب ورفع ملف في مودال التعديل
    $('#videoType{{ $story->id }}').on('change', function() {
        const type = $(this).val();
        if (type === 'url') {
            $('#videoUrlGroup{{ $story->id }}').show();
            $('#videoFileGroup{{ $story->id }}').hide();
        } else if (type === 'file') {
            $('#videoUrlGroup{{ $story->id }}').hide();
            $('#videoFileGroup{{ $story->id }}').show();
        } else {
            $('#videoUrlGroup{{ $story->id }}').hide();
            $('#videoFileGroup{{ $story->id }}').hide();
        }
    });
</script>

