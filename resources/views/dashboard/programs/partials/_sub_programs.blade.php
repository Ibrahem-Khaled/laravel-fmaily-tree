{{-- Tab 6: البرامج الفرعية --}}
<div class="tab-pane fade" id="sub-programs" role="tabpanel">
    <div class="card section-card">
        <div class="section-header">
            <h5><i class="fas fa-sitemap mr-2"></i>البرامج الفرعية</h5>
            <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#addSubProgramForm">
                <i class="fas fa-plus mr-1"></i>إضافة برنامج فرعي
            </button>
        </div>
        <div class="collapse" id="addSubProgramForm">
            <div class="form-section">
                <h6 class="font-weight-bold mb-3">ربط برنامج موجود كبرنامج فرعي</h6>
                <form action="{{ route('dashboard.programs.sub-programs.attach', $program) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">اختر برنامج <span class="text-danger">*</span></label>
                        <select name="sub_program_id" class="form-control @error('sub_program_id') is-invalid @enderror" required>
                            <option value="">-- اختر برنامج --</option>
                            @php
                                $availablePrograms = \App\Models\Image::where('is_program', true)
                                    ->where('id', '!=', $program->id)
                                    ->whereNull('program_id')
                                    ->orderBy('program_is_active', 'desc')
                                    ->orderBy('program_order')
                                    ->get();
                            @endphp
                            @foreach($availablePrograms as $availableProgram)
                                <option value="{{ $availableProgram->id }}" {{ old('sub_program_id') == $availableProgram->id ? 'selected' : '' }}>
                                    {{ $availableProgram->program_title ?? $availableProgram->name ?? 'برنامج #' . $availableProgram->id }}
                                    @if(!$availableProgram->program_is_active)
                                        (غير مفعل)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('sub_program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($availablePrograms->isEmpty())
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                لا توجد برامج متاحة للربط. تأكد من وجود برامج غير مرتبطة ببرامج أخرى.
                            </small>
                        @else
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                سيتم ربط البرنامج المحدد كبرنامج فرعي لهذا البرنامج. البرامج المفعلة تظهر أولاً.
                            </small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary" {{ $availablePrograms->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-link mr-1"></i>ربط البرنامج
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($subPrograms->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-sitemap"></i>
                    <h5 class="mt-3">لا توجد برامج فرعية</h5>
                    <p class="text-muted">ابدأ بربط برامج موجودة كبرامج فرعية لهذا البرنامج</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>الصورة</th>
                                <th>عنوان البرنامج</th>
                                <th>الوصف</th>
                                <th>الحالة</th>
                                <th style="width: 150px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="subProgramsTableBody">
                            @foreach($subPrograms as $index => $subProgram)
                                <tr data-sub-program-id="{{ $subProgram->id }}">
                                    <td class="align-middle">
                                        <span class="badge badge-secondary">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $subProgramImage = $subProgram->cover_image_path ?? $subProgram->path;
                                        @endphp
                                        @if($subProgramImage)
                                            <img src="{{ asset('storage/' . $subProgramImage) }}"
                                                 alt="{{ $subProgram->program_title ?? $subProgram->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px; border-radius: 4px;">
                                                <i class="fas fa-folder-open text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <strong>{{ $subProgram->program_title ?? ($subProgram->name ?? 'برنامج #' . $subProgram->id) }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        @if($subProgram->program_description)
                                            <small class="text-muted">
                                                {{ Str::limit(strip_tags($subProgram->program_description), 80) }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($subProgram->program_is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('programs.show', $subProgram) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-info"
                                               title="عرض البرنامج">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboard.programs.manage', $subProgram) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="إدارة البرنامج">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <form action="{{ route('dashboard.programs.sub-programs.detach', [$program, $subProgram]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من فك ربط هذا البرنامج الفرعي؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="فك الربط">
                                                    <i class="fas fa-unlink"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        يمكنك سحب وإفلات الصفوف لإعادة ترتيب البرامج الفرعية (قريباً)
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>
