@extends('admin.layouts.master')

@section('content')

    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">List</div>
        </div>
        <div class="portlet-body">
            <table class="table table-striped table-hover table-responsive" id="ajaxtable">
                <thead>
                <th>User</th>
                <th>Action</th>
                <th>Action model</th>
                <th>Action id</th>
                <th>Time</th>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $('#ajaxtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('actions.ajax') }}',
            columns: [
                {data: 'users.name', name: 'user_id'},
                {data: 'action', name: 'action'},
                {data: 'action_model', name: 'action_model'},
                {data: 'action_id', name: 'action_id'},
                {data: 'created_at', name: 'created_at'}
            ]
        });
    </script>
@stop