<ul id="side-menu" x-data="{selected:0}" class="flex flex-col float-none w-full px-1 pb-6 font-medium sidebar-small-menu">
    @can('view-any', App\Models\Farm::class)
        <x-dashboard.sidebar-link name="Dashboard" href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="{{ 'bxs-dashboard' }}">

        </x-dashboard.sidebar-link>
    @endcan
    @can('view-any', App\Models\Farm::class)
        <x-dashboard.sidebar-link name="Medicinas" href="{{ route('medicines.index') }}" :active="request()->routeIs('medicines.index')" icon="{{ 'bxs-capsule' }}">

        </x-dashboard.sidebar-link>
    @endcan
    @can('view-any', App\Models\Farm::class)
        <x-dashboard.sidebar-link name="Finca" href="{{ route('farms.index') }}" :active="request()->routeIs('farms.index')" icon="{{ 'bx-landscape' }}">

        </x-dashboard.sidebar-link>
    @endcan
    @can('view-any', App\Models\Cow::class)
        <x-dashboard.sidebar-link id="1" name="Vacas" href="javascript:;" :active="request()->routeIs('cows.index') || request()->routeIs('cows.genealogy')" icon="{{ 'bxs-group' }}">
            <x-dashboard.child-link name="Todas" href="{{ route('cows.index') }}" />
            <x-dashboard.child-link name="Árbol Genealógico" href="{{ route('cows.genealogy') }}" />
            <x-dashboard.child-link name="Historial" href="{{ route('histories.index') }}" />
            <x-dashboard.child-link name="Ventas" href="{{ route('solds.index') }}" />
        </x-dashboard.sidebar-link>
    @endcan
    @can('view-any', App\Models\Medicine::class)
        <x-dashboard.sidebar-link id="2" name="Medicinas" href="javascript:;" :active="request()->routeIs('medicines.index')" icon="{{ 'bxs-capsule' }}">
            <x-dashboard.child-link name="Todas" href="{{ route('medicines.index') }}" />
            <x-dashboard.child-link name="Tiendas" href="{{ route('markets.index') }}" />
            <x-dashboard.child-link name="Fabricantes" href="{{ route('manufacturers.index') }}" />
        </x-dashboard.sidebar-link>
    @endcan

    @if (Auth::user()->can('create', Spatie\Permission\Models\Role::class) ||
                Auth::user()->can('create', Spatie\Permission\Models\Permission::class))
        <!-- Section Devider -->
        <div class="pt-4 pb-1 pl-0 mb-4 text-xs text-white section" :class="isSidebarExpanded ? 'md:block' : 'hidden group-hover:md:block'">
            Permissions
        </div>
        <hr>
        @can('create', Spatie\Permission\Models\Role::class)
            <x-dashboard.sidebar-link name="Roles" href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')" icon="{{ 'bx-tag-alt' }}">

            </x-dashboard.sidebar-link>
        @endcan
        @can('create', Spatie\Permission\Models\Permission::class)
            <x-dashboard.sidebar-link name="Permissions" href="{{ route('permissions.index') }}" :active="request()->routeIs('permissions.index')" icon="{{ 'bx-badge-check
                ' }}">

            </x-dashboard.sidebar-link>
        @endcan
    @endif
</ul>

