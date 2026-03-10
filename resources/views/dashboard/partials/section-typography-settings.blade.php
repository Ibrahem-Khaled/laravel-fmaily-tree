{{--
    Reusable typography settings partial for home-sections create/edit forms.

    Variables:
        $ss         – section settings array (defaults to [])
        $idPrefix   – unique string prefix for HTML IDs  e.g. 'create' or 'edit'
        $compact    – bool: true = sidebar s-input style, false = Bootstrap card style

    Color strategy: the <input type="color"> is a visual-only picker (no name).
    The <input type="text" name="settings[...]"> is the actual submitted value.
    Empty text = "use default". JS keeps the two in sync.
--}}

@php
    $ss       = $ss       ?? [];
    $idPrefix = $idPrefix ?? 'typ';
    $compact  = $compact  ?? false;

    $inputCls = $compact ? 'form-control s-input' : 'form-control form-control-sm';
    $labelCls = $compact ? 's-label mb-1'         : 'font-weight-bold small mb-1';

    $sizeOpts   = ['' => 'افتراضي', '13px' => '13 – خفيف', '15px' => '15 – صغير', '17px' => '17 – عادي', '20px' => '20 – متوسط', '24px' => '24 – كبير', '30px' => '30 – ضخم', '36px' => '36 – أكبر', '48px' => '48 – عملاق'];
    $weightOpts = ['' => 'افتراضي', '400' => '400 – عادي', '600' => '600 – شبه عريض', '700' => '700 – عريض', '800' => '800 – أعرض', '900' => '900 – أقصى'];
    $alignOpts  = ['' => 'افتراضي', 'right' => 'يمين ←', 'center' => 'وسط ↔', 'left' => '→ يسار'];

    $groups = [
        'title' => [
            'label'      => 'العنوان الرئيسي',
            'icon'       => 'fas fa-heading',
            'color_key'  => 'title_color',
            'size_key'   => 'title_size',
            'weight_key' => 'title_weight',
            'align_key'  => 'title_align',
        ],
        'subtitle' => [
            'label'      => 'العنوان الفرعي',
            'icon'       => 'fas fa-font',
            'color_key'  => 'subtitle_color',
            'size_key'   => 'subtitle_size',
            'weight_key' => 'subtitle_weight',
            'align_key'  => 'subtitle_align',
        ],
        'description' => [
            'label'      => 'الوصف',
            'icon'       => 'fas fa-align-right',
            'color_key'  => 'description_color',
            'size_key'   => 'description_size',
            'weight_key' => null,
            'align_key'  => 'description_align',
        ],
    ];
@endphp

@foreach ($groups as $key => $g)
    @php
        $colorVal  = old("settings.{$g['color_key']}",  $ss[$g['color_key']]  ?? '');
        $sizeVal   = old("settings.{$g['size_key']}",   $ss[$g['size_key']]   ?? '');
        $weightVal = $g['weight_key'] ? old("settings.{$g['weight_key']}", $ss[$g['weight_key']] ?? '') : null;
        $alignVal  = old("settings.{$g['align_key']}",  $ss[$g['align_key']]  ?? '');

        // IDs for JS linkage  (color picker is visual-only, text input is the real field)
        $pickerId = "{$idPrefix}_{$g['color_key']}_pick";
        $textId   = "{$idPrefix}_{$g['color_key']}_txt";
    @endphp

    @if ($compact)
        {{-- ───────── COMPACT mode (edit sidebar) ───────── --}}
        <div class="setting-group" style="margin-bottom:4px;">
            <div class="setting-group-title" style="font-size:0.72rem;">
                <i class="{{ $g['icon'] }}"></i>{{ $g['label'] }}
            </div>

            {{-- Color row --}}
            <div class="form-group mb-2">
                <label class="{{ $labelCls }}">اللون</label>
                <div class="color-pair">
                    {{-- Visual picker only — NOT submitted --}}
                    <input type="color" id="{{ $pickerId }}"
                           value="{{ $colorVal ?: '#333333' }}"
                           title="انقر لاختيار لون"
                           oninput="document.getElementById('{{ $textId }}').value=this.value;">
                    {{-- Actual named input --}}
                    <input type="text" id="{{ $textId }}"
                           name="settings[{{ $g['color_key'] }}]"
                           class="form-control s-input"
                           value="{{ $colorVal }}"
                           placeholder="افتراضي (فارغ)"
                           style="flex:1;direction:ltr;text-align:left;"
                           oninput="syncPicker('{{ $pickerId }}', this.value)">
                    <button type="button" class="btn btn-sm btn-link p-0"
                            title="مسح اللون والرجوع للافتراضي"
                            onclick="document.getElementById('{{ $textId }}').value='';document.getElementById('{{ $pickerId }}').value='#333333';"
                            style="font-size:0.7rem;color:#aaa;">✕</button>
                </div>
            </div>

            {{-- Size / Weight / Align row --}}
            <div class="row no-gutters" style="gap:4px;">
                <div class="{{ $g['weight_key'] ? 'col' : 'col-6' }}">
                    <label class="{{ $labelCls }}">الحجم</label>
                    <select name="settings[{{ $g['size_key'] }}]" class="{{ $inputCls }} no-search" style="font-size:0.75rem;padding:3px 6px;">
                        @foreach ($sizeOpts as $val => $lbl)
                            <option value="{{ $val }}" {{ $sizeVal == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($g['weight_key'])
                    <div class="col">
                        <label class="{{ $labelCls }}">الوزن</label>
                        <select name="settings[{{ $g['weight_key'] }}]" class="{{ $inputCls }} no-search" style="font-size:0.75rem;padding:3px 6px;">
                            @foreach ($weightOpts as $val => $lbl)
                                <option value="{{ $val }}" {{ $weightVal == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="{{ $g['weight_key'] ? 'col' : 'col-6' }}">
                    <label class="{{ $labelCls }}">المحاذاة</label>
                    <select name="settings[{{ $g['align_key'] }}]" class="{{ $inputCls }} no-search" style="font-size:0.75rem;padding:3px 6px;">
                        @foreach ($alignOpts as $val => $lbl)
                            <option value="{{ $val }}" {{ $alignVal == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    @else
        {{-- ───────── FULL mode (create card) ───────── --}}
        <div class="border rounded p-3 mb-3" style="background:#f9fafb;">
            <h6 class="font-weight-bold text-primary mb-3">
                <i class="{{ $g['icon'] }} mr-1"></i>{{ $g['label'] }}
            </h6>

            <div class="row">
                {{-- Color --}}
                <div class="{{ $g['weight_key'] ? 'col-sm-3' : 'col-sm-4' }}">
                    <div class="form-group mb-2">
                        <label class="{{ $labelCls }}"><i class="fas fa-tint mr-1"></i>اللون</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text p-1" style="background:#fff;">
                                    <input type="color" id="{{ $pickerId }}"
                                           value="{{ $colorVal ?: '#333333' }}"
                                           style="width:24px;height:24px;padding:0;border:none;cursor:pointer;"
                                           oninput="document.getElementById('{{ $textId }}').value=this.value;">
                                </span>
                            </div>
                            <input type="text" id="{{ $textId }}"
                                   name="settings[{{ $g['color_key'] }}]"
                                   class="form-control form-control-sm"
                                   value="{{ $colorVal }}"
                                   placeholder="افتراضي"
                                   style="direction:ltr;text-align:left;"
                                   oninput="syncPicker('{{ $pickerId }}', this.value)">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        title="مسح"
                                        onclick="document.getElementById('{{ $textId }}').value='';document.getElementById('{{ $pickerId }}').value='#333333';">
                                    <i class="fas fa-times" style="font-size:0.65rem;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Size --}}
                <div class="{{ $g['weight_key'] ? 'col-sm-3' : 'col-sm-4' }}">
                    <div class="form-group mb-2">
                        <label class="{{ $labelCls }}"><i class="fas fa-text-height mr-1"></i>الحجم</label>
                        <select name="settings[{{ $g['size_key'] }}]" class="{{ $inputCls }}">
                            @foreach ($sizeOpts as $val => $lbl)
                                <option value="{{ $val }}" {{ $sizeVal == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Weight --}}
                @if ($g['weight_key'])
                    <div class="col-sm-3">
                        <div class="form-group mb-2">
                            <label class="{{ $labelCls }}"><i class="fas fa-bold mr-1"></i>الوزن</label>
                            <select name="settings[{{ $g['weight_key'] }}]" class="{{ $inputCls }}">
                                @foreach ($weightOpts as $val => $lbl)
                                    <option value="{{ $val }}" {{ $weightVal == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                {{-- Align --}}
                <div class="{{ $g['weight_key'] ? 'col-sm-3' : 'col-sm-4' }}">
                    <div class="form-group mb-2">
                        <label class="{{ $labelCls }}"><i class="fas fa-align-right mr-1"></i>المحاذاة</label>
                        <select name="settings[{{ $g['align_key'] }}]" class="{{ $inputCls }}">
                            @foreach ($alignOpts as $val => $lbl)
                                <option value="{{ $val }}" {{ $alignVal == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- Shared JS helper (safe to include multiple times — guarded by flag) --}}
<script>
if (typeof syncPicker === 'undefined') {
    function syncPicker(pickerId, hexVal) {
        if (/^#[0-9a-fA-F]{6}$/.test(hexVal)) {
            var el = document.getElementById(pickerId);
            if (el) el.value = hexVal;
        }
    }
}
</script>
