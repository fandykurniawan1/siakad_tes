<li class="site-menu-category">Home</li>
<li class="site-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
    <a href="{{ route('backend.dashboard') }}">
        <i class="site-menu-icon wb-dashboard"></i>
        <span class="site-menu-title">Dashboard</span>
    </a>
</li>

@can (['view-merchant-list', 'view-banner-list'])
    <li class="site-menu-category">Main</li>
@endcan
@can ('view-merchant-list')
    <li class="site-menu-item {{ Request::is('merchant*') ? 'active' : '' }}">
        <a href="{{ route('backend.merchant.index') }}">
            <i class='site-menu-icon wb-home'></i>
            <span class='site-menu-title'>Merchants</span>
        </a>
    </li>
@endcan
@can ('view-banner-list')
    <li class='site-menu-item has-sub {{ Request::is('banner*') ? 'active' : '' }}'>
        <a href='{{ route('backend.banner.index') }}'>
            <i class='site-menu-icon wb-gallery'></i>
            <span class='site-menu-title'>Banner</span>
        </a>
    </li>
@endcan

@can (['view-master-data-list', 'view-user-list', 'view-user-login-history', 'view-role-list', 'change-preference'])
    <li class="site-menu-category">Settings</li>
@endcan

@can ('view-master-data-list')
    <li class="site-menu-item has-sub {{ Request::is('master*') ? 'active open' : '' }}">
        <a href="javascript:void(0)">
            <i class="site-menu-icon fa-database"></i>
            <span class="site-menu-title">Master Data</span>
            <span class="site-menu-arrow"></span>
        </a>
        <ul class="site-menu-sub">
            <li class="site-menu-item {{ Request::is('master/product-category*') ? 'active' : '' }}">
                <a class="animsition-link" href="{{ route('backend.master.product-category.index') }}">
                <span class="site-menu-title">Product Category</span>
                </a>
            </li>
            <li class="site-menu-item {{ Request::is('master/brand*') ? 'active' : '' }}">
                <a class="animsition-link" href="{{ route('backend.master.brand.index') }}">
                <span class="site-menu-title">Brand</span>
                </a>
            </li>
        </ul>
    </li>
@endcan
@can (['view-user-list', 'view-user-login-history', 'view-role-list'])
    <li class="site-menu-item has-sub {{ Request::is('management*') ? 'active open' : '' }}">
        <a href="javascript:void(0)">
            <i class="site-menu-icon wb-user"></i>
            <span class="site-menu-title">User Management</span>
            <span class="site-menu-arrow"></span>
        </a>
        <ul class="site-menu-sub">
            @can ('view-user-list')
                <li class="site-menu-item {{ Request::is('management/user*') && !Request::is('management/user/login-history') ? 'active' : '' }}">
                    <a class="animsition-link" href="{{ route('backend.management.user.index') }}">
                        <span class="site-menu-title">Users</span>
                    </a>
                </li>
            @endcan
            @can ('view-user-login-history')
                <li class="site-menu-item {{ Request::is('management/user/login-history') ? 'active' : '' }}">
                    <a class="animsition-link" href="{{ route('backend.management.user.login-history') }}">
                        <span class="site-menu-title">User Login History</span>
                    </a>
                </li>
            @endcan
            @can ('view-role-list')
                <li class="site-menu-item {{ Request::is('management/role*') ? 'active' : '' }}">
                    <a class="animsition-link" href="{{ route('backend.management.role.index') }}">
                        <span class="site-menu-title">Role & Permission</span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan
@can ('change-preference')
    <li class='site-menu-item {{ Request::is('preference*') ? 'active' : '' }}'>
        <a href='{{ route('backend.preference.edit') }}'>
            <i class='site-menu-icon wb-settings'></i>
            <span class='site-menu-title'>Preference</span>
        </a>
    </li>
    @endcan
    @can ('view-email-template-list')
    <li class='site-menu-item {{ Request::is('email*') ? 'active' : '' }}'>
        <a href='{{ route('backend.email.template.index') }}'>
            <i class='site-menu-icon wb-settings'></i>
            <span class='site-menu-title'>Email Template</span>
        </a>
    </li>
@endcan