$(document).ready(function () {
    $('.datatable').dataTable({
        "iDisplayLength": 100,
        "aaSorting": []
    });
    $('.ckeditor').each(function () {
        CKEDITOR.replace($(this));
    })
});