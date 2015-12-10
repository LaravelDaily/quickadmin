$(document).ready(function () {
    $('.datatable').dataTable({
        "iDisplayLength": 100,
        "aaSorting": [],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [0]}
        ],
    });
    $('.ckeditor').each(function () {
        CKEDITOR.replace($(this));
    })
});