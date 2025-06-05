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
    @include('systemSettings/themeManager/themes/modals/add/form') 
    @include('systemSettings/themeManager/themes/modals/delete/form')
    @include('systemSettings/themeManager/themes/modals/editTheme/form')
    @include('systemSettings/themeManager/themes/modals/themeZones/form')
    @include('systemSettings/themeManager/themes/modals/editThemeZone/form')
    @include('systemSettings/themeManager/themes/modals/assignRoom/form')
    @include('systemSettings/themeManager/themes/modals/themeApplications/form')
    @include('systemSettings/themeManager/themes/modals/editThemeApplication/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
    let dt_tableThemezones;
    let dt_tableThemeApplications;
    let roomListBox;

    function onChangeImg(input, thumbnail, href){
        if (input.files[0] != undefined) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (allowedTypes.includes(input.files[0].type)) {
                $(href).show();
                const fileUrl = window.URL.createObjectURL(input.files[0]);
                $(thumbnail).attr('src', fileUrl);
                $(href).attr('href', fileUrl);
            } else {
                $(href).hide();
            }
        }
    }
    
    $(document).ready(function() {    
        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('themes.create')
                    { text: '+ Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/themes/data',  
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

    function showThemeZonesModal(id, data){
        let modal = '#theme_zones_modal';
        $(modal+' .modal-title').text(data.name+' Zones');

        table = $(modal+ ' #table').DataTable({
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [],
            pageLength: 10,
            searching: true,
            autoWidth: false,
            ajax: {
                url: '/themes/theme-zone-data',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(data) {
                    // Add multiple parameters
                    data.search = $('#search_input_themezones').val();
                    data.theme_id = id;
                }
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', width: '1%',
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'zones', name: 'zones', title: 'Name', width: '70%', orderable: true, searchable: true},
                
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '1%'},
            ],
            initComplete: function(settings, json) {
                let selected_len = table.page.len();
                const length_change = $(modal+' #length_change_themezones');
                length_change.empty(); // Clear existing options
                length_change.append($('<option>', {value: 5,text: 'Show 5', selected:(selected_len == "5") ? true : false}));
                length_change.append($('<option>', {value: 10,text: 'Show 10',selected:(selected_len == "10") ? true : false}));
                length_change.append($('<option>', {value: 50,text: 'Show 50',selected:(selected_len == "50") ? true : false}));
                length_change.append($('<option>', {value: 100,text: 'Show 100',selected:(selected_len == "100") ? true : false}));
                length_change.append($('<option>', {value: 1000,text: 'Show 1000',selected:(selected_len == "1000") ? true : false}));
            },
           
        });

        dt_tableThemezones = table;


        // Placement controls for Table filters and buttons
        if (dt_tableThemezones) {
            dt_tableThemezones.buttons().container().appendTo( modal+ ' .dt-card-header-themezones' ); 
            $('#search_input_themezones').val(dt_tableThemezones.search());
            $('#search_input_themezones').keyup(function(){ dt_tableThemezones.search($(this).val()).draw() ; })
            $('#length_change_themezones').change( function() { dt_tableThemezones.page.len($(this).val()).draw() });
        }
        else {
            console.error('DataTable failed to initialize.');
        }
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
            $(modal+' #table').DataTable().clear().destroy(); 
        });
    }

    function showThemeApplicationsModal(id, data){
        let modal = '#theme_applications_modal';
        $(modal+' .modal-title').text(data.name+' Applications');
        
        table = $(modal+ ' #table').DataTable({
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [],
            pageLength: 10,
            searching: true,
            autoWidth: false,
            ajax: {
                url: '/themes/theme-application-data',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(data) {
                    // Add multiple parameters
                    data.search = $('#search_input_themeapplications').val();
                    data.theme_id = id;
                }
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID', width: '1%',
                    render: function(data, type, row) {
                        return `${row.id}`;
                    } 
                },
                { data: 'application', name: 'application', title: 'Name', width: '70%', orderable: true, searchable: true},
                
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '1%'},
            ],
            initComplete: function(settings, json) {
                let selected_len = table.page.len();
                const length_change = $(modal+' #length_change_themeapplications');
                length_change.empty(); // Clear existing options
                length_change.append($('<option>', {value: 5,text: 'Show 5', selected:(selected_len == "5") ? true : false}));
                length_change.append($('<option>', {value: 10,text: 'Show 10',selected:(selected_len == "10") ? true : false}));
                length_change.append($('<option>', {value: 50,text: 'Show 50',selected:(selected_len == "50") ? true : false}));
                length_change.append($('<option>', {value: 100,text: 'Show 100',selected:(selected_len == "100") ? true : false}));
                length_change.append($('<option>', {value: 1000,text: 'Show 1000',selected:(selected_len == "1000") ? true : false}));
            },
           
        });

        dt_tableThemeApplications = table;


        // Placement controls for Table filters and buttons
        if (dt_tableThemeApplications) {
            dt_tableThemeApplications.buttons().container().appendTo( modal+ ' .dt-card-header-themeapplications' ); 
            $('#search_input_themeapplications').val(dt_tableThemeApplications.search());
            $('#search_input_themeapplications').keyup(function(){ dt_tableThemeApplications.search($(this).val()).draw() ; })
            $('#length_change_themeapplications').change( function() { dt_tableThemeApplications.page.len($(this).val()).draw() });
        }
        else {
            console.error('DataTable failed to initialize.');
        }
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
            $(modal+' #table').DataTable().clear().destroy(); 
        });
    }

    function showEditThemeApplicationModal(id, data, themeId){
        let data_id = id;
        let modal = '#edit_theme_application_modal';
        let arr = data; 
        const toggleButton  = $(modal+' #toggleIsNull');
        $(modal + ' #active-text-color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        $(modal + ' #text-color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        
        if(arr.icon){
            $(modal+' #icon-thumbnail').attr('src', arr.icon);
            $(modal+' #icon-thumbnail-href').attr('href', arr.icon);
        }
        else{
            $(modal+' #icon-thumbnail').attr('src', '/upload/no_image.jpg');
            $(modal+' #icon-thumbnail-href').attr('href', '/upload/no_image.jpg');
        }

        if(arr.active_icon){
            $(modal+' #active-icon-thumbnail').attr('src', arr.active_icon);
            $(modal+' #active-icon-thumbnail-href').attr('href', arr.active_icon);
        }
        else{
            $(modal+' #active-icon-thumbnail').attr('src', '/upload/no_image.jpg');
            $(modal+' #active-icon-thumbnail-href').attr('href', '/upload/no_image.jpg');
        }
        
        $(modal+' #id').val(arr.id);
        $(modal+' #theme_id').val(themeId);
        $(modal+' .modal-title').text('Edit ' +arr.application.name);
        $(modal+' #order_no').val(arr.order_no);
        $(modal+' #text_color').val(arr.text_color);
        $(modal+' #active_text_color').val(arr.active_text_color);
        $(modal+' #itext_color').css("background-color", arr.text_color);
        $(modal+' #iactive_text_color').css("background-color", arr.active_text_color);
        
        
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
            
            $(modal+' #itext_color').css("background-color", '#e9ecef');
            $(modal+' #iactive_text_color').css("background-color", '#e9ecef');
        });
    }

    function showAddModal(){
        let modal = '#add_modal';
        let fileInput = $(modal+' #bg');

        $(modal).on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).addClass('dragover');
        });

        $(modal).on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).removeClass('dragover');
        });

        $(modal).on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).removeClass('dragover');

            let files = e.originalEvent.dataTransfer.files;
            fileInput[0].files = files;
            fileInput.trigger('change');
        });

        
        $(modal+' #bg-thumbnail').attr('src', '/upload/no_image.jpg');
        $(modal+' #bg-thumbnail-href').attr('href', '/upload/no_image.jpg');
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

    function showEditThemeModal(id, data){
        let modal = '#edit_theme_modal';
        let fileInput = $(modal+' #bg');
        let data_id = id;
        let arr = data;
        $(modal+' #bg-thumbnail').attr('src', '/upload/no_image.jpg');
        $(modal+' #bg-thumbnail-href').attr('href', '/upload/no_image.jpg');

        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);
        if(arr.bg_uri){
            $(modal+' #bg-thumbnail').attr('src', arr.bg_uri);
            $(modal+' #bg-thumbnail-href').attr('href', arr.bg_uri);
        }
        else{
            $(modal+' #bg-thumbnail').attr('src', '/upload/no_image.jpg');
            $(modal+' #bg-thumbnail-href').attr('href', '/upload/no_image.jpg');
        }

        $(modal).on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).addClass('dragover');
        });

        $(modal).on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).removeClass('dragover');
        });

        $(modal).on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).removeClass('dragover');

            let files = e.originalEvent.dataTransfer.files;
            fileInput[0].files = files;
            fileInput.trigger('change');
        });

        
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

    function showEditThemeZoneModal(id, data){
        let data_id = id;
        let modal = '#edit_theme_zone_modal';
        let fileInput = $(modal+' #bg');
        let arr = data;
        const toggleButton  = $(modal+' #toggleIsNull');
        $(modal + ' #active-text-color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        $(modal + ' #text-color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        
        if(arr.bg_uri == null){
            $(modal+' .image-holder-for-is-null').hide();
            toggleButton.text("Insert Image").removeClass('btn-warning').addClass('btn-success');
            $(modal+' #is_null').val(1);
        }
        else{
            $(modal+' .image-holder-for-is-null').show();
            toggleButton.text("Change to Null").removeClass('btn-success').addClass('btn-warning');
            $(modal+' #is_null').val(0);
        }

        $(modal+' #id').val(arr.id);
        $(modal+' .modal-title').text('Edit '+arr.zones.name);
        
        $(modal+' #order_no').val(arr.order_no);
        $(modal+' #text_color').val(arr.text_color);
        $(modal+' #active_text_color').val(arr.active_text_color);

        $(modal+' #itext_color').css("background-color", arr.text_color);
        $(modal+' #iactive_text_color').css("background-color", arr.active_text_color);
        
        if(arr.bg_uri){
            $(modal+' #bg-thumbnail').attr('src', arr.bg_uri);
            $(modal+' #bg-thumbnail-href').attr('href', arr.bg_uri);
        }
        else{
            $(modal+' #bg-thumbnail').attr('src', '/upload/no_image.jpg');
            $(modal+' #bg-thumbnail-href').attr('href', '/upload/no_image.jpg');
        }

        $(modal).on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).addClass('dragover');
        });

        $(modal).on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).removeClass('dragover');
        });

        $(modal).on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(modal).removeClass('dragover');

            let files = e.originalEvent.dataTransfer.files;
            fileInput[0].files = files;
            fileInput.trigger('change');
        });

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
            
            $(modal+' #itext_color').css("background-color", '#e9ecef');
            $(modal+' #iactive_text_color').css("background-color", '#e9ecef');
        });
    }

    function showAssignRoomModal(id, data){
        let data_id = id;
        let modal = '#assign_room_modal';
        roomListBox   = $(modal+" #duallistbox").bootstrapDualListbox({
            nonSelectedListLabel: 'Non-selected',
            selectedListLabel: 'Selected',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: true,
            selectorMinimalHeight: 250
        });
        let arr = data;
        let roomIds = [];
        arr.theme_rooms.forEach(room => {
            roomIds.push(room.room.id);
        });
        

        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);
        let roomData = {
            id: roomIds,
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadRoomListBoxResult, "/search/rooms", roomData);
        

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
            roomListBox.empty();
        });
    }

    function loadRoomListBoxResult(result, data){
        let len = result.length;

        result.forEach(item => {
            const option = new Option(item.name, item.id);

            // Check if this option should be preselected
            if (data.id.includes(item.id)) {
                option.selected = true;
            }

            roomListBox.append(option);
        });
        roomListBox.bootstrapDualListbox('refresh',true);
    };

    function toggleDefault(id, isDefault)
    {   
        let data = {
            id : id
        }

        // Call the global SweetAlert confirmation function
        inFooterScriptSweetAlertDialogConfirmation(
            "Are you sure?",                // Custom title
            "Do you want to make this as a default?", // Custom text
            "Yes!",              // Custom confirm button text
            function () {                   // Callback function
                // This will run only when the user confirms
                inFooterScriptAjaxBasicGet(resultContainer, "/themes/toggle-default", data);
            }
        );
    }

    function toggleNull(modal)
    {
        const toggleButton  = $(modal+' #toggleIsNull');
        const isNullInput = $(modal+' #is_null');
        const currentValue = isNullInput.val();

        if (currentValue === "1") {
            isNullInput.val("0");
            toggleButton.text("Change to Null").removeClass('btn-success').addClass('btn-warning');
            $(modal+' .image-holder-for-is-null').show();
        } else {
            isNullInput.val("1");
            toggleButton.text("Insert Image").removeClass('btn-warning').addClass('btn-success');
            $(modal+' .image-holder-for-is-null').hide();
        }
    }

    function toggleEnable(id, isEnable)
    {   
        let data = {
            id : id
        }

        if(isEnable == 0){
            enableText = 'enable';
        }else{
            enableText = 'disable';
        }

        // Call the global SweetAlert confirmation function
        inFooterScriptSweetAlertDialogConfirmation(
            "Are you sure?",                // Custom title
            "Do you want to "+enableText+" this?", // Custom text
            "Yes!",              // Custom confirm button text
            function () {                   // Callback function
                // This will run only when the user confirms
                inFooterScriptAjaxBasicGet(resultThemeApplicationContainer, "/themes/toggle-enable", data);
            }
        );
    }

    function resultThemeApplicationContainer(result)
    {console.log(result);
        dt_tableThemeApplications.ajax.reload(null, false);
        sweetAlert2(result.status, result.message);
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
            $(modal)
            .find("input,textarea,select")
            .val('')
            .end();
            $(modal+' #bg-thumbnail').attr('src', '/upload/no_image.jpg');
            $(modal+' #bg-thumbnail-href').attr('href', '/upload/no_image.jpg');
        }
        else{
            $(modal).modal('hide');
        }
        
    }

    function reloadThemeZoneDataTable(data, modal, reform=false){
        dt_tableThemezones.ajax.reload(null, false);
        dt_table.ajax.reload(null, false);
        $(modal).modal('hide');
        sweetAlert2(data.status, data.message);    
    }

    function reloadThemeApplicationDataTable(data, modal, reform=false){
        dt_tableThemeApplications.ajax.reload(null, false);
        dt_table.ajax.reload(null, false);
        $(modal).modal('hide');
        sweetAlert2(data.status, data.message);    
    }
</script>

@stop