@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>{{ trans('quickadmin::qa.menus-createParent-create_new_parent') }}</h1>

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
        {!! Form::label('title', trans('quickadmin::qa.menus-createParent-parent_title'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::qa.menus-createParent-parent_title_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('roles', trans('quickadmin::qa.menus-createParent-roles') , ['class'=>'col-sm-2 control-label']) !!}
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
        {!! Form::label('icon', trans('quickadmin::qa.menus-createParent-icon') , ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::qa.menus-createParent-icon_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit( trans('quickadmin::qa.menus-createParent-create_parent') , ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection