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
    @include('general/body/guests/rooms/modals/add/form')
    @include('general/body/guests/rooms/modals/edit/form')
    @include('general/body/guests/rooms/modals/view/form')
    @include('general/body/guests/rooms/modals/delete/form')
    @include('general/body/guests/rooms/modals/changeOrder/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;

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

    $('#edit_modal').on('hidden.bs.modal', function(){
       
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

    $(document).ready(function() {     

        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('room.create')
                    { text: 'Add Room', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/rooms/data',  
            type: 'GET',
            data: function(data) {
                // Add multiple parameters
                data.search = $('input[type="search"]').val();
                // data.customParam1 = 'example1';  // First parameter
                // data.customParam2 = 'example2';  // Second parameter
                // You can continue adding more parameters as needed
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', width: '1%',
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'name', name: 'name', title: 'Name', width: '20%',
                    render: function(data, type, row) {
                        return `${row.name}`;
                    } 
                },
                { data: 'category', name: 'category', title: 'Type', width: '10%'},
                { data: 'status', name: 'status', title: 'Room Status' , width: '1%'},
                { data: 'mac_address', name: 'mac_address', title: 'Mac Address' , orderable: false, searchable: false, width: '25%'},
                

                
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '10%'},
            ]
        });

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    function showAddModal(){
        let modal = '#add_modal';
        let categoryTypeId = '#category_id';
        let roomStatusId = '#room_status';

        inFooterScriptDropDownBelowSelect2(modal, categoryTypeId);
        inFooterScriptDropDownBelowSelect2(modal, roomStatusId);

        let categoryData = {
            id: '',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/room-categories", categoryData);
        let statusData = {
            id: '',
            category: 'room_status',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadStatusDropdownResult, "/search/room-statuses", statusData);

        $(modal).modal('show');
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let arr = data;
        let categoryTypeId = '#category_id';
        let roomStatusId = '#room_status';

        inFooterScriptDropDownBelowSelect2(modal, categoryTypeId);
        inFooterScriptDropDownBelowSelect2(modal, roomStatusId);

        // console.log('fire-------------', arr);

        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);
        if(arr.category){
            let data = {
                id: arr.category_id,
                modal: modal
            };
            inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/room-categories", data);  
        }
        else{
            let data = {
                id: '',
                modal: modal
            };
            inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/room-categories", data);
        }

        if(arr.status){
            let data = {
                id: arr.status.id,
                category: 'room_status',
                modal: modal
            };
            inFooterScriptAjaxBasicGet(loadStatusDropdownResult, "/search/room-statuses", data);  
        }
        else{
            let data = {
                id: '',
                category: 'room_status',
                modal: modal
            };
            inFooterScriptAjaxBasicGet(loadStatusDropdownResult, "/search/room-statuses", data);
        }

        $(modal).modal('show');
    }

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        let arr = data;
        $(modal+' #id').val(arr.id).data('device-info', arr);
        $(modal+' #name').text(arr.name);
        $(modal+' #category_id').text(arr.category.name);
        $(modal+' #room_status').text(arr.status.name);

        $(modal).modal('show');
        $(modal).on('hidden.bs.modal', function(){
          // Remove focus from the currently focused element inside modal
          $(document.activeElement).blur();
        });
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

    function loadCategoryDropdownResult(result, data){
        let len = result.length;
        
        $(data.modal+" #category_id").empty();
        $(data.modal+" #category_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
           
            if(data.id==id){$(data.modal+" #category_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #category_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    function loadStatusDropdownResult(result, data){
        let len = result.length;
        $(data.modal+" #room_status").empty();
        $(data.modal+" #room_status").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
            
            if(data.id==id){$(data.modal+" #room_status").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #room_status").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };
</script>

<style>
    /* .was-validated .custom-select:invalid + .select2 .select2-selection{
    border-color: #dc3545!important;
    }
    .was-validated .custom-select:valid + .select2 .select2-selection{
        border-color: #28a745!important;
    }
    *:focus{
    outline:0px;
    } */
</style>

@stop