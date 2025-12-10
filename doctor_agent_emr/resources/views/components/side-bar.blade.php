<!-- Sidebar -->
<div class="kt-sidebar bg-background border-e border-e-border fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0 [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start top-0 bottom-0" id="sidebar">
    <x-side-bar-header />

    <div class="kt-sidebar-content flex grow shrink-0 py-5 pe-2" id="sidebar_content">
        <div class="kt-scrollable-y-hover grow shrink-0 flex ps-2 lg:ps-5 pe-1 lg:pe-3" data-kt-scrollable="true" data-kt-scrollable-dependencies="#sidebar_header" data-kt-scrollable-height="auto" data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_content" id="sidebar_scrollable">
            <!-- Sidebar Menu -->
            <div class="kt-menu flex flex-col grow gap-1" data-kt-menu="true" data-kt-menu-accordion-expand-all="false" id="sidebar_menu">
                <x-accordion-menu title="Dashboards" :active="request()->is('dashboards/*')">
                    <x-menu-item :active="request()->routeIs('dashboards.main')" :href="route('dashboards.main')">Dashboard</x-menu-item>
                </x-accordion-menu>

                <x-menu-group-header>Auth</x-menu-group-header>
                <x-accordion-menu title="Auth & Permissions" :active="request()->is('auth/*')">
                    <x-menu-item :active="request()->routeIs('auth.users.index')" :href="route('auth.users.index')">Users</x-menu-item>
                    <x-menu-item :active="request()->routeIs('auth.roles.index')" :href="route('auth.roles.index')">Roles</x-menu-item>
                    <x-menu-item :active="request()->routeIs('auth.permissions.index')" :href="route('auth.permissions.index')">Permissions</x-menu-item>
                </x-accordion-menu>

                <x-menu-group-header>Patient Management</x-menu-group-header>
                <x-accordion-menu title="Patients" :active="request()->is('patients/*')">
                    <x-menu-item :active="request()->routeIs('patients.index')" :href="route('patients.index')">Patients</x-menu-item>
                </x-accordion-menu>
            </div>
            <!-- End of Sidebar Menu -->
        </div>
    </div>
</div>
<!-- End of Sidebar -->
