<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            <li>
                <a href="{{ url('crud') }}">
                    <i class="fa fa-plus"></i>
                    <span class="title">Crud</span>
                </a>
            </li>
            <li>
                <a href="{{ url('users') }}">
                    <i class="fa fa-users"></i>
                    <span class="title">Users</span>
                </a>
            </li>
            @foreach($cruds as $crud)
                <li>
                    <a href="{{ route('admin.'.strtolower($crud->name).'.index') }}">
                        <i class="fa {{ $crud->icon }}"></i>
                        <span class="title">{{ $crud->title }}</span>
                    </a>
                </li>
            @endforeach
            <li>
                <a href="{{ url('auth/logout') }}">
                    <i class="fa fa-sign-out fa-fw"></i>
                    <span class="title">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>