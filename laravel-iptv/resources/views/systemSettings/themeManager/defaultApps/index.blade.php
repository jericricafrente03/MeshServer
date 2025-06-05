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
    @include('systemSettings/themeManager/defaultApps/modals/add/form')
    @include('systemSettings/themeManager/defaultApps/modals/edit/form')
    @include('systemSettings/themeManager/defaultApps/modals/delete/form')
    
@stop

@section('pages_specific_scripts')
<script>
    let dt_table;

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
                @can('default_apps.create')
                    { text: '+ Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/default-apps/data',  
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
                { data: 'name', name: 'name', title: 'Application', width: '70%', orderable: true, searchable: true},
                
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
        $(modal + ' #color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
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
            $(modal+' #icolor').css("background-color", '#e9ecef');
        });
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let arr = data;
        $(modal + ' #color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        $(modal + ' #text-color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        $(modal + ' #active-text-color-picker').colorpicker({
            //color: '#bbb',
            horizontal: true,
            format: 'hex',
        });
        

        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);
        $(modal+' #method').val(arr.method);
        $(modal+' #order_no').val(arr.order_no);
        $(modal+' #text_color').val(arr.text_color);
        $(modal+' #active_text_color').val(arr.active_text_color);
        $(modal+' #color').val(arr.color);
        $(modal+' #itext_color').css("background-color", arr.text_color);
        $(modal+' #iactive_text_color').css("background-color", arr.active_text_color);
        $(modal+' #icolor').css("background-color", arr.color);
        
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
            $(modal+' #icolor').css("background-color", '#e9ecef');
        });
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
                inFooterScriptAjaxBasicGet(resultContainer, "/default-apps/toggle-enable", data);
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
            $(modal)
            .find("input,textarea,select")
            .val('')
            .end();
            
            $(modal+' #icolor').css("background-color", '#e9ecef');
        }
        else{
            $(modal).modal('hide');
        }
        
    }
</script>

@stop