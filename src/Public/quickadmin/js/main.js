$(document).ready(function () {
    $('.datatable').dataTable({
        "iDisplayLength": 100,
        "aaSorting": []
    });
    $('.datepicker').datepicker({
        autoclose: true
    });
    $('.ckeditor').each(function () {
        CKEDITOR.replace($(this));
    })
});