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
    @include('deviceAdb/manager/modals/groupInstall/form') 
    @include('deviceAdb/manager/modals/groupUninstall/form') 
    @include('deviceAdb/manager/modals/settings/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
    let refreshParam = 'no';

    $(document).ready(function() {    
        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('device_adb_manager.group_install')
                    { text: 'Group Install <i class="fa fa-download"></i>', className: 'btn btn-primary me-1 schedule-icon-btn schedule-list-btn"', action: function ( e, dt, node, config ) {
                        showGroupInstall();
                    }},
                @endcan
                @can('device_adb_manager.group_uninstall')
                    { text: 'Group Uninstall <i class="fa fa-trash-arrow-up"></i>', className: 'btn btn-danger me-1 schedule-icon-btn schedule-list-btn"', action: function ( e, dt, node, config ) {
                        showGroupUninstall();
                    }},
                @endcan
                @can('device_adb_manager.settings')
                    { text: 'Settings <i class="fa fa-gear"></i>', className: 'btn btn-warning me-1 schedule-icon-btn schedule-list-btn"', action: function ( e, dt, node, config ) {
                        showSettings();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/adb-manager/data',  
            type: 'GET',
            data: function(data) {
                data.search = $('input[type="search"]').val();
                data.refresh = refreshParam; 
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', 
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'mac_address', name: 'mac_address', title: 'MAC Address', 
                    render: function(data, type, row) {
                        return `${row.mac_address}`;
                    } 
                },
                { data: 'ip4_address', name: 'ip4_address', title: 'IPv4' },
                { data: 'model', name: 'model', title: 'Model' },
                { data: 'architecture', name: 'architecture', title: 'Architecture' },
                { data: 'launcher', name: 'launcher', title: 'Launcher' },
                { data: 'launcher_version', name: 'launcher_version', title: 'Version' },
                // { data: 'room', name: 'room', title: 'User' },
                { data: 'foreground', name: 'foreground', title: 'Activity' },
                { data: 'status', name: 'status', title: 'Status' },
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false},
            ]
        });

        // Auto-refresh data every 5 seconds if refresh is 'yes'
        setInterval(function() {
            refreshParam = 'yes';  // Change refresh to 'yes' on interval
            dt_table.ajax.reload(null, false); // Reload the table data
        }, 15000);

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    function showGroupInstall(){
        let modal = '#group_install_modal';
        let roomCategoryId = '#apk';

        inFooterScriptDropDownBelowSelect2(modal, roomCategoryId);

        let roomCategoryData = {
            id: '',
            // category: 'room_status',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadApkListDropdownResult, "/adb-manager/apk-list", roomCategoryData);
        
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
            // Properly reset Select2 dropdowns
            $(modal).find('select').each(function() {
                $(this).val(null).trigger('change');  // Clear Select2 selections
            });
        });
    }

    function showGroupUninstall(){
        let modal = '#group_uninstall_modal';
        
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

    function showSettings(){
        let modal = '#settings_modal';

        let settingsData = {
            id: '',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadSettingsResult, "/search/settings", settingsData);
        
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

    function loadSettingsResult(result, data){

        $(data.modal+" #server_interface").val(result.server_interface);
        $(data.modal+" #server_ip_address").val(result.server_ip_address);
        $(data.modal+" #package_name").val(result.launcher_package_name);
    };

    function loadApkListDropdownResult(result, data){
        let len = result.length;
        
        $(data.modal+" #apk").empty();
        $(data.modal+" #apk").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i];
            let name = result[i];
           
            if(data.id==id){$(data.modal+" #apk").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #apk").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    function screenCaptureRecord(id, ipAddress, type, macAddress)
    {   
        let data = {
            ip_address : ipAddress,
            mac_address : macAddress
        }

        if (type == 'capture') {
            url = "/adb-manager/screen-capture";
        }
        else{
            url = "/adb-manager/screen-record";
        }

        // Call the global SweetAlert confirmation function
        inFooterScriptSweetAlertDialogConfirmation(
            "Are you sure?",                // Custom title
            "Continue to screen "+type+" "+ipAddress+"?", // Custom text
            "Yes!",              // Custom confirm button text
            function () {                   // Callback function
                // This will run only when the user confirms
                inFooterScriptAjaxBasicGet(resultContainer, url, data);
            }
        );
    }

    function resultContainer(result)
    {
        // dt_table.ajax.reload(null, false);
        sweetAlert2(result.status, result.message);
    }

    function reloadDataTable(data, modal, reform=false){
        // dt_table.ajax.reload(null, false);
        refreshParam = 'no';
        dt_table.ajax.reload(); // Reload the table data
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