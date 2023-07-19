$(document).ready(function() {
    $('#example').DataTable( {
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
    } );
} );

//check box, check all
$(document).ready(function() {
    // "Check All" checkbox functionality
    $("#headerCheckbox").change(function() {
        $(".bodyCheckbox").prop('checked', $(this).prop('checked'));
    });

    // Individual checkbox functionality
    $(".bodyCheckbox").change(function() {
        if (!$(this).prop('checked')) {
            $("#headerCheckbox").prop('checked', false);
        }
    });
});

  