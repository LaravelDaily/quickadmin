<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            @if(Auth::user()->role_id == config('quickadmin.defaultRole'))
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
                <li @if(Request::path() == 'actions') class="active" @endif>
                    <a href="{{ url(config('quickadmin.route').'/actions') }}">
                        <i class="fa fa-users"></i>
                        <span class="title">User actions</span>
                    </a>
                </li>
            @endif
            @foreach($cruds as $crud)
                @if(is_null($crud->children()->first()) && is_null($crud->parent_id))
                    @if(in_array(Auth::user()->role_id, explode(',',$crud->roles)))
                        <li @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($crud->name)) class="active" @endif>
                            <a href="{{ route(config('quickadmin.route').'.'.strtolower($crud->name).'.index') }}">
                                <i class="fa {{ $crud->icon }}"></i>
                                <span class="title">{{ $crud->title }}</span>
                            </a>
                        </li>
                    @endif
                @else
                    @if(in_array(Auth::user()->role_id, explode(',',$crud->roles)) && is_null($crud->parent_id))
                        <li>
                            <a href="javascript:;">
                                <i class="fa {{ $crud->icon }}"></i>
                                <span class="title">{{ $crud->title }}</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @foreach($crud['children'] as $child)
                                    @if(in_array(Auth::user()->role_id, explode(',',$child->roles)))
                                        <li
                                                @if(isset(explode('/',Request::path())[1]) && explode('/',Request::path())[1] == strtolower($child->name)) class="active active-sub" @endif>
                                            <a href="{{ route(config('quickadmin.route').'.'.strtolower($child->name).'.index') }}">
                                                <i class="fa {{ $child->icon }}"></i>
                                        <span class="title">
                                            {{ $child->title  }}
                                        </span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
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