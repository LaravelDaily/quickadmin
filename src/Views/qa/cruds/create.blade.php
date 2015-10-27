@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <h1>Create new CRUD menu item</h1>

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
        {!! Form::label('name', 'CRUD name', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=> 'Plural, ex. Books or Products (used to generate DB table and all back-end files)']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('title', 'CRUD title', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> 'Crud title (used for menu item)']) !!}
        </div>
    </div>


    <div class="form-group">
        {!! Form::label('soft', 'Use soft delete?', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('soft', [1 => 'Yes', 0 => 'No'], old('soft'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('icon', 'Icon (font-awesome)', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> 'Font awesome']) !!}
        </div>
    </div>

    <hr />

    <h3>Add fields</h3>

    <table class="table">
        <tbody id="generator">
        @if(old('f_type'))
            @foreach(old('f_type') as $index => $fieldName)
                @include('tpl::crud_field_line', ['index' => $index])
            @endforeach
        @else
            @include('tpl::crud_field_line', ['index' => ''])
        @endif
        </tbody>
    </table>

    <div class="form-group">
        <div class="col-md-12">
            <button type="button" id="addField" class="btn btn-success"><i class="fa fa-plus"></i> Add one more field</button>
        </div>
    </div>

    <hr />

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Create CRUD', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <div style="display: none;">
        <table>
            <tbody id="line">
            @include('tpl::crud_field_line', ['index' => ''])
            </tbody>
        </table>
    </div>

@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            // Add new row to the table of fields
            $('#addField').click(function () {
                var line = $('#line').html();
                var table = $('#generator');
                table.append(line);
            });
            // Remove row from the table of fields
            $(document).on('click', '.rem', function () {
                $(this).parent().parent().remove();
            });

            $(document).on('change', '.type', function () {
                var val = $(this).val();
                if(val == 'radio' || val == 'checkbox') {
                    $(this).parent().parent().find('.value').show();
                }else{
                    $(this).parent().parent().find('.value').hide();
                }
            });
        });
    </script>
@stop