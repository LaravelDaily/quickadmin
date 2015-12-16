@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <h1>Create new custom controller</h1>

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
        {!! Form::label('parent_id', 'Menu parent', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('parent_id', $parentsSelect, old('parent_id'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('name', 'Controller name', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=> 'ex. Books or Products (used to generate DB table and all back-end files)']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('title', 'Menu title', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> 'Menu title (used for menu item)']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('roles', 'Roles', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            @foreach($roles as $role)
                <div class="col-xs-12">  {!! Form::hidden('role-'.$role->id,0) !!}
                    {!! Form::checkbox('role-'.$role->id,1,false) !!}
                    {!! $role->title !!}</div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('icon', 'Icon (font-awesome)', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> 'Font awesome']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Create controller', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection