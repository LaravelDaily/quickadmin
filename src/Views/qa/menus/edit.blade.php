@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <h1>Edit menu information</h1>

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

    @if($menu->menu_type != 2)
        <div class="form-group">
            {!! Form::label('parent_id', 'Parent', ['class'=>'col-md-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::select('parent_id', $parentsSelect, old('parent_id', $menu->parent_id), ['class'=>'form-control']) !!}
            </div>
        </div>
    @endif

    <div class="form-group">
        {!! Form::label('title', 'Title', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('title', old('title',$menu->title), ['class'=>'form-control', 'placeholder'=> 'Menu title (used for menu item)']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('roles', 'Roles', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            @foreach($roles as $role)
                <div class="col-xs-12">  {!! Form::hidden('role-'.$role->id,0) !!}
                    {!! Form::checkbox('role-'.$role->id,1,in_array($role->id, explode(',',$menu->roles))) !!}
                    {!! $role->title !!}</div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('icon', 'Icon (font-awesome)', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('icon', old('icon',$menu->icon), ['class'=>'form-control', 'placeholder'=> 'Font awesome']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection