@php
    // UserDetails
    if (auth()->user())
    {
        $userID = encrypt(auth()->user()->id);
        $userName = auth()->user()->firstname." ".auth()->user()->lastname;
        $userImage = auth()->user()->image;
    }
    else
    {
        $userID = '';
        $userName = '';
        $userImage = '';
    }

    $client_settings = getClientSettings();
    $logo = isset($client_settings['shop_view_header_logo']) ? $client_settings['shop_view_header_logo'] : '';

    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

    // Subscrption ID
    $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

@endphp

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between text-center">
        <a href="{{ route('restaurant',$shop_slug) }}" target="_blank" class="logo d-flex align-items-center justify-content-center">
            @if(!empty($logo) && file_exists('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo))
                <img class="w-100" src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo) }}" alt="Logo">
            @else
                <span class="d-none d-lg-block">My Logo</span>
            @endif
        </a>

        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <button id="myHiddenButton" style="display: none;">Hidden</button>

            {{-- Notification Section --}}
            @if(isset($package_permissions['bell']) && !empty($package_permissions['bell']) && $package_permissions['bell'] == 1)
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-comments"></i>
                        <span class="badge bg-primary badge-number waiter-count">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header waiter-message">
                            You have 0 new Orders
                            <a href="{{ route('list.call.waiter') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- Notification Section --}}
            @if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number noti-count">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header noti-message">
                            You have 0 new Orders
                            <a href="{{ route('client.orders') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- Language Section --}}
            <li class="nav-item lang-drop pe-3">
                @php
                    $lang_id = session('lang_code');
                    $lang_id = !empty($lang_id) ? $lang_id : 'en';
                @endphp
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <x-dynamic-component width="35px" component="flag-language-{{ $lang_id }}" />
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                      <li><a class="dropdown-item" onclick="changeBackendLang('en')"><x-dynamic-component width="35px" component="flag-language-en" /> English</a></li>
                      <li><a class="dropdown-item" onclick="changeBackendLang('el')"><x-dynamic-component width="35px" component="flag-language-el" /> Greek</a></li>
                    </ul>
                  </div>
            </li>
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    @if (!empty($userImage) || $userImage != null)
                        <img src="{{ asset($userImage) }}" alt="Profile" class="rounded-circle">
                    @else
                        <img src="{{ asset('public/admin_images/demo_images/profiles/profile1.jpg') }}" alt="Profile" class="rounded-circle">
                    @endif
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ $userName }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ $userName }}</h6>
                        <span>Shop Admin</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('client.profile.view',$userID) }}">
                            <i class="fa-solid fa-user"></i>
                            <span>{{ __('My Profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('client.subscription',$userID) }}">
                            <i class="fa-solid fa-money-bill"></i>
                            <span>{{ __('Subscription') }}</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('billing.info') }}">
                            <i class="fa-solid fa-receipt"></i>
                            <span>{{ __('Billing Info') }}</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a href="{{ route('logout') }}" class="dropdown-item d-flex align-items-center">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
