<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            @if(Auth::user()->role_id == 1)
                <li @if(Request::path() == 'crud') class="active" @endif>
                    <a href="{{ url(config('quickadmin.route').'/crud') }}">
                        <i class="fa fa-plus"></i>
                        <span class="title">Crud</span>
                    </a>
                </li>
                <li @if(Request::path() == 'users') class="active" @endif>
                    <a href="{{ url('users') }}">
                        <i class="fa fa-users"></i>
                        <span class="title">Users</span>
                    </a>
                </li>
            @endif
            @foreach($cruds as $crud)
                @if(in_array(Auth::user()->role_id, explode(',',$crud->roles)))
                    <li @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($crud->name)) class="active" @endif>
                        <a href="{{ route('admin.'.strtolower($crud->name).'.index') }}">
                            <i class="fa {{ $crud->icon }}"></i>
                            <span class="title">{{ $crud->title }}</span>
                        </a>
                    </li>
                @endif
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