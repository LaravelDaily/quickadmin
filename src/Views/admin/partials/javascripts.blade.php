<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="{{ url('quickadmin/js') }}/timepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
<script src="//cdn.ckeditor.com/4.5.4/full/ckeditor.js"></script>
<script src="{{ url('quickadmin/js') }}/bootstrap.min.js"></script>
<script src="{{ url('quickadmin/js') }}/main.js"></script>
<script src="{{ url('quickadmin/js') }}/sweetalert.min.js"></script>

<script>

    $('.datepicker').datepicker({
        autoclose: true,
        dateFormat: "{{ config('quickadmin.date_format_jquery') }}"
    });

    $('.datetimepicker').datetimepicker({
        autoclose: true,
        dateFormat: "{{ config('quickadmin.date_format_jquery') }}",
        timeFormat: "{{ config('quickadmin.time_format_jquery') }}"
    });

    $('#datatable').dataTable( {
        "language": {
            "url": "{{ trans('quickadmin::strings.datatable_url_language') }}"
        }
    });

    function handleDelete(e, stop){
      if(stop){
        e.preventDefault();
        swal({
          title: "{!! trans("quickadmin::strings.delete_are_you_sure") !!}",
          text: "{!! trans("quickadmin::strings.delete_not_able_recover") !!}",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "{!! trans("quickadmin::strings.delete_confirm_button") !!}",
          cancelButtonText: "{!! trans("quickadmin::strings.delete_cancel_button") !!}",
          closeOnConfirm: false
        },
        function (isConfirm) {
          if (isConfirm) {
            $('.confirm-delete').trigger('click', {});
          }
        });
      }
    };

    $(document).ready(function () {
      $('.confirm-delete').on('click',function(e, data){
        if(!data){
          handleDelete(e, 1);
        }
      });
    });

</script>

