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
    @include('general/body/messages/broadcastMessages/tickers/modals/add/form')
    @include('general/body/messages/broadcastMessages/tickers/modals/view/form')
    @include('general/body/messages/broadcastMessages/tickers/modals/delete/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
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
        $('#add_modal #message').summernote('code', ''); //Clear Summernote Description
    });

    $(document).ready(function() {    
        $('textarea').summernote({
            disableDragAndDrop: true,
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],    
                ['para', ['ul']],//, 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview']]//, 'help']]
            ]
        });

        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[1, 'desc']],
            buttons: [
                @can('tickers.create')
                    { text: '+ Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
                @can('tickers.delete_selected')
                    { text: 'Delete Selected', className: 'btn btn-danger delete-selected-btn', action: function ( e, dt, node, config ) {
                        deleteSelected();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/tickers/data',  
            type: 'GET',
            data: function(data) {
                // Add multiple parameters
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
                // { data: 'from', name: 'from', title: 'From', width: '1%', orderable: true, searchable: true},
                
                { data: 'message', name: 'message', title: 'Message', width: '20%', orderable: true, searchable: true,
                    render: function(data, type, row) {
                        return `${row.message}`;
                    } 
                },
                { data: 'duration', name: 'duration', title: 'Duration', width: '1%', orderable: true, searchable: true},
                { data: 'type', name: 'type', title: 'Type', width: '1%', orderable: true, searchable: true},
                { data: 'device_group', name: 'device_group', title: 'Group', width: '1%', orderable: true, searchable: true},
                { data: 'date', name: 'date', title: 'Date', width: '5%', orderable: true, searchable: false},
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '10%'},
            ]
        });

        $('.delete-selected-btn').hide(); 

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

            // hide/show Delete Selected Button
            if (rows_selected.length > 0) {
                $('.delete-selected-btn').show();
            } else {
                $('.delete-selected-btn').hide();
            }
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

    function deleteSelected(){
        // alert(rows_selected);
        let data = {
            ids: rows_selected,
            type: 'mass'
        };

        // Call the global SweetAlert confirmation function
        inFooterScriptSweetAlertDialogConfirmation(
            "Are you sure?",                // Custom title
            "Do you want to delete this ticker(s)?", // Custom text
            "Yes, delete it!",              // Custom confirm button text
            function () {                   // Callback function
                // This will run only when the user confirms
                inFooterScriptAjaxCreateUpdateNoModalSubmit(data, false, "/tickers/delete", 'DELETE');
                rows_selected.length = 0;
                $('.delete-selected-btn').hide();

                // Optionally, show a success message after the action
                Swal.fire({
                    title: "Resent!",
                    text: "The ticker has been deleted successfully.",
                    icon: "success"
                });
            }
        );
        
        // inFooterScriptAjaxCreateUpdateNoModalSubmit(data, false, "/tickers/delete", 'DELETE');
    }

    function showAddModal(){
        let modal = '#add_modal';
        let typeId = '#type_id';
        let category = '#category_id';

        $(modal+' .room-div').hide();
        $(modal+' .group-div').hide();

        inFooterScriptDropDownBelowSelect2(modal, typeId);
        inFooterScriptDropDownAboveSelect2(modal, category);

        let typeData = {
            id: '',
            category: 'broadcast_messages_group_type',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadTypeDropdownResult, "/search/message-types", typeData);
        
        let categoryData = {
            id: ''
        };
        inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/device-group", categoryData);
        
        $(modal).modal('show');
    }

    function showChangeOrderModal(id, data){
        let data_id = id;
        let modal = '#change_order_modal';
        let arr = data;

        $(modal+' #id').val(arr.id);
        $(modal+' #order_no').val(arr.order_no);

        $(modal).modal('show');
    }

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        let arr = data;
        // console.log(arr);
        $(modal+' #id').val(arr.id).data('data-info', arr);
        $(modal+' #duration').text(arr.duration);
        $(modal+ ' #message').empty();
        $(modal+' #message').append(arr.message);

        $(modal).modal('show');
        $(modal).on('hidden.bs.modal', function(){
          // Remove focus from the currently focused element inside modal
          $(document.activeElement).blur();
        });
    }

    function clickResend(id)
    {   
        let data = {
            id : id
        }

        // Call the global SweetAlert confirmation function
        inFooterScriptSweetAlertDialogConfirmation(
            "Are you sure?",                // Custom title
            "Do you want to resend this ticker?", // Custom text
            "Yes, resend it!",              // Custom confirm button text
            function () {                   // Callback function
                // This will run only when the user confirms
                inFooterScriptAjaxBasicGet(resultContainer, "/tickers/resend", data);

                // Optionally, show a success message after the action
                Swal.fire({
                    title: "Resent!",
                    text: "The ticker has been resent successfully.",
                    icon: "success"
                });
            }
        );
        
        // inFooterScriptAjaxBasicGet(resultContainer, "/tickers/resend", data);
    }

    //other sample
    function deleteItem(id) {
        inFooterScriptSweetAlertPop(
            "Delete Confirmation",
            "Are you sure you want to delete this item?",
            "Yes, delete it!",
            function () {
                console.log("Item with ID " + id + " has been deleted.");
            }
        );
    }

    function resultContainer(result)
    {
        dt_table.ajax.reload(null, false);
        sweetAlert2(result.status, result.message);
    }

    function reloadDataTable(data, modal, reform=false){
        dt_table.ajax.reload(null, false);
        sweetAlert2(data.status, data.message);
        rows_selected = []; 
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
            $('#add_modal #message').summernote('code', '');
        }
        else{
            $(modal).modal('hide');
        }
        
    }

    function loadTypeDropdownResult(result, data){
        let len = result.length;
        
        $(data.modal+" #type_id").empty();
        $(data.modal+" #type_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
           
            if(data.id==id){$(data.modal+" #type_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #type_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    function loadCategoryDropdownResult(result, data){
        let len = result.length;
        
        $("#category_id").empty();
        $("#category_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
           
            if(data.id==id){$("#category_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$("#category_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    $(document).on('change', '#add_modal #type_id', function() {
        let typeId = $(this).val();
        let modal = '#add_modal';
        
        $(modal+' .group-div').hide();

        switch (typeId) {
            case '41':
                $(modal+' .group-div').show();
                break;
        
            default:
                $(modal+' .group-div').hide();
                break;
        }
    });

</script>

@stop