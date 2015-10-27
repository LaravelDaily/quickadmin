@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-11 col-md-offset-1">
            <h1>Create new CRUD</h1>

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
        {!! Form::label('name', 'Crud name', ['class'=>'col-md-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=> 'Plural']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('title', 'Crud title', ['class'=>'col-md-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=> 'Crud title']) !!}
        </div>
    </div>


    <div class="form-group">
        {!! Form::label('soft', 'Use soft delete?', ['class'=>'col-md-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::select('soft', [1 => 'Yes', 0 => 'No'], old('soft'), ['class' => 'form-control',]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('icon', 'Icon?', ['class'=>'col-md-1 control-label']) !!}
        <div class="col-sm-11">
            {!! Form::text('icon', old('icon','fa-database'), ['class'=>'form-control', 'placeholder'=> 'Font awesome']) !!}
        </div>
    </div>


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
        <div class="col-md-11 col-md-offset-1">
            <button type="button" id="addField" class="btn btn-success"><i class="fa fa-plus"></i></button>
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-1 control-label">&nbsp;</label>

        <div class="col-sm-11">
            {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
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