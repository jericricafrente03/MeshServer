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
    @include('general/body/devices/deviceGroup/modals/add/form')
    @include('general/body/devices/deviceGroup/modals/edit/form')
    @include('general/body/devices/deviceGroup/modals/view/form')
    @include('general/body/devices/deviceGroup/modals/delete/form')
    @include('general/body/devices/deviceGroup/modals/changeOrder/form')
    
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
    });

    $('#change_order_modal').on('hidden.bs.modal', function(){
       
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
       
        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();    
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
                @can('device_group.create')
                    { text: 'Add Group', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/device_group/data',  
            type: 'GET',
            data: function(data) {
                // Add multiple parameters
                data.search = $('input[type="search"]').val();
                // data.customParam1 = 'example1';  // First parameter
                // data.customParam2 = 'example2';  // Second parameter
                // You can continue adding more parameters as needed
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', 
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'name', name: 'name', title: 'Name', 
                    render: function(data, type, row) {
                        return `${row.name}`;
                    } 
                },
                { data: 'description', name: 'description', title: 'Description' },
                { data: 'order_no', name: 'order_no', title: 'Order No.' },

                
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false},
            ]
        });

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    function showAddModal(){
        let modal = $('#add_modal');

        modal.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.addClass('dragover');
        });

        modal.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.removeClass('dragover');
        });

        modal.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.removeClass('dragover');

            var files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        $(modal).modal('show');
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let arr = data;
        // let btn = $(`#data-edit-btn-${id}`);
        // let arr = btn.data('array');
        // console.log('fire-------------', arr);

        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);
        $(modal+' #description').val(arr.description);
        $(modal+' #order_no').val(arr.order_no);

        $(modal).modal('show');
    }

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        let arr = data;
        // let btn = $(`#data-view-btn-${id}`);
        // let arr = btn.data('array');
        // console.log('fire-------------', arr);

        $(modal+' #id').val(arr.id).data('device-info', arr);
        $(modal+' #name').text(arr.name);
        $(modal+' #description').text(arr.description);
        $(modal+' #order_no').text(arr.order_no);

        $(modal).modal('show');
        $(modal).on('hidden.bs.modal', function(){
            // Remove focus from the currently focused element inside modal
            $(document.activeElement).blur();
        });
    }

    function showChangeOrderModal(id, data){
        let data_id = id;
        let modal = '#change_order_modal';
        let arr = data;
        // let btn = $(`#data-co-btn-${id}`);
        // let arr = btn.data('array');
        // console.log('fire-------------', arr);

        $(modal+' #id').val(arr.id);
        $(modal+' #order_no').val(arr.order_no);

        $(modal).modal('show');
    }

    function reloadDataTable(data, modal, reform=false)
    {
        dt_table.ajax.reload(null, false);
        sweetAlert2(data.status, data.message);
        if(reform){
            $('.error_txt').remove();
            $('#add_modal')
            .find("input,textarea,select")
            .val('')
            .end();
        }
        else{
            $(modal).modal('hide');
        }
        
    }
</script>

@stop