<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

{{-- Sidebar brand logo --}}
    <div class="brand" style="text-align: center; padding: 20px 10px;">
        <img src="{{ asset('image/icons/smartservelogo.jpg') }}" style="width: 100%;">
    </div>

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>
    <style>

        .sidebar-mini.sidebar-collapse .main-sidebar .brand img {
            content: url('public/image/icons/pagadian_round.png');
        }

        .sidebar-mini.sidebar-collapse .main-sidebar:hover .brand img{
            content: url('public/image/icons/smartservelogo.jpg');
        }

        .sidebar nav li a i {
            width: 20px;
            text-align: center;
            margin-right: 9px;
        }

        .sidebar nav li a p {
            font-size: 15px;
        }

    </style>

</aside>
