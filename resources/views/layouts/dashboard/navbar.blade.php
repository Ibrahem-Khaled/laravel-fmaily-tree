<nav class="topnav navbar navbar-light">
    @php
        // Navbar relies on user avatar + unread notifications.
        // In this project, there is no App\Http\Resources\UserResource, so we compute safely here.
        $user = auth()->user();
        $avatarUrl = $user && !empty($user->avatar_url)
            ? $user->avatar_url
            : asset('admin-assets/assets/avatars/default.png');

        $unreadNotificationsCount = 0;
        try {
            if ($user && method_exists($user, 'unreadNotifications')) {
                $unreadNotificationsCount = $user->unreadNotifications->count();
            }
        } catch (\Throwable $e) {
            $unreadNotificationsCount = 0;
        }
    @endphp
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>
    <!-- <form class="form-inline mr-auto searchform text-muted">
        <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search"
            placeholder="Type something..." aria-label="Search">
    </form> -->
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="{{ route('home') }}" target="_blank" title="عرض المتجر">
                <i class="fe fe-external-link fe-16"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="dark">
                <i class="fe fe-sun fe-16"></i>
            </a>
        </li>

        <!-- Currency Selector -->
        {{-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted my-2" href="#" id="currencyDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fe fe-dollar-sign fe-16"></i>
                <span class="currency-code ml-1 small fw-bold">
                    {{ currentCurrency() ? currentCurrency()->code : 'YER' }}
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-lg animated--grow-in border-0"
                aria-labelledby="currencyDropdown" style="min-width: 200px;">
                <h6 class="dropdown-header text-uppercase font-weight-bold py-3 bg-light rounded-top">
                    <i class="fe fe-refresh-cw mr-1"></i> اختر العملة
                </h6>
                @php
                    $currencyService = app(\App\Services\Currency\CurrencyService::class);
                    $currencies = $currencyService->getActiveCurrencies();
                    $currentCurrency = $currencyService->getCurrentCurrency();
                @endphp
                @foreach($currencies as $currency)
                    <a class="dropdown-item currency-item py-2 px-3 {{ $currentCurrency && $currentCurrency->code === $currency->code ? 'active bg-primary-light text-primary' : '' }}"
                        href="javascript:void(0)" data-currency-code="{{ $currency->code }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="currency-details">
                                <span class="d-block font-weight-bold">{{ $currency->code }}</span>
                                <small class="text-muted">{{ $currency->name_ar }}</small>
                                @if($currentCurrency && $currentCurrency->code === $currency->code)
                                    <i class="fe fe-check text-success ml-1"></i>
                                @endif
                            </div>
                            <span class="badge badge-light border">{{ number_format($currency->exchange_rate, 2) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </li> --}}

        <li class="nav-item">
            <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
                <span class="fe fe-grid fe-16"></span>
            </a>
        </li>
        <li class="nav-item nav-notif">
            <a class="nav-link text-muted my-2" href="#" data-toggle="modal" data-target=".modal-notif">
                <span class="fe fe-bell fe-16"></span>
                @if($unreadNotificationsCount > 0)
                    <span class="dot dot-md bg-success"></span>
                @endif
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="avatar avatar-sm mt-2">
                    <img src="{{ $avatarUrl }}" alt="Avatar"
                        class="avatar-img rounded-circle border shadow-sm">
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" aria-labelledby="navbarDropdownMenuLink">
                <div class="dropdown-header text-center py-3">
                    <img src="{{ $avatarUrl }}" alt="Avatar"
                        class="avatar-img rounded-circle border shadow-sm mb-2" style="width: 50px; height: 50px;">
                    <p class="mb-0 font-weight-bold">{{ auth()->user()->name }}</p>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </div>
                <div class="dropdown-divider"></div>
                @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                        <i class="fe fe-user fe-14 mr-2 text-muted"></i> الملف الشخصي
                    </a>
                @else
                    <a class="dropdown-item py-2" href="#">
                        <i class="fe fe-user fe-14 mr-2 text-muted"></i> الملف الشخصي
                    </a>
                @endif
                <a class="dropdown-item py-2 text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fe fe-log-out fe-14 mr-2 text-danger"></i> تسجيل الخروج
                </a>
            </div>
        </li>
    </ul>
</nav>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">تسجيل الخروج</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                هل انت متاكد من تسجيل الخروج؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                <a class="btn btn-danger" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
