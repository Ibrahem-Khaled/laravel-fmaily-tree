<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name') }}">
    <meta name="keywords" content="">
    <meta name="author" content="{{ config('app.name') }}">
    <link rel="icon" href="{{ asset('admin-assets/favicon.ico') }}">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/simplebar.css') }}">
    <!-- Fonts CSS -->
    <link
        href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/feather.css') }}">
    <!-- Font Awesome (used by tenant sidebar icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/uppy.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/quill.snow.css') }}">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/daterangepicker.css') }}">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/app-light.css') }}" id="lightTheme" disabled>
    <link rel="stylesheet" href="{{ asset('admin-assets/css/app-dark.css') }}" id="darkTheme">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard/custom.css') }}">

    @stack('styles')

</head>

<body class="vertical  dark rtl ">
    <!-- Preloader -->
    <div id="preloader"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #1b1e21; z-index: 9999; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="wrapper">
        <!-- navbar -->
        @include('layouts.dashboard.navbar')
        <!-- sidebar -->
        @include('layouts.dashboard.sidebar')
        <!-- main content -->
        <main role="main" class="main-content">
            @yield('content')
        </main> <!-- main -->
    </div> <!-- .wrapper -->

    <!-- Modals خارج الجدول والمحتوى لتفادي القص والتداخل -->
    @include('layouts.dashboard.modals.notifications')
    @include('layouts.dashboard.modals.shortcuts')

    <script>
        // Preloader hiding logic
        window.addEventListener('load', function () {
            var preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(function () {
                    preloader.style.display = 'none';
                }, 500);
            }
        });

        // Show preloader on theme switch (since it reloads)
        document.addEventListener('DOMContentLoaded', function () {
            // Sync body class with localStorage mode
            var currentMode = localStorage.getItem("mode");
            if (currentMode) {
                if (currentMode === 'dark') {
                    document.body.classList.add('dark');
                } else {
                    document.body.classList.remove('dark');
                    document.body.classList.add('light');
                }
            }

            var switcher = document.getElementById('modeSwitcher');
            if (switcher) {
                switcher.addEventListener('click', function () {
                    var preloader = document.getElementById('preloader');
                    if (preloader) {
                        preloader.style.display = 'flex';
                        preloader.style.opacity = '1';
                    }
                });
            }
        });
    </script>
    <script src="{{ asset('admin-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.stickOnScroll.js') }}"></script>
    <script src="{{ asset('admin-assets/js/tinycolor-min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/config.js') }}"></script>
    <script src="{{ asset('admin-assets/js/d3.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/topojson.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/datamaps.all.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/datamaps-zoomto.js') }}"></script>
    <script src="{{ asset('admin-assets/js/datamaps.custom.js') }}"></script>
    <script src="{{ asset('admin-assets/js/Chart.min.js') }}"></script>
    <script>
        /* defind global options */
        Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
        Chart.defaults.global.defaultFontColor = colors.mutedColor;
    </script>
    <script src="{{ asset('admin-assets/js/gauge.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/apexcharts.custom.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.timepicker.js') }}"></script>
    <script src="{{ asset('admin-assets/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/uppy.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/quill.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4',
        });
        $('.select2-multi').select2({
            multiple: true,
            theme: 'bootstrap4',
        });
        $('.drgpicker').daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            showDropdowns: true,
            locale: {
                format: 'MM/DD/YYYY'
            }
        });
        $('.time-input').timepicker({
            'scrollDefault': 'now',
            'zindex': '9999' /* fix modal open */
        });
        /** date range picker */
        if ($('.datetimes').length) {
            $('.datetimes').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });
        }
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            }
        }, cb);
        cb(start, end);
        $('.input-placeholder').mask("00/00/0000", {
            placeholder: "__/__/____"
        });
        $('.input-zip').mask('00000-000', {
            placeholder: "____-___"
        });
        $('.input-money').mask("#.##0,00", {
            reverse: true
        });
        $('.input-phoneus').mask('(000) 000-0000');
        $('.input-mixed').mask('AAA 000-S0S');
        $('.input-ip').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
            translation: {
                'Z': {
                    pattern: /[0-9]/,
                    optional: true
                }
            },
            placeholder: "___.___.___.___"
        });
        // editor
        var editor = document.getElementById('editor');
        if (editor) {
            var toolbarOptions = [
                [{
                    'font': []
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                    'header': 1
                },
                {
                    'header': 2
                }
                ],
                [{
                    'list': 'ordered'
                },
                {
                    'list': 'bullet'
                }
                ],
                [{
                    'script': 'sub'
                },
                {
                    'script': 'super'
                }
                ],
                [{
                    'indent': '-1'
                },
                {
                    'indent': '+1'
                }
                ], // outdent/indent
                [{
                    'direction': 'rtl'
                }], // text direction
                [{
                    'color': []
                },
                {
                    'background': []
                }
                ], // dropdown with defaults from theme
                [{
                    'align': []
                }],
                ['clean'] // remove formatting button
            ];
            var quill = new Quill(editor, {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
            });
        }
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <script>
        var uptarg = document.getElementById('drag-drop-area');
        if (uptarg) {
            var uppy = Uppy.Core().use(Uppy.Dashboard, {
                inline: true,
                target: uptarg,
                proudlyDisplayPoweredByUppy: false,
                theme: 'dark',
                width: 770,
                height: 210,
                plugins: ['Webcam']
            }).use(Uppy.Tus, {
                endpoint: 'https://master.tus.io/files/'
            });
            uppy.on('complete', (result) => {
                console.log('Upload complete! We’ve uploaded these files:', result.successful)
            });
        }
    </script>
    <script src="{{ asset('admin-assets/js/apps.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/dashboard/custom.js') }}"></script>
    <script src="{{ asset('js/currency-selector.js') }}"></script>
    @stack('scripts')
</body>

</html>