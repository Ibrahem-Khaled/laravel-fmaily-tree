<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- خطوط عربية -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Select2 الأساس -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- ثيم Select2 المتوافق مع Bootstrap 4 (متوافق مع SB Admin 2 المبني على Bootstrap 4) -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" />

    <!-- Daterangepicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- ستايل أساسي للخط + بوليش للمدخلات -->
    <style>
        body {
            font-family: "Tajawal", sans-serif !important;
            /* direction: rtl !important; */
            text-align: right !important;
        }

        .form-control {
            border-radius: .6rem;
            border-color: #dfe3e8;
            transition: box-shadow .2s, border-color .2s;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .25);
        }

        .form-control::placeholder {
            opacity: .75;
        }

        /* توحيد ارتفاع select2 مع .form-control */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            border-radius: .6rem;
            border-color: #dfe3e8;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: calc(2.25rem);
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem);
        }

        /* دعم RTL لمجموعات الإدخال */
        :dir(rtl) .input-group .input-group-prepend {
            order: 2;
        }

        :dir(rtl) .input-group .input-group-append {
            order: 1;
        }

        :dir(rtl) .input-group .input-group-text {
            border-left: 0;
            border-right: 1px solid #dfe3e8;
        }
    </style>

    <!-- ستايل SB Admin 2 (مبني على Bootstrap 4) -->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body id="page-top">

    <div id="wrapper">
        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.header')

                <div class="container-fluid">
                    {{-- تأكد في صفحات الفورم إن كل input/select/textarea عليه class="form-control" --}}
                    @yield('content')
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- jQuery + Bootstrap 4 (المستخدمة بواسطة SB Admin 2) -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- إضافات -->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Select2 + تعريب عربي -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>

    <!-- Moment + Daterangepicker -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(function() {
            // إعدادات افتراضية لـ Select2
            $.fn.select2.defaults.set('theme', 'bootstrap4'); // ثيم Bootstrap 4
            $.fn.select2.defaults.set('language', 'ar'); // تعريب الرسائل
            $.fn.select2.defaults.set('minimumResultsForSearch', 0); // إظهار مربع البحث دائمًا
            $.fn.select2.defaults.set('width', '100%'); // عرض مثل .form-control

            // تفعيل Select2 لكل select (عدا .no-search) + دعم المودال تلقائيًا
            $('select:not(.no-search)').each(function() {
                const $select = $(this);
                const $modal = $select.closest('.modal');
                const opts = {};
                if ($modal.length) opts.dropdownParent = $modal; // مهم داخل المودال
                $select.select2(opts);
            });

            // تركيز تلقائي على حقل البحث عند فتح المنسدلة
            $(document).on('select2:open', function() {
                const input = document.querySelector('.select2-container--open .select2-search__field');
                if (input) input.focus();
            });

            // مثال تفعيل Daterangepicker على أي input عليه data-daterangepicker
            // (احرص يكون input عليه class="form-control" ليتنسّق صح)
            $('[data-daterangepicker]').each(function() {
                $(this).daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'مسح',
                        applyLabel: 'تطبيق'
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate
                        .format('YYYY-MM-DD'));
                }).on('cancel.daterangepicker', function() {
                    $(this).val('');
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
