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
    @include('general/body/devices/devices/modals/edit/form')
    @include('general/body/devices/devices/modals/view/form')
    @include('general/body/devices/devices/modals/delete/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;

    $('#view_modal').on('hidden.bs.modal', function(){
        
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
      
        $('.error_txt').remove();
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();    
    });

    function detectDevices(){
        // let data = {};
        // inFooterScriptAjaxBasicGet(detectDevicesResult, "/devices/detect-devices", data);
        inFooterScriptAjaxBasicGet(detectDevicesResult, "/analytics/ping-devices");
    }

    function detectDevicesResult(data) {
        console.log(data);  // Handle the data
    }

    $(document).ready(function() {     
        //pusher/channel for dynamic reload
        let pusher = new Pusher('46081c3d261c0267298e', {
            cluster: 'ap1',
        });
        let channel = pusher.subscribe('device-channel');
        channel.bind('device-event', function(data) {
            switch (data.result.type) {
				case "stb":
					dt_table.ajax.reload(null, false);
					break;
				default:
					break;
			}
        });

        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('devices.detect_devices')
                    { text: 'Detect Devices <i class="fa fa-bullhorn"></i>', className: 'btn btn-primary me-1 schedule-icon-btn schedule-list-btn"', action: function ( e, dt, node, config ) {
                        detectDevices();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/devices/data',  
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
                { data: 'mac_address', name: 'mac_address', title: 'MAC Address', 
                    render: function(data, type, row) {
                        return `${row.mac_address}`;
                    } 
                },
                { data: 'ip4_address', name: 'ip4_address', title: 'IPv4' },
                { data: 'room', name: 'room', title: 'User' },
                { data: 'os_version', name: 'os_version', title: 'Version' },
                { data: 'current_status', name: 'current_status', title: 'Status' },

                
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false},
            ]
            // searchInputId: '#custom_search_input',
            // lengthChangeId: '#custom_length_change',
            // buttonsContainer: '.custom-dt-buttons'
        });

        // Placement controls for Table filters and buttons
		dt_table.buttons().container().appendTo( '.dt-card-header' ); 
        $('#search_input').val(dt_table.search());
		$('#search_input').keyup(function(){ dt_table.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { dt_table.page.len($(this).val()).draw() });
    });

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        
        let arr = data;

        // Assign data attributes using jQuery's .data() method
        $(modal+' #id').val(arr.id).data('device-info', arr); // Attach the array as data to the element
        $(modal+' #name').text(arr.room.name);
        $(modal+' #api_key').text(arr.api_id);
        $(modal+' #ip4_address').text(arr.ip4_address);
        $(modal+' #mac_address').text(arr.mac_address);
        if(arr.category){
            $(modal+' #category').text(arr.category.name);
        }
        else{
            $(modal+' #category').text('No Group');
        }
        if(arr.language){
            $(modal+' #language').text(arr.language.name);
        }
        else{
            $(modal+' #language').text('No Language');
        }
        $(modal).modal('show');
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let select_id = '#category_id';
        let languageId = '#language_id';
        let arr = data;

        inFooterScriptDropDownAboveSelect2(modal, select_id);
        inFooterScriptDropDownAboveSelect2(modal, languageId);

        $(modal+' #id').val(arr.id);
        $(modal+' #name').text(arr.room.name);
        $(modal+' #api_key').text(arr.api_id);
        $(modal+' #ip4_address').text(arr.ip4_address);
        $(modal+' #mac_address').text(arr.mac_address);
        if(arr.category){
            // $(modal+' #category').text(arr.category.name);
            let data = {
                id: arr.category_id
            };
            inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/device-group", data);  
        }
        else{
            let data = {
                id: ''
            };
            inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/device-group", data);
        }

        if(arr.language_id){
            // $(modal+' #category').text(arr.category.name);
            let data = {
                type: 'language_type',
                id: arr.language_id
            };
            inFooterScriptAjaxBasicGet(loadLanguageDropdownResult, "/search/languages", data);  
        }
        else{
            let data = {
                type: 'language_type',
                id: ''
            };
            inFooterScriptAjaxBasicGet(loadLanguageDropdownResult, "/search/languages", data);
        }
        
        $(modal).modal('show');
        $(modal).on('hidden.bs.modal', function(){
            // Remove focus from the currently focused element inside modal
            $(document.activeElement).blur();
        });
    }

    function loadCategoryDropdownResult(result, data){
        let len = result.length;
        
        $("#category_id").empty();
        $("#category_id").append(`<option></option>`);
        $("#category_id").append(`<option value="0">Remove Selected Group</option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
           
            if(data.id==id){$("#category_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$("#category_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    function loadLanguageDropdownResult(result, data){
        let len = result.length;

        $("#language_id").empty();
        $("#language_id").append(`<option></option>`);
        // $("#language_id").append(`<option value="0">Remove Selected Group</option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['name'];
           
            if(data.id==id){$("#language_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$("#language_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

    function adbReboot(ip){
        let data = {
                ip: ip
            };
            inFooterScriptAjaxBasicGet(loadAdbRebootResult, "/devices/adb-reboot", data);
    }

    function loadAdbRebootResult(result, data){
        console.log(result);
    }

    function adbResetData(ip){
        let data = {
                ip: ip
            };
            inFooterScriptAjaxBasicGet(loadAdbResetDataResult, "/devices/adb-reset-data", data);
    }

    function loadAdbResetDataResult(result, data){
        console.log(result);
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
        }
        else{
            $(modal).modal('hide');
        }
        
    }
</script>

@stop