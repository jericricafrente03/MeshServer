@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->

        <div class="card">
            <div class="card-header dt-card-header">
                <select name='length_change' id='length_change' class="table_length_change form-select">
                </select>
                <input type="text" id="search_input" class="table_search_input form-control" placeholder="Search...">
                
                <!-- <div class="dropdown ms-2" id="statusAllDropdown"> -->
                <button class="btn btn-primary dropdown-toggle ms-2" id="statusAllDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">Status</button>
                <ul class="dropdown-menu">
                    @foreach ($statusDropdown as $status)
                        <li><a class="dropdown-item mass-status-dropdown" data-value="{{$status->name}}" data-id="{{$status->id}}" href="javascript:void(0)"  class="me-1"><i class="fa-solid fa-caret-right" style="color: {{$status->bg_color}};"></i> {{$status->name}}</a></li>
                    @endforeach
                </ul>
                <!-- </div> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table row-border table-hover" style="width:100%">
                        <thead>
                        </thead>
                        <tbody>                                   
                        </tbody>
                        <tfooter></tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert2/script')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
    let rows_selected = []; 

    $(document).ready(function() {   
        const initialSearch = inFooterScriptGetUrlParam('search');

        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[1, 'desc']],
            buttons: [
            ],
            pageLength: 10,
            searching: true,
            url: '/guest-billings/data',  
            type: 'GET',
            data: function(data) {
                data.search = $('input[type="search"]').val();
            },
            columns: [
                {data:"checkbox", searchable: false, orderable: false, width: '1%', className: 'dt-body-center',
                    title: '<input name="select_all" id="select_all" value="1" type="checkbox">',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox" class="row-checkbox">';
                    }
                },
                { data: 'id', name: 'id', title: 'ID', width: '1%',
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'transaction_datetime', name: 'transaction_datetime', title: 'Date/Time', width: '5%'},
                { data: 'guest_name', name: 'guest_name', title: 'Guest' , width: '5%',
                    render: function(data,type, row) {
                        return `${row.guest_name}`
                    }
                },
                // { data: 'room_number', name: 'room_number', title: 'Room' , width: '1%'},
                { data: 'room_number', name: 'room_number', title: 'Room', width: '1%',
                    render: function(data, type, row) {
                        return `🛏️${row.room_number}`;
                    } 
                },
                { data: 'category', name: 'category', title: 'Category' , width: '1%'},
                { data: 'item_name', name: 'item_name', title: 'Item' , width: '5%'},
                { data: 'quantity', name: 'quantity', title: 'Quantity' , width: '1%'},
                // { data: 'unit_price', name: 'unit_price', title: 'Price' , width: '5%'},
                { data: 'unit_price', name: 'unit_price', title: 'Price', width: '5%',
                    render: function(data, type, row) {
                        return `₱ `+parseFloat(row.unit_price).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    } 
                },
                { data: 'user', name: 'user', title: 'User' , width: '5%'},

                { data: 'status', name: 'status', title: 'Status', orderable: false, searchable: false, width: '10%'},
            ],
           
        });

        // Handle click on checkbox
        $('#table tbody').on('click', 'input[type="checkbox"].row-checkbox', function(e){
            var $row = $(this).closest('tr');
            
            // Get row data
            var data = dt_table.row($row).data();

            // Get row ID
            //var rowId = data[0];
            var rowId = Object.entries(data)[1][1];
                
            // Determine whether row ID is in the list of selected row IDs 
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if(this.checked && index === -1){
                rows_selected.push(rowId);

            // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1){
                rows_selected.splice(index, 1);
            }

            if(this.checked){
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(dt_table);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', dt_table.table().container()).on('click', function(e){
            console.log(this.checked);
            if(this.checked){
                $('#table tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#table tbody input[type="checkbox"]:checked').trigger('click');
            }
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        dt_table.on('draw', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(dt_table);
        });

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
        if (initialSearch) {
            // readNotification(notificationId);
            $('#search_input').val(initialSearch);
            dt_table.search(initialSearch).draw();
        }
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    
    // Updates "Select all" control in a data table
    function updateDataTableSelectAllCtrl(table){
        let $table = $(table.table().node());
        
        let $chkbox_all = $table.find('tbody input[type="checkbox"]');
        let $chkbox_checked = $table.find('tbody input[type="checkbox"]:checked');
        let chkbox_select_all = $('thead input[name="select_all"]#select_all');

         // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.prop('checked', false);
            chkbox_select_all.prop('indeterminate', false);

        // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.prop('checked', true);
            chkbox_select_all.prop('indeterminate', false);

        // If some of the checkboxes are checked
        } else {
           chkbox_select_all.prop('checked', true); 
           chkbox_select_all.prop('indeterminate', true);
        }
    }

    $('.mass-status-dropdown').on('click', function(event) {
        event.preventDefault();
        // alert(rows_selected);
        let data = {
            status: $(this).data('value'),
            status_id: $(this).data('id'),
            ids: rows_selected,
            type: 'mass'
        };
        inFooterScriptAjaxCreateUpdateNoModalSubmit(data, false, "/guest-billings/edit", 'PUT');
    });

    $(document).on('click', '.single-status-dropdown', function(event) {
        event.preventDefault();

        let data = {
            status: $(this).data('value'),
            status_id: $(this).data('status_id'),
            id: $(this).data('id'),
            type: 'single'
        };

        inFooterScriptAjaxCreateUpdateNoModalSubmit(data, false, "/guest-billings/edit", 'PUT');
    });


    function reloadDataTable(data, modal, reform=false){
        dt_table.ajax.reload(null, false);
        sweetAlert2(data.status, data.message);
        if(reform){
            $('.error_txt').remove();
            $('#add_modal')
            .find("input,textarea,select")
            .val('')
            .end();
            // Properly reset Select2 dropdowns
            $('#add_modal').find('select').each(function() {
                $(this).val(null).trigger('change');  // Clear Select2 selections
            });
        }
        else{
            $(modal).modal('hide');
        }
        
    }

</script>


@stop