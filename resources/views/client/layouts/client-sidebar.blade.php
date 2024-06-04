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

    // ShopName
    $shop_name = isset(Auth::user()->hasOneShop->shop['name']) ? Auth::user()->hasOneShop->shop['name'] : '';

    $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

    // Current Route Name
    $routeName = Route::currentRouteName();

    // Route Params
    $routeParams = Route::current()->parameters();

    // Subscrption ID
    $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    $layout  = getcheckLayout();

@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'client.dashboard') ? 'active-tab' : '' }}" href="{{ route('client.dashboard') }}">
                <i class="fa-solid fa-house-chimney {{ ($routeName == 'client.dashboard') ? 'icon-tab' : '' }}"></i>
                <span>{{ $shop_name }}</span>
            </a>
        </li>


        {{-- Design Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ (($routeName != 'design.logo') && ($routeName != 'design.cover') && ($routeName != 'banners') && ($routeName != 'design.theme') && ($routeName != 'design.theme-preview') && ($routeName != 'theme.clone') && ($routeName != 'special.icons') && ($routeName != 'special.icons.add') && ($routeName != 'special.icons.edit')) && ($routeName != 'desgin.layout') ? 'collapsed' : '' }} {{ (($routeName == 'design.logo') || ($routeName == 'design.cover') || ($routeName == 'banners') || ($routeName == 'design.theme') || ($routeName == 'design.theme-preview') || ($routeName == 'theme.clone') || ($routeName == 'special.icons') || ($routeName == 'special.icons.add') || ($routeName == 'special.icons.edit') || ($routeName == 'desgin.layout')) ? 'active-tab' : '' }}" data-bs-target="#design-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ (($routeName == 'design.logo') || ($routeName == 'design.cover') || ($routeName == 'banners') || ($routeName == 'design.theme') || ($routeName == 'design.theme-preview') || ($routeName == 'theme.clone') || ($routeName == 'special.icons') || ($routeName == 'special.icons.add') || ($routeName == 'special.icons.edit') || ($routeName == 'desgin.layout')) ? 'true' : 'false' }}">
                <i class="fa-solid fa-pen-nib {{ (($routeName == 'design.logo') || ($routeName == 'design.cover') || ($routeName == 'banners') || ($routeName == 'design.theme') || ($routeName == 'desgin.layout') || ($routeName == 'design.mail.forms' || ($routeName == 'design.theme-preview') || ($routeName == 'theme.clone') || ($routeName == 'special.icons') || ($routeName == 'special.icons.add') || ($routeName == 'special.icons.edit'))) ? 'icon-tab' : '' }}"></i><span>{{ __('Design') }}</span><i class="bi bi-chevron-down ms-auto {{ (($routeName == 'design.logo') || ($routeName == 'design.cover') || ($routeName == 'banners') || ($routeName == 'design.theme') || ($routeName == 'design.theme-preview') || ($routeName == 'theme.clone') || ($routeName == 'special.icons') || ($routeName == 'special.icons.add') || ($routeName == 'special.icons.edit') || ($routeName != 'desgin.layout')) ? 'icon-tab' : '' }}"></i>
            </a>
            <ul id="design-nav" class="nav-content sidebar-ul collapse {{ (($routeName == 'design.logo') || ($routeName == 'design.cover') || ($routeName == 'banners') || ($routeName == 'design.theme') || ($routeName == 'design.theme-preview') || ($routeName == 'theme.clone') || ($routeName == 'special.icons') || ($routeName == 'special.icons.add') || ($routeName == 'special.icons.edit') || ($routeName == 'desgin.layout')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                {{-- <li>
                    <a href="{{ route('design.logo') }}" class="{{ ($routeName == 'design.logo') ? 'active-link' : '' }}">
                        <span>{{ __('Logo') }}</span>
                    </a>
                </li> --}}
                @if(isset($package_permissions['cover']) && !empty($package_permissions['cover']) && $package_permissions['cover'] == 1 || isset($package_permissions['cover_cube']) && !empty($package_permissions['cover_cube']) && $package_permissions['cover_cube'] == 1 )

                <li>
                    <a href="{{ route('design.cover') }}" class="{{ ($routeName == 'design.cover') ? 'active-link' : '' }}">
                        <span>{{ __('Cover') }}</span>
                    </a>
                </li>
                @endif

                {{-- Banner --}}
                @if(isset($package_permissions['banner']) && !empty($package_permissions['banner']) && $package_permissions['banner'] == 1)
                    <li>
                        <a href="{{ route('banners') }}" class="{{ ($routeName == 'banners') ? 'active-link' : '' }}">
                            <span>{{ __('Banners') }}</span>
                        </a>
                    </li>
                @endif

                    <li>
                        <a href="{{ route('design.theme') }}" class="{{ ($routeName == 'design.theme' || $routeName == 'design.theme-preview' || $routeName == 'theme.clone') ? 'active-link' : '' }}">
                            <span>{{ __('Themes') }}</span>
                        </a>
                    </li>


                {{-- <li>
                    <a href="{{ route('desgin.layout') }}" class="{{ ($routeName == 'desgin.layout' ? 'active-link' : '') }}" >
                        <span>{{ __('Layouts') }}</span>
                    </a>
                </li> --}}

                @if(isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1)
                    <li>
                        <a href="{{ route('special.icons') }}" class="{{ ($routeName == 'special.icons' || $routeName == 'special.icons.add' || $routeName == 'special.icons.edit') ? 'active-link' : '' }}">
                            <span>{{ __('Special Icons') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        {{-- Menu Nav --}}
        <li class="nav-item">
            {{-- && --}}
            <a class="nav-link {{ (($routeName != 'categories') && ($routeName != 'items') && ($routeName != 'tags')  && ($routeName != 'options')) ? 'collapsed' : '' }} {{ (($routeName == 'categories') || ($routeName == 'items') || ($routeName == 'tags')  || ($routeName == 'options')) ? 'active-tab' : '' }}" data-bs-target="#menu-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ (($routeName == 'categories') || ($routeName == 'items') || ($routeName == 'tags')  || ($routeName == 'options')) ? 'true' : 'false' }}">
                <i class="fa-solid fa-bars {{ (($routeName == 'categories') || ($routeName == 'items') || ($routeName == 'tags'))  ? 'icon-tab' : '' }}"></i><span>{{ __('QR Catalogue') }}</span><i class="bi bi-chevron-down ms-auto {{ (($routeName == 'categories') || ($routeName == 'items') || ($routeName == 'tags')  || ($routeName == 'options')) ? 'icon-tab' : '' }}"></i>
            </a>
            <ul id="menu-nav" class="nav-content sidebar-ul collapse {{ (($routeName == 'categories') || ($routeName == 'items') || ($routeName == 'tags')  || ($routeName == 'options')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('categories') }}" class="{{ (($routeName == 'categories') &&  count($routeParams) == 0) ? 'active-link' : '' }}">
                        <span>{{ __('Categories') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('items') }}" class="{{ ($routeName == 'items') ? 'active-link' : '' }}">
                        <span>{{ __('Items') }}</span>
                    </a>
                </li>

                @if(isset($package_permissions['page']) && !empty($package_permissions['page']) && $package_permissions['page'] == 1)
                    <li>
                        <a href="{{ route('categories','page') }}" class="{{ (($routeName == 'categories') && (isset($routeParams['cat_id']) && $routeParams['cat_id'] == 'page')) ? 'active-link' : '' }}">
                            <span>{{ __('Pages') }}</span>
                        </a>
                    </li>
                @endif

                @if(isset($package_permissions['link']) && !empty($package_permissions['link']) && $package_permissions['link'] == 1)
                    <li>
                        <a href="{{ route('categories','link') }}" class="{{ (($routeName == 'categories') && (isset($routeParams['cat_id']) && $routeParams['cat_id'] == 'link')) ? 'active-link' : '' }}">
                            <span>{{ __('Links') }}</span>
                        </a>
                    </li>
                @endif

                @if(isset($package_permissions['gallery']) && !empty($package_permissions['gallery']) && $package_permissions['gallery'] == 1)
                    <li>
                        <a href="{{ route('categories','gallery') }}" class="{{ (($routeName == 'categories') && (isset($routeParams['cat_id']) && $routeParams['cat_id'] == 'gallery')) ? 'active-link' : '' }}">
                            <span>{{ __('Galleries') }}</span>
                        </a>
                    </li>
                @endif

                @if(isset($package_permissions['check_in']) && !empty($package_permissions['check_in']) && $package_permissions['check_in'] == 1)
                    <li>
                        <a href="{{ route('categories','check_in') }}" class="{{ (($routeName == 'categories') && (isset($routeParams['cat_id']) && $routeParams['cat_id'] == 'check_in')) ? 'active-link' : '' }}">
                            <span>{{ __('Check In Pages') }}</span>
                        </a>
                    </li>
                @endif

                @if(isset($package_permissions['pdf_file']) && !empty($package_permissions['pdf_file']) && $package_permissions['pdf_file'] == 1)
                    <li>
                        <a href="{{ route('categories','pdf_page') }}" class="{{ (($routeName == 'categories') && (isset($routeParams['cat_id']) && $routeParams['cat_id'] == 'pdf_page')) ? 'active-link' : '' }}">
                            <span>{{ __('PDF') }}</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('tags') }}" class="{{ ($routeName == 'tags') ? 'active-link' : '' }}">
                        <span>{{ __('Tags') }}</span>
                    </a>
                </li>


                @if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                    <li>
                        <a href="{{ route('options') }}" class="{{ ($routeName == 'options') ? 'active-link' : '' }}">
                            <span>{{ __('Order Attributes') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        {{-- Orders Nav --}}
        @if((isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1) || isset($package_permissions['bell']) && !empty($package_permissions['bell']) && $package_permissions['bell'] == 1)
            <li class="nav-item">
                <a class="nav-link {{ (($routeName != 'client.orders') && ($routeName != 'client.orders.history') && ($routeName != 'view.order') && ($routeName != 'list.call.waiter') && ($routeName != 'show.call.waiter')) ? 'collapsed' : '' }} {{ (($routeName == 'client.orders') || ($routeName == 'view.order') || ($routeName == 'client.orders.history') || ($routeName == 'list.call.waiter') || ($routeName == 'show.call.waiter')) ? 'active-tab' : '' }}" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ (($routeName == 'client.orders') || ($routeName == 'view.order') || ($routeName == 'client.orders.history') || ($routeName == 'list.call.waiter') || ($routeName == 'show.call.waiter')) ? 'true' : 'false' }}">
                    <i class="bi bi-cart-check {{ (($routeName == 'client.orders') || ($routeName == 'view.order') || ($routeName == 'client.orders.history') || ($routeName == 'list.call.waiter') || ($routeName == 'show.call.waiter')) ? 'icon-tab' : '' }}"></i><span>{{ __('Orders') }}</span><i class="bi bi-chevron-down ms-auto {{ (($routeName == 'client.orders') || ($routeName == 'client.orders.history') || ($routeName == 'list.call.waiter') || ($routeName == 'show.call.waiter')) ? 'icon-tab' : '' }}"></i>
                </a>
                <ul id="orders-nav" class="nav-content sidebar-ul collapse {{ (($routeName == 'client.orders') || ($routeName == 'view.order') || ($routeName == 'client.orders.history') || ($routeName == 'list.call.waiter') || ($routeName == 'show.call.waiter')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                    @if(isset($package_permissions['bell']) && !empty($package_permissions['bell']) && $package_permissions['bell'] == 1)
                        <li>
                            <a href="{{ route('list.call.waiter') }}" class="{{ (($routeName == 'list.call.waiter') || ($routeName == 'show.call.waiter')) ? 'active-link' : '' }}">
                                <span>{{ __('Waiter Call') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)                        
                        <li>
                            <a href="{{ route('client.orders') }}" class="{{ (($routeName == 'client.orders')) ? 'active-link' : '' }}">
                                <span>{{ __('Pending Orders') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.orders.history') }}" class="{{ (($routeName == 'client.orders.history') || ($routeName == 'view.order')) ? 'active-link' : '' }}">
                                <span>{{ __('Orders History') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif



        {{-- Settings Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ (($routeName != 'design.general-info') && ($routeName != 'client.subscription') && ($routeName != 'design.mail.forms') && ($routeName != 'languages') && ($routeName != 'payment.settings') && ($routeName != 'order.settings') && ($routeName != 'printer.settings'))  && ($routeName != 'rate.services') && ($routeName != 'rate.services.add') && ($routeName != 'rate.services.edit')  && ($routeName != 'rooms') && ($routeName != 'rooms.add') && ($routeName != 'rooms.edit') && ($routeName != 'coupons') && ($routeName != 'coupons.add') && ($routeName != 'coupons.edit')  ? 'collapsed' : '' }} {{ (($routeName == 'design.general-info') || ($routeName == 'design.mail.forms') || ($routeName == 'languages') || ($routeName == 'payment.settings') || ($routeName == 'order.settings') | ($routeName == 'printer.settings') || ($routeName == 'rate.services') || ($routeName == 'rate.services.add') || ($routeName == 'rate.services.edit') || ($routeName == 'coupons') || ($routeName == 'coupons.add') || ($routeName == 'coupons.edit')) ? 'active-tab' : '' }}" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ (($routeName == 'design.general-info') || ($routeName == 'design.mail.forms') || ($routeName == 'languages') || ($routeName == 'payment.settings') || ($routeName == 'order.settings') || ($routeName == 'order.settings') | ($routeName == 'printer.settings')  || ($routeName == 'rate.services') || ($routeName == 'rate.services.add') || ($routeName == 'rate.services.edit') || ($routeName == 'coupons') || ($routeName == 'coupons.add') || ($routeName == 'coupons.edit')) ? 'true' : 'false' }}">
                <i class="fa-solid fa-gear  {{ (($routeName == 'design.general-info') || ($routeName == 'design.mail.forms') || ($routeName == 'languages') || ($routeName == 'payment.settings') || ($routeName == 'order.settings') || ($routeName == 'order.settings') | ($routeName == 'printer.settings') ||   ($routeName == 'rate.services') || ($routeName == 'rate.services.add') || ($routeName == 'rate.services.edit') || ($routeName == 'coupons') || ($routeName == 'coupons.add') || ($routeName == 'coupons.edit')) ? 'icon-tab' : '' }}"></i><span>{{ __('Settings') }}</span><i class="bi bi-chevron-down ms-auto {{ (($routeName == 'design.general-info') || ($routeName == 'design.mail.forms') || ($routeName == 'languages') || ($routeName == 'payment.settings') || ($routeName == 'order.settings') || ($routeName == 'order.settings') | ($routeName == 'printer.settings')  || ($routeName == 'rate.services') || ($routeName == 'rate.services.add') || ($routeName == 'rate.services.edit') || ($routeName == 'coupons') || ($routeName == 'coupons') || ($routeName == 'coupons.add') || ($routeName == 'coupons.edit')) ? 'icon-tab' : '' }}"></i>
            </a>
            <ul id="settings-nav" class="nav-content sidebar-ul collapse  {{ (($routeName == 'design.general-info') || ($routeName == 'design.mail.forms') || ($routeName == 'languages') || ($routeName == 'payment.settings') || ($routeName == 'order.settings') || ($routeName == 'order.settings') | ($routeName == 'printer.settings') || ($routeName == 'rate.services') || ($routeName == 'rate.services.add') || ($routeName == 'rate.services.edit') || ($routeName == 'coupons') || ($routeName == 'coupons') || ($routeName == 'coupons.add') || ($routeName == 'coupons.edit')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('design.general-info') }}" class="{{ ($routeName == 'design.general-info') ? 'active-link' : '' }}">
                        <span>{{ __('General') }}</span>
                    </a>
                </li>
                @if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                    <li>
                        <a href="{{ route('order.settings') }}" class="{{ (($routeName == 'order.settings') &&  count($routeParams) == 0) ? 'active-link' : '' }}">
                            <span>{{ __('Order Rules') }}</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('coupons') }}" class="{{ (($routeName == 'coupons') || ($routeName == 'coupons.add') || ($routeName == 'coupons.edit')) ? 'active-link' : '' }}">
                        <span>{{ __('Coupons') }}</span>
                    </a>
                </li>

                @if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                    <li>
                        <a href="{{ route('payment.settings') }}" class="{{ (($routeName == 'payment.settings')) ? 'active-link' : '' }}">
                            <span>{{ __('Payment Methods') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('printer.settings') }}" class="{{ (($routeName == 'printer.settings')) ? 'active-link' : '' }}">
                            <span>{{ __('Print') }}</span>
                        </a>
                    </li>
                @endif

                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                <li>
                    <a href="{{ route('rate.services') }}" class="{{ ($routeName == 'rate.services') || ($routeName == 'rate.services.add') || ($routeName == 'rate.services.edit')   ? 'active-link' : '' }}">
                        <span>{{ __('Grading') }}</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('languages') }}" class="{{ ($routeName == 'languages') ? 'active-link' : '' }}">
                        <span>{{ __('Languages') }}</span>
                    </a>
                </li>

                @if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                    <li>
                        <a href="{{ route('design.mail.forms') }}" class="{{ ($routeName == 'design.mail.forms') ? 'active-link' : '' }}">
                            <span>{{ __('Mail Forms') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        {{-- Assets Nav --}}
        @if((isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1) || isset($package_permissions['bell']) && !empty($package_permissions['bell']) && $package_permissions['bell'] == 1)
            <li class="nav-item">
                <a href="" class="nav-link {{ (($routeName != 'rooms') && ($routeName != 'rooms.add') && ($routeName != 'rooms.edit') && ($routeName != 'tables') && ($routeName != 'tables.create') && ($routeName != 'tables.edit') && ($routeName != 'staffs') && ($routeName != 'staffs.add') && ($routeName != 'staffs.edit')) ? 'collapsed' : '' }} {{ (($routeName == 'rooms') || ($routeName == 'rooms.add') || ($routeName == 'rooms.edit') || ($routeName == 'tables') || ($routeName == 'tables.create') || ($routeName == 'tables.edit') || ($routeName == 'staffs') || ($routeName == 'staffs.add') || ($routeName == 'staffs.edit')) ? 'active-tab' : '' }}" data-bs-target="#servicepoints-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ (( $routeName == 'rooms') || ($routeName == 'rooms.add') || ($routeName == 'rooms.edit') || ( $routeName == 'tables') || ($routeName == 'tables.create') || ($routeName == 'tables.edit') || ($routeName == 'staffs') || ($routeName == 'staffs.add') || ($routeName == 'staffs.edit')) ? 'true' : 'false' }}">
                            <span><i class="fa-solid fa-handshake"></i>{{ __('Service Assets') }}</span><i class="bi bi-chevron-down ms-auto {{ (( $routeName == 'rooms') || ($routeName == 'rooms.add') || ($routeName == 'rooms.edit') || ( $routeName == 'tables') || ($routeName == 'tables.create') || ($routeName == 'tables.edit')) ? 'icon-tab' : '' }}"></i>
                </a>
                <ul id="servicepoints-nav" class="nav-content collapse {{ (( $routeName == 'rooms') || ($routeName == 'rooms.add') || ($routeName == 'rooms.edit') || ( $routeName == 'tables') || ($routeName == 'tables.create') || ($routeName == 'tables.edit') || ($routeName == 'staffs') || ($routeName == 'staffs.add') || ($routeName == 'staffs.edit')) ? 'show' : '' }}" data-bs-parent="#servicepoints-nav">
                    <li class="ms-3">
                            <a href="{{ route('staffs') }}" class="{{ ($routeName == 'staffs') || ($routeName == 'staffs.add') || ($routeName == 'staffs.edit') ? 'active-tab' : '' }}">
                                <span>{{ __('Employees') }}</span>
                            </a>
                    </li>
                    <li class="ms-3">
                        <a href="{{ route('tables') }}" class="{{ (($routeName == 'tables') || ($routeName == 'tables.create') || ($routeName == 'tables.edit')) ? 'active-tab' : '' }}">
                            <span>{{ __('Tables') }}</span>
                        </a>
                    </li>
                    <li class="ms-3">
                        <a href="{{ route('rooms') }}" class="{{ (($routeName == 'rooms') || ($routeName == 'rooms.add') || ($routeName == 'rooms.edit')) ? 'active-tab' : '' }}">
                            <span>{{ __('Rooms') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Preview Nav --}}
        <li class="nav-item">
            <a class="nav-link" onclick="previewMyShop('{{ $shop_slug }}')" style="cursor: pointer">
                <i class="fa-solid fa-eye"></i>
                <span>{{ __('Preview') }}</span>
            </a>
        </li>


        {{-- QrCode Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'qrcode') ? 'active-tab' : '' }}" href="{{ route('qrcode') }}">
                <i class="fa-solid fa-qrcode {{ ($routeName == 'qrcode') ? 'icon-tab' : '' }}"></i>
            <span>{{ __('Get QR Code') }}</span>
            </a>
        </li>

        {{-- Statistics Nav --}}
        <li class="nav-item">
            <a href="" class="nav-link {{ (($routeName != 'statistics') && ($routeName != 'items.reviews') && ($routeName != 'services.review') && ($routeName != 'services.view')) ? 'collapsed' : '' }} {{ (($routeName == 'statistics') || ($routeName == 'items.reviews') || ($routeName == 'services.review') || ($routeName == 'services.view')) ? 'active-tab' : '' }}" data-bs-target="#statistics-nav" data-bs-toggle="collapse" href="#" aria-expanded="{{ (( $routeName == 'statistics') ||  ($routeName == 'items.reviews') || ($routeName == 'services.review') || ($routeName == 'services.view')) ? 'true' : 'false' }}">
                        <span><i class="fa-solid fa-chart-line"></i>{{ __('Statistics') }}</span><i class="bi bi-chevron-down ms-auto {{ (( $routeName == 'statistics') ||  ($routeName == 'items.reviews') || ($routeName == 'services.review') || ($routeName == 'services.view')) ? 'icon-tab' : '' }}"></i>
            </a>
            <ul id="statistics-nav" class="nav-content collapse {{ (( $routeName == 'statistics') ||  ($routeName == 'items.reviews') || ($routeName == 'services.review') || ($routeName == 'services.view')) ? 'show' : '' }}" data-bs-parent="#statistics-nav">
                <li class="ms-3">
                    <a href="{{ route('statistics') }}" class="{{ ($routeName == 'statistics') ? 'active-tab' : '' }}">
                        <span>{{ __('Analytics') }}</span>
                    </a>
                </li>

                <li class="ms-3">
                    <a href="{{ route('items.reviews') }}" class="{{ ($routeName == 'items.reviews') ? 'active-tab' : '' }}">
                        <span>{{ __('Items Reviews') }}</span>
                    </a>
                </li>
                
                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                    <li class="ms-3">
                        <a href="{{ route('services.review') }}" class="{{ ($routeName == 'services.review') || ($routeName == 'services.view') ? 'active-tab' : '' }}">
                            <span>{{ __('Services Reviews') }}</span>
                        </a>
                    </li>
                @endif
            </ul>

        </li>

        {{-- Statistics Nav --}}
        <!-- <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'statistics') ? 'active-tab' : '' }}" href="{{ route('statistics') }}">
                <i class="fa-solid fa-chart-line {{ ($routeName == 'statistics') ? 'icon-tab' : '' }}"></i>
            <span>{{ __('Statistics') }}</span>
            </a>
        </li> -->

        {{-- Item Reviews Nav --}}
        <!-- <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'items.reviews') ? 'active-tab' : '' }}" href="{{ route('items.reviews') }}">
                <i class="fa-solid fa-comments {{ ($routeName == 'items.reviews') ? 'icon-tab' : '' }}"></i>
            <span>{{ __('Item Reviews') }}</span>
            </a>
        </li> -->

        {{-- Service Reviews Nav --}}
        <!-- <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'services.review') || ($routeName == 'services.view') ? 'active-tab' : '' }}" href="{{ route('services.review') }}">
                <i class="fa-solid fa-comments {{ ($routeName == 'services.review') || ($routeName == 'services.view') ? 'icon-tab' : '' }}"></i>
            <span>{{ __('Service Reviews') }}</span>
            </a>
        </li> -->


        {{-- Tutorial Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'tutorial.show') ? 'active-tab' : '' }}" href="{{ route('tutorial.show')}}">
                <i class="fa-solid fa-circle-info {{ ($routeName == 'tutorial.show') ? 'icon-tab' : '' }}"></i>
            <span>{{ __('Tutorial') }}</span>
            </a>
        </li>

        {{-- Contact Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ ($routeName == 'contact') ? 'active-tab' : '' }}" href="{{ route('contact') }}">
                <i class="fa-solid fa-address-card {{ ($routeName == 'contact') ? 'icon-tab' : '' }}"></i>
            <span>{{ __('Contact') }}</span>
            </a>
        </li>


        {{-- Logout Nav --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
            <span>{{ __('Logout') }}</span>
            </a>
        </li>

    </ul>
</aside>
