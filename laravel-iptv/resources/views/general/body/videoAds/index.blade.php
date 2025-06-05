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
    @include('general/body/videoAds/modals/add/form')
    @include('general/body/videoAds/modals/edit/form')
    @include('general/body/videoAds/modals/view/form')
    @include('general/body/videoAds/modals/delete/form')
    @include('general/body/videoAds/modals/changeOrder/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;
    let rows_selected = []; 
    let roomListBox;
    
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
                @can('video_ads.create')
                    { text: '+ Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
                @can('video_ads.delete_selected')
                    { text: 'Delete Selected', className: 'btn btn-danger delete-selected-btn', action: function ( e, dt, node, config ) {
                        deleteSelected();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/video-ads/data',  
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
                { data: 'video_uri', name: 'video_uri', title: 'Video', orderable: false, searchable: false, width: '2%'},       
                { data: 'name', name: 'name', title: 'Title', width: '10%', orderable: true, searchable: true},
                { data: 'rooms', name: 'rooms', title: 'Rooms', width: '20%', orderable: false, searchable: false,
                    render: function (data) {
                        const roomArray = data.split(', '); // Assuming data is a string
                        if (roomArray.length > 20) {
                            return roomArray.slice(0, 20).join(', ') + '...'; // Truncate
                        }
                        return data; // No truncation
                    }
                },
                { data: 'date', name: 'date', title: 'Date', width: '1%', orderable: true, searchable: false, className:'text-end'},
                { data: 'is_enable', name: 'is_enable', title: 'Enabled', width: '1%', orderable: true, searchable: false, className:'text-center'},
                { data: 'order_no', name: 'order_no', title: 'Order', width: '1%', orderable: true, searchable: true, className:'text-center'},
                { data: 'actions', name: 'actions', title: 'Action', orderable: false, searchable: false, width: '1%', className:'text-center'},
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
            "Do you want to delete this advertisement?", // Custom text
            "Yes, delete it!",              // Custom confirm button text
            function () {                   // Callback function
                // This will run only when the user confirms
                inFooterScriptAjaxCreateUpdateNoModalSubmit(data, false, "/video-ads/delete", 'DELETE');
                rows_selected.length = 0;
                $('.delete-selected-btn').hide();
            }
        );
    }

    function showAddModal(){
        let modal = '#add_modal';
        roomListBox   = $(modal+" #duallistbox").bootstrapDualListbox({
            nonSelectedListLabel: 'Non-selected',
            selectedListLabel: 'Selected',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: true,
            selectorMinimalHeight: 250
        });
        const preselectedIds = [];

        let roomData = {
            id: preselectedIds,
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
            // Clear existing options before appending new ones
            roomListBox.empty();
        });
    }

    function toggleEnable(id)
    {   
        let data = {
            id : id
        }

        inFooterScriptAjaxBasicGet(resultContainer, "/video-ads/toggle-enable", data);
    }

    function resultContainer(result)
    {
        dt_table.ajax.reload(null, false);
        sweetAlert2(result.status, result.message);
    }

    function showChangeOrderModal(id, data){
        let data_id = id;
        let modal = '#change_order_modal';
        let arr = data;

        $(modal+' #id').val(arr.id);
        $(modal+' #order_no').val(arr.order_no);

        $(modal).modal('show');
        $(modal).on('hidden.bs.modal', function(){
            // Remove focus from the currently focused element inside modal
            $(document.activeElement).blur();
        });
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        roomListBox   = $(modal+" #duallistbox").bootstrapDualListbox({
            nonSelectedListLabel: 'Non-selected',
            selectedListLabel: 'Selected',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: true,
            selectorMinimalHeight: 250
        });
        let arr = data;
        let roomIds = [];
        arr.video_ads_rooms.forEach(room => {
            roomIds.push(room.room.id);
        });
        console.log(roomIds);

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

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        let arr = data;
        let rooms = [];
        
        $(modal+' #id').val(arr.id).data('data-info', arr);
        $(modal+' #name').text(arr.name);
        arr.video_ads_rooms.forEach(room => {
            rooms.push(room.room.name);
        });
        $(modal+ ' #room').empty();
        $(modal+ ' #room').text(rooms.join(', '));
        $(modal + ' #video').empty(); // Clear previous content
        let video = `<a href="${arr.video_uri}" target="_blank">
                        <video src="${arr.video_uri}" autoplay controls class="media-object img img-thumbnail" style="width:350px"></video>
                    </a>`;
        $(modal + ' #video').append(video);
        $(modal).modal('show');

        // Stop video playback when the modal is closed
        $(modal).on('hidden.bs.modal', function () {
            
            // Remove focus from the currently focused element inside modal
            $(document.activeElement).blur();

            $(modal + ' #video').empty(); // Remove the video element to stop playback
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
            roomListBox.bootstrapDualListbox('refresh',true);
        }
        else{
            $(modal).modal('hide');
        }
        
    }

</script>

@stop