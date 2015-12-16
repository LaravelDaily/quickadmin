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
        {!! Form::label('parent_id', 'CRUD parent', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('parent_id', $parentsSelect, old('parent_id'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('name', 'CRUD name', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=> 'ex. Books or Products (used to generate DB table and all back-end files)']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('title', 'CRUD title', ['class'=>'col-md-2 control-label']) !!}
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

    <hr/>

    <h3>Add fields</h3>

    <table class="table">
        <tbody id="generator">
        <tr>
            <td>Show in list</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if(old('f_type'))
            @foreach(old('f_type') as $index => $fieldName)
                @include('tpl::menu_field_line', ['index' => $index])
            @endforeach
        @else
            @include('tpl::menu_field_line', ['index' => ''])
        @endif
        </tbody>
    </table>

    <div class="form-group">
        <div class="col-md-12">
            <button type="button" id="addField" class="btn btn-success"><i class="fa fa-plus"></i> Add one more field
            </button>
        </div>
    </div>

    <hr/>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Create CRUD', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <div style="display: none;">
        <table>
            <tbody id="line">
            @include('tpl::menu_field_line', ['index' => ''])
            </tbody>
        </table>

        <!-- Select for relationship column-->
        @foreach($models as $key => $model)
            <select name="f_relationship_field[{{ $key }}]" class="form-control relationship-field rf-{{ $key }}">
                <option value="">Select display field</option>
                @foreach($model as $key2 => $option)
                    <option value="{{ $option }}"
                            @if($option == old('f_relationship_field.'.$key)) selected @endif>{{ $option }}</option>
                @endforeach
            </select>
            @endforeach
                    <!-- /Select for relationship column-->
    </div>

@endsection

@section('javascript')

    <script>
        function typeChange(e) {
            var val = $(e).val();
            // Hide all possible outputs
            $(e).parent().parent().find('.value').hide();
            $(e).parent().parent().find('.default_c').hide();
            $(e).parent().parent().find('.relationship').hide();
            $(e).parent().parent().find('.title').show().val('');
            $(e).parent().parent().find('.texteditor').hide();
            $(e).parent().parent().find('.size').hide();
            $(e).parent().parent().find('.dimensions').hide();
            $(e).parent().parent().find('.enum').hide();

            // Show a checbox which enables/disables showing in list
            $(e).parent().parent().parent().find('.show2').show();
            $(e).parent().parent().parent().find('.show_hid').val(1);
            switch (val) {
                case 'radio':
                    $(e).parent().parent().find('.value').show();
                    break;
                case 'checkbox':
                    $(e).parent().parent().find('.default_c').show();
                    break;
                case 'relationship':
                    $(e).parent().parent().find('.relationship').show();
                    $(e).parent().parent().find('.title').hide().val('-');
                    break;
                case 'textarea':
                    $(e).parent().parent().find('.show2').hide();
                    $(e).parent().parent().find('.show_hid').val(0);
                    $(e).parent().parent().find('.texteditor').show();
                    break;
                case 'file':
                    $(e).parent().parent().find('.size').show();
                    break;
                case 'enum':
                    $(e).parent().parent().find('.enum').show();
                    break;
                case 'photo':
                    $(e).parent().parent().find('.size').show();
                    $(e).parent().parent().find('.dimensions').show();
                    break;
            }
        }

        function relationshipChange(e) {
            var val = $(e).val();
            $(e).parent().parent().find('.relationship-field').remove();
            var select = $('.rf-' + val).clone();
            $(e).parent().parent().find('.relationship-holder').html(select);
        }

        $(document).ready(function () {
            $('.type').each(function () {
                typeChange($(this))
            });
            $('.relationship').each(function () {
                relationshipChange($(this))
            });

            $('.show2').change(function () {
                var checked = $(this).is(":checked");
                if (checked) {
                    $(this).parent().find('.show_hid').val(1);
                } else {
                    $(this).parent().find('.show_hid').val(0);
                }
            });

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
                typeChange($(this))
            });
            $(document).on('change', '.relationship', function () {
                relationshipChange($(this))
            });
        });

    </script>
@stop