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
    @include('deviceAdb/apk/modals/delete/form')
    @include('deviceAdb/apk/modals/upload/form')
    
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
                @can('device_adb_apk.create')
                    { text: 'Upload <i class="fa fa-download"></i>', className: 'btn btn-primary me-1 schedule-icon-btn schedule-list-btn"', action: function ( e, dt, node, config ) {
                        showUploadModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/adb-apk/data',  
            type: 'GET',
            data: function(data) {
                data.search = $('input[type="search"]').val();
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', 
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'name', name: 'name', title: 'File Name', orderable: false, searchable: false, width: '5%'},
                { data: 'created_at', name: 'created_at', title: 'Date Created' },
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false},
            ]
        });

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    function showUploadModal(){
        let modal = '#upload_modal';
        
        $(modal).modal('show');

        $(modal).on('hidden.bs.modal', function(){
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
        console.log(data.message);
        if(reform){
            $('.error_txt').remove();
            $(modal)
            .find("input,textarea,select")
            .val('')
            .end();
            // Properly reset Select2 dropdowns
            $(modal).find('select').each(function() {
                $(this).val(null).trigger('change');  // Clear Select2 selections
            });
        }
        else{
            $(modal).modal('hide');
        }
        
    }
   
</script>

@stop