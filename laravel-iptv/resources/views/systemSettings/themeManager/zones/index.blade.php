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
    @include('systemSettings/themeManager/zones/modals/add/form')
    @include('systemSettings/themeManager/zones/modals/edit/form')
    @include('systemSettings/themeManager/zones/modals/delete/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;

    $(document).ready(function() {    
        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('zones.create')
                    { text: '+ Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/zones/data',  
            type: 'GET',
            data: function(data) {
                // Add multiple parameters
                data.search = $('input[type="search"]').val();
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', width: '1%',
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'name', name: 'name', title: 'Name', width: '70%', orderable: true, searchable: true},
                
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '30%'},
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
        
        $(modal).modal('show');

        $(modal).on('hidden.bs.modal', function(){
            // Remove focus from the currently focused element inside modal
            $(document.activeElement).blur();
        
            $('.error_txt').remove();
            $(".form-control").removeClass("is-invalid");
            $(this)
            .find("input,textarea,select")
            .val('')
            .end();
        });
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let arr = data;
        
        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);

        $(modal).modal('show');

        $(modal).on('hidden.bs.modal', function(){
            // Remove focus from the currently focused element inside modal
            $(document.activeElement).blur();
        
            $('.error_txt').remove();
            $(".form-control").removeClass("is-invalid");
            $(this)
            .find("input,textarea,select")
            .val('')
            .end();
        });
    }

    function reloadDataTable(data, modal, reform=false){
        dt_table.ajax.reload(null, false);
        sweetAlert2(data.status, data.message);
        rows_selected = []; 
        if(reform){
            $('.error_txt').remove();
            $(modal)
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