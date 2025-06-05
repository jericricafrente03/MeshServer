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
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table row-border table-hover" style="width:100%">
                        <thead></thead>
                        <tbody>                                   
                        </tbody>
                        <tfooter></tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert2/script')
    @include('general/body/guests/roomAssignments/modals/add/form')
    @include('general/body/guests/roomAssignments/modals/delete/form')
    @include('general/body/guests/roomAssignments/modals/changeRoom/form')
    @include('general/body/guests/roomAssignments/modals/billing/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
    let dt_tableBilling;
    let rows_selected = []; 

    $('#add_modal').on('hidden.bs.modal', function(){
        
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
        
        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();    
        // Properly reset Select2 dropdowns
        $(this).find('select').each(function() {
            $(this).val(null).trigger('change');  // Clear Select2 selections
        });
    });

    $('#billing_modal').on('hidden.bs.modal', function(){
        
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
        
        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();  
        $('#billing_modal #table').DataTable().clear().destroy(); 
    });

    $(document).ready(function() {     

        inFooterScriptAjaxDataTable({
            tableId: '#table', 
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('room_assignments.create')
                    { text: '<i class="fa-solid fa-user-plus"></i> Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/room-assignments/data',  
            type: 'GET',
            data: function(data) {
                // Add multiple parameters
                data.search = $('.dt-card-header #search_input').val();
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', width: '1%',
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'room', name: 'room', title: 'Room', width: '5%',
                    render: function(data, type, row) {
                        return `${row.room}`;
                    } 
                },
                { data: 'guest', name: 'guest', title: 'Guest', width: '20%',
                    render: function(data, type, row) {
                        return `${row.guest}`;
                    } 
                },
                { data: 'check_in', name: 'check_in', title: 'Check In', width: '10%'},
                { data: 's_check_out', name: 's_check_out', title: 'Scheduled Check Out' , width: '5%'},
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '10%'},
            ]
        });

        if (dt_table) {
            // Placement controls for Table filters and buttons
            dt_table.buttons().container().appendTo('.dt-card-header'); 
            $('.dt-card-header #search_input').val(dt_table.search());
            $('.dt-card-header #search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
            $('.dt-card-header #length_change').change( function() { dt_table.page.len($(this).val()).draw() });
        }
        else {
            console.error('DataTable failed to initialize.');
        }
            
    });

    function showAddModal(){
        let modal = '#add_modal';
        let roomId = '#room_id';
        let roomCategoryId = '#room_category';
        let customerId = '#customer_id';

        inFooterScriptDropDownBelowSelect2(modal, customerId);
        inFooterScriptDropDownBelowSelect2(modal, roomCategoryId);
        inFooterScriptDropDownBelowSelect2(modal, roomId);
        inFooterScriptDateTimePicker(modal, "#s_check_out");

        let roomCategoryData = {
            id: '',
            // category: 'room_status',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadRoomCategoryDropdownResult, "/search/room-categories", roomCategoryData);
        inFooterScriptSearchSelect2(customerId, modal, "search/guests")
        roomDropDownReload(modal, '');

        $(modal).modal('show');
    }

    function showCheckOutForm(id){
        let data = {
            id: id
        };

        let swalData = {
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor:  "#15a0a3 ",
            cancelButtonColor:  "#fd3550",
            confirmButtonText: "Yes!",
            position: "top"
        };

        sweetAlert2Confirmation(swalData, inFooterScriptAjaxCreateUpdateNoModalSubmit, data, false, '/room-assignments/check-out', 'PUT');
    }

    function showChangeRoomForm(id, data){
        let data_id = id;
        let modal = '#change_room_modal';
        let roomId = '#room_id';
        let roomCategoryId = '#room_category';
        let arr = data;

        $(modal+ ' #id').val(data_id);
        $(modal+ ' #current_room_id').val(arr.room_id);
        // console.log(arr);
        inFooterScriptDropDownBelowSelect2(modal, roomCategoryId);
        inFooterScriptDropDownBelowSelect2(modal, roomId);
       
        roomDropDownReload(modal, '', arr.room_id);
        let roomCategoryData = {
            id: '',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadRoomCategoryDropdownResult, "/search/room-categories", roomCategoryData);
        
        $(modal).modal('show');
        $(modal).on('hidden.bs.modal', function(){
          // Remove focus from the currently focused element inside modal
          $(document.activeElement).blur();
        });
    }

    function showBillingModal(id){
        let modal = '#billing_modal';

        table = $(modal+ ' #table').DataTable({
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[1, 'desc']],
            buttons: [],
            pageLength: 10,
            searching: true,
            autoWidth: false,
            ajax: {
                url: '/room-assignments/billing-data',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(data) {
                    // Add multiple parameters
                    data.search = $('#search_input_billing').val();
                    data.room_assignment_id = id;
                }
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
             
                { data: 'category', name: 'category', title: 'Category' , width: '1%'},
                { data: 'item_name', name: 'item_name', title: 'Item' , width: '20%'},
                { data: 'quantity', name: 'quantity', title: 'Quantity' , width: '1%'},
                
                { data: 'status', name: 'status', title: 'Status', orderable: false, searchable: false, width: '1%'},
                { data: 'payment_status', name: 'payment_status', title: 'Payment', orderable: false, searchable: false, width: '1%'},
                { data: 'unit_price', name: 'unit_price', title: 'Price', width: '5%', class: 'text-end',
                    render: function(data, type, row) {
                        return `₱ `+parseFloat(row.unit_price).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    } 
                },
            ],
            initComplete: function(settings, json) {
                let selected_len = table.page.len();
                const length_change = $(modal+' #length_change_billing');
                length_change.empty(); // Clear existing options
                length_change.append($('<option>', {value: 5,text: 'Show 5', selected:(selected_len == "5") ? true : false}));
                length_change.append($('<option>', {value: 10,text: 'Show 10',selected:(selected_len == "10") ? true : false}));
                length_change.append($('<option>', {value: 50,text: 'Show 50',selected:(selected_len == "50") ? true : false}));
                length_change.append($('<option>', {value: 100,text: 'Show 100',selected:(selected_len == "100") ? true : false}));
                length_change.append($('<option>', {value: 1000,text: 'Show 1000',selected:(selected_len == "1000") ? true : false}));
            },
            rowCallback: function(row, data, dataIndex){
                let rowId = data.id;
                // this is use for checkbox[selecting]
                if (typeof rows_selected !== 'undefined' && Array.isArray(rows_selected)) {
                    if($.inArray(rowId, rows_selected) !== -1){
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                        $(row).addClass('selected');
                    }
                }
            },
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
        
                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // console.log(api.rows().data());  
                
                // Total over all pages, excluding rows with status 'cancelled' or 'paid'
                let total = api
                    .rows()
                    .data()
                    .filter(function (row) {
                        return !(row.is_paid == 1 || row.status_id == 15);
                    })
                    .reduce((a, b) => intVal(a) + intVal(b.unit_price), 0);
                
                // Total over this page, excluding rows with status 'cancelled' or 'paid'
                let pageTotal = api
                    .rows({ page: 'current' })
                    .data()
                    .filter(function (row) {
                        return !(row.is_paid == 1 || row.status_id == 15);
                    })
                    .reduce((a, b) => intVal(a) + intVal(b.unit_price), 0);
                
                let formatTotal = `₱ `+parseFloat(total).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                    
                // Update footer with the page total and all-pages total
                $(api.column(7).footer()).html(
                    // 'PHP' + pageTotal.toFixed(2) + ' ( PHP' + total.toFixed(2) + ' total)' //with total of this page only
                    '<span style="font-size: 14px;">'+formatTotal+'</span>'
                );
                
                if(total == 0){
                    $('#billing_modal #modal_checkout').show();
                }
                else{
                    $('#billing_modal #modal_checkout').hide();
                }
                    
            },
        });

        dt_tableBilling = table;

        // Handle click on checkbox
        $('#table tbody').on('click', 'input[type="checkbox"].row-checkbox', function(e){
            var $row = $(this).closest('tr');
            
            // Get row data
            var data = dt_tableBilling.row($row).data();

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
            updateDataTableSelectAllCtrl(dt_tableBilling);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', dt_tableBilling.table().container()).on('click', function(e){
            // console.log(this.checked);
            if(this.checked){
                $('#table tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#table tbody input[type="checkbox"]:checked').trigger('click');
            }
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        dt_tableBilling.on('draw', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(dt_tableBilling);
        });

        // Placement controls for Table filters and buttons
        if (dt_tableBilling) {
            dt_tableBilling.buttons().container().appendTo( modal+ ' .dt-card-header-billing' ); 
            $('#search_input_billing').val(dt_tableBilling.search());
            $('#search_input_billing').keyup(function(){ dt_tableBilling.search($(this).val()).draw() ; })
            $('#length_change_billing').change( function() { dt_tableBilling.page.len($(this).val()).draw() });
        }
        else {
            console.error('DataTable failed to initialize.');
        }
        $(modal).modal('show');
    }

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

    function reloadBillingDataTable(data, modal, reform=false){
        dt_tableBilling.ajax.reload(null, false);
        dt_table.ajax.reload(null, false);
        sweetAlert2(data.status, data.message);    
    }

    $(document).on('change', '#add_modal #room_category', function() {
        let roomCategoryId = $(this).val();
        let modal = '#add_modal';        

        roomDropDownReload(modal, roomCategoryId);
    });

    $(document).on('change', '#change_room_modal #room_category', function() {
        let roomCategoryId = $(this).val();
        let modal = '#change_room_modal';    
        let id = $(modal+ ' #current_room_id').val();    
        
        roomDropDownReload(modal, roomCategoryId, id);
    });

    function roomDropDownReload(modal, roomCategoryId, id){
        let roomData = {
            id: (id==null)?'':id,
            room_category_id: (roomCategoryId==0)?null:roomCategoryId,
            modal: modal,
            
        };
        inFooterScriptAjaxBasicGet(loadRoomDropdownResult, "/search/available-rooms", roomData);
    }

    function loadRoomCategoryDropdownResult(result, data){
        let len = result.length;
        
        $(data.modal+" #room_category").empty();
        $(data.modal+" #room_category").append(`<option></option>`);
        $(data.modal+" #room_category").append(`<option value="0">--Show All Room--</option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
           
            if(data.id==id){$(data.modal+" #room_category").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #room_category").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    function loadRoomDropdownResult(result, data){
        let len = result.length;
        
        $(data.modal+" #room_id").empty();
        $(data.modal+" #room_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
            
            if(data.id==id){$(data.modal+" #room_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #room_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    $('.mass-status-dropdown').on('click', function(event) {
        event.preventDefault();
        // alert($(this).data('value')+' = '+rows_selected);
        let data = {
            status: $(this).data('value'),
            status_id: $(this).data('id'),
            ids: rows_selected,
            type: 'mass'
        };
        inFooterScriptAjaxCreateUpdateWithSpecificDataTableSubmit(data, false, "#billing_modal", "/guest-billings/edit", 'PUT', null, reloadBillingDataTable);
    });

    $(document).on('click', '.single-status-dropdown', function(event) {
        event.preventDefault();

        let data = {
            status: $(this).data('value'),
            status_id: $(this).data('status_id'),
            id: $(this).data('id'),
            type: 'single'
        };

        inFooterScriptAjaxCreateUpdateWithSpecificDataTableSubmit(data, false, "#billing_modal", "/guest-billings/edit", 'PUT', null, reloadBillingDataTable);
    });

    $('.mass-payment-dropdown').on('click', function(event) {
        event.preventDefault();
        // alert($(this).data('value')+' = '+rows_selected);
        let data = {
            is_paid: $(this).data('value'),
            ids: rows_selected,
            type: 'mass'
        };
        inFooterScriptAjaxCreateUpdateWithSpecificDataTableSubmit(data, false, "#billing_modal", "/guest-billings/edit-payment", 'PUT', null, reloadBillingDataTable);
    });

    $(document).on('click', '.single-payment-dropdown', function(event) {
        event.preventDefault();

        let data = {
            is_paid: $(this).data('value'),
            id: $(this).data('id'),
            type: 'single'
        };

        inFooterScriptAjaxCreateUpdateWithSpecificDataTableSubmit(data, false, "#billing_modal", "/guest-billings/edit-payment", 'PUT', null, reloadBillingDataTable);
    });

    // Handle dropdown item selection
    // $(document).on('click', '.dt-modal-dropdown-item', function() {
    //     const statusName = $(this).data('value');
    //     const button = $(this).closest('.dt-modal-dropdown').find('.dt-modal-dropdown-btn');
    //     // button.text(statusName);
        
    //     // alert(statusName);
    //     // Close the dropdown after selection
    //     $(this).closest('.dt-modal-dropdown').removeClass('active');
    // });


</script>


@stop