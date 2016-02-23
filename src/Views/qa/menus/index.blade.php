@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('
                        <li class="error">:message</li>
                        ')) !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>


    @if($menusList->count() == 0)
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <div class="alert alert-info">
                    {{ trans('quickadmin::qa.menus-index-no_menu_items_found') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12 form-group">
            <a href="{{ route('menu.crud') }}" class="btn btn-primary">{{ trans('quickadmin::qa.menus-index-new_crud') }}</a>
            <a href="{{ route('menu.custom') }}" class="btn btn-primary">{{ trans('quickadmin::qa.menus-index-new_custom') }}</a>
            <a href="{{ route('menu.parent') }}" class="btn btn-primary">{{ trans('quickadmin::qa.menus-index-new_parent') }}</a>
        </div>
    </div>

    {!! Form::open(['class' => 'form-horizontal']) !!}

    @if($menusList->count() != 0)
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <div class="alert alert-danger">
                    {{ trans('quickadmin::qa.menus-index-positions_drag_drop') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-6 col-md-4">
            <ul id="sortable" class="list-unstyled">
                @foreach($menusList as $menu)
                    @if($menu->children()->first() == null)
                        <li data-menu-id="{{ $menu->id }}">
                            <span>
                                {{ $menu->title }} {{ $menu->parent_id }}
                                <a href="{{ route('menu.edit',[$menu->id]) }}"
                                   class="btn btn-xs btn-default pull-right">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </span>
                            <input type="hidden" class="menu-no" value="{{ $menu->position }}"
                                   name="menu-{{ $menu->id }}">
                            @if($menu->menu_type == 2)
                                <ul class="childs" style="min-height: 10px;"></ul>
                            @endif
                        </li>
                    @else
                        <li data-menu-id="{{ $menu->id }}">
                            <span>
                                {{ $menu->title }}
                                <a href="{{ route('menu.edit',[$menu->id]) }}"
                                   class="btn btn-xs btn-default pull-right">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </span>
                            <input type="hidden" class="menu-no" value="{{ $menu->position }}"
                                   name="menu-{{ $menu->id }}">
                            <ul class="childs list-unstyled" style="min-height: 10px;">
                                @foreach($menu->children as $child)
                                    <li>
                                        <span>
                                            {{ $child->title }}
                                            <a href="{{ route('menu.edit',[$child->id]) }}"
                                               class="btn btn-xs btn-default pull-right">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </span>
                                        <input type="hidden" class="child-no" value="{{ $child->position }}"
                                               name="child-{{ $child->id }}">
                                        <input type="hidden" class="menu-id" value="{{ $menu->id }}"
                                               name="child-parent-{{ $child->id }}">
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    @if($menusList->count() != 0)

        <div class="row" id="dragMessage" style="display: none;">
            <div class="col-xs-6 col-md-4">
                <div class="alert alert-danger">
                    {{ trans('quickadmin::qa.menus-index-click_save_positions') }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                {!! Form::submit(trans('quickadmin::qa.menus-index-save_positions'),['class' => 'btn btn-danger']) !!}
            </div>
        </div>
    @endif

    {!! Form::close() !!}

@stop

@section('javascript')
    <script>
        $(function () {
            $("#sortable").sortable({
                placeholder: "ui-state-highlight",
                update: function () {
                    $('#dragMessage').show();
                    var i = 1;
                    $('#sortable').find('> li').each(function () {
                        $(this).attr('data-menu-no', i);
                        var no = $(this).attr('data-menu-no');
                        $(this).find('.menu-no').val(no);
                        i++;
                    });
                }

            });
            $("#sortable").disableSelection();
            $(".childs").sortable({
                placeholder: "ui-state-highlight",
                connectWith: ".childs",
                dropOnEmpty: true,
                update: function () {
                    $('#dragMessage').show();
                    $('#sortable').find('> li').each(function () {
                        var i = 1;
                        $('> ul > li', this).each(function () {
                            var no = $(this).parent().parent().attr('data-menu-id');
                            $(this).find('.menu-id').val(no);
                            $(this).find('.child-no').val(i);
                            i++;
                            console.log('ok');
                        });
                    });
                }
            });
        });
    </script>
@stop