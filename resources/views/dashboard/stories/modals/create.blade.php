<div class="modal fade" id="createStoryModal" tabindex="-1" role="dialog" aria-labelledby="createStoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStoryModalLabel">إضافة قصة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">العنوان <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ old('title') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="story_owner_id">صاحب القصة</label>
                                <select class="form-control select2" id="story_owner_id" name="story_owner_id">
                                    <option value="">اختر صاحب القصة</option>
                                    @foreach(\App\Models\Person::orderBy('first_name')->get() as $person)
                                        <option value="{{ $person->id }}" {{ old('story_owner_id') == $person->id ? 'selected' : '' }}>
                                            {{ $person->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="narrators">الرواة</label>
                                <select class="form-control select2" id="narrators" name="narrators[]" multiple>
                                    @foreach(\App\Models\Person::orderBy('first_name')->get() as $person)
                                        <option value="{{ $person->id }}" {{ in_array($person->id, old('narrators', [])) ? 'selected' : '' }}>
                                            {{ $person->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">يمكن اختيار أكثر من راوٍ</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="content">محتوى القصة (النص)</label>
                        <textarea class="form-control" id="content" name="content" rows="6"
                            placeholder="اكتب محتوى القصة هنا...">{{ old('content') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="audio_path">ملف صوتي</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="audio_path" name="audio_path"
                                        accept="audio/*">
                                    <label class="custom-file-label" for="audio_path">اختر ملف صوتي...</label>
                                </div>
                                <small class="form-text text-muted">امتدادات مسموحة: mp3, wav, ogg, m4a (حد أقصى 1GB)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="videoType">نوع الفيديو</label>
                                <select class="form-control" id="videoType" name="video_type">
                                    <option value="none">لا يوجد فيديو</option>
                                    <option value="url">رابط يوتيوب أو منصة خارجية</option>
                                    <option value="file">رفع ملف فيديو</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" id="videoUrlGroup" style="display: none;">
                            <div class="form-group">
                                <label for="video_url">رابط الفيديو</label>
                                <input type="url" class="form-control" id="video_url" name="video_url"
                                    value="{{ old('video_url') }}"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <small class="form-text text-muted">ضع رابط يوتيوب أو أي منصة فيديو</small>
                            </div>
                        </div>
                        <div class="col-md-6" id="videoFileGroup" style="display: none;">
                            <div class="form-group">
                                <label for="video_path">ملف فيديو</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="video_path" name="video_path"
                                        accept="video/*">
                                    <label class="custom-file-label" for="video_path">اختر ملف فيديو...</label>
                                </div>
                                <small class="form-text text-muted">امتدادات مسموحة: mp4, avi, mov, wmv, flv (حد أقصى 100MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

