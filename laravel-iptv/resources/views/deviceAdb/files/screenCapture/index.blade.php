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
    @include('deviceAdb/files/screenCapture/modals/delete/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
    let lastUpdate = "";

    $(document).ready(function() {    
        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                
            ],
            pageLength: 10,
            searching: true,
            url: '/adb-screen-capture/data',  
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
                { data: 'img_uri', name: 'img_uri', title: 'Image', orderable: false, searchable: false, width: '2%'},
                { data: 'mac_address', name: 'mac_address', title: 'MAC Address', 
                    render: function(data, type, row) {
                        return `${row.mac_address}`;
                    } 
                },
                { data: 'ip4_address', name: 'ip4_address', title: 'IPv4' },
                { data: 'room', name: 'room', title: 'Room' },
                { data: 'created_at', name: 'created_at', title: 'Date Created' },
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false},
            ]
        });

        // Auto-refresh data every 5 seconds if refresh is 'yes'
        // setInterval(function() {
        //     dt_table.ajax.reload(null, false); // Reload the table data
        // }, 15000);

        // Check for changes every 5 seconds
        setInterval(checkForUpdates, 5000);

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    // Function to check for table changes
    function checkForUpdates() {
        $.ajax({
            url: '/adb-screen-capture/check-table-changes',
            type: 'GET',
            success: function(response) {
                
                if (lastUpdate !== response.latest_update) {
                    lastUpdate = response.latest_update; // Update stored timestamp
                    dt_table.ajax.reload(null, false); // Reload DataTable without resetting pagination
                }
                else{
                    lastUpdate = response.latest_update; // Set initial timestamp
                } 
            }
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
        }
        else{
            $(modal).modal('hide');
        }
        
    }
   
</script>

@stop