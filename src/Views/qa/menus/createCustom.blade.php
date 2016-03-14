@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>{{ trans('quickadmin::qa.menus-createCustom-create_new_custom_controller') }}</h1>

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

    {!! Form::open(['class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('parent_id', trans('quickadmin::qa.menus-createCustom-menu_parent') , ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('parent_id', $parentsSelect, old('parent_id'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('name', trans('quickadmin::qa.menus-createCustom-controller_name'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::qa.menus-createCustom-controller_name_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('title', trans('quickadmin::qa.menus-createCustom-menu_title'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::qa.menus-createCustom-menu_title_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('roles', trans('quickadmin::qa.menus-createCustom-roles'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            @foreach($roles as $role)
                <div>
                    <label>
                        {!! Form::checkbox('roles['.$role->id.']',$role->id,old('roles.'.$role->id)) !!}
                        {!! $role->title !!}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('icon', trans('quickadmin::qa.menus-createCustom-icon'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::qa.menus-createCustom-icon_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {!! Form::submit(trans('quickadmin::qa.menus-createCustom-create_controller'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection