$('#coinTable').DataTable({
    paging: false,
    stateSave: true,
    info: false,

    responsive: true
});

$('#coinTableAdmin').DataTable({
    paging: false,
    stateSave: true,
    info: false,

    responsive: true
});

$('.table-paged.table-paged--sortable').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    responsive: true,

    order: [[ 0, "desc" ]],

    language: {
        paginate: {
            previous: "‹",
            next: "›"
        }
    }
});

