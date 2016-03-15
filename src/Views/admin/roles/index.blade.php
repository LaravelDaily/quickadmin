@extends('admin.layouts.master')

@section('content')

    <p>{!! link_to_route('roles.create', trans('quickadmin::admin.roles-index-add_new'), [], ['class' => 'btn btn-success']) !!}</p>

    @if($roles->count() > 0)
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">{{ trans('quickadmin::admin.roles-index-roles_list') }}</div>
            </div>
            <div class="portlet-body">
                <table id="datatable" class="table table-striped table-hover table-responsive datatable">
                    <thead>
                        <tr>
                            <th>{{ trans('quickadmin::admin.roles-index-title') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->title }}</td>
                                <td>
                                    {!! link_to_route('roles.edit', trans('quickadmin::admin.roles-index-edit'), [$role->id], ['class' => 'btn btn-xs btn-info']) !!}
                                    {!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'' . trans('quickadmin::admin.roles-index-are_you_sure') . '\');',  'route' => ['roles.destroy', $role->id]]) !!}
                                    {!! Form::submit(trans('quickadmin::admin.roles-index-delete'), ['class' => 'btn btn-xs btn-danger']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{ trans('quickadmin::admin.roles-index-no_entries_found') }}
    @endif

@endsection

