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
    @include('general/body/hospitality/facilities/modals/add/form')
    @include('general/body/hospitality/facilities/modals/edit/form')
    @include('general/body/hospitality/facilities/modals/view/form')
    @include('general/body/hospitality/facilities/modals/delete/form')
    
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
        $('#add_modal #description').summernote('code', ''); //Clear Summernote Description
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
            order: [[0, 'desc']],
            buttons: [
                @can('facilities.create')
                    { text: '+ Add Record', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/facilities/data',  
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
                { data: 'img_uri', name: 'img_uri', title: 'Image', orderable: false, searchable: false, width: '2%'},
                { data: 'name', name: 'name', title: 'Name', width: '20%',
                    render: function(data, type, row) {
                        return `${row.name}`;
                    } 
                },
                { data: 'category', name: 'category', title: 'Category', width: '10%'},
                { data: 'is_enable', name: 'is_enable', title: 'Enabled', searchable: false, width: '1%', class: 'text-center'},
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
        let fileInput = $('#add_modal #img_uri');

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

        inFooterScriptDropDownBelowSelect2(modal, categoryTypeId);

        let categoryData = {
            id: '',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/facility-categories", categoryData);
        $(modal+' #img-thumbnail').attr('src', '/upload/no_image.jpg');
        $(modal+' #img-thumbnail-href').attr('href', '/upload/no_image.jpg');
        $(modal).modal('show');
    }

    function toggleEnable(id)
    {   
        let data = {
            id : id
        }

        inFooterScriptAjaxBasicGet(resultContainer, "/facilities/toggle-enable", data);
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let arr = data;
        let categoryTypeId = '#category_id';
        let fileInput = $('#edit_modal #img_uri');

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

        $(modal+' #img_uri').on('change', function() {
            // Check if a file is selected
            if (this.files[0] !== undefined) {
                const file = this.files[0];

                // Check if the file is an image
                if (file.type.startsWith('image/')) {
                    // Create a URL for the selected file and set it as the image source
                    $(modal+' #img-thumbnail').attr('src', window.URL.createObjectURL(this.files[0]));
                    $(modal+' #img-thumbnail-href').attr('href', 'javascript:void(0)');
                }
                else{
                    // If the file is not an image, hide the thumbnail
                    $(modal + ' #img-thumbnail').attr('src', '/upload/no_image.jpg');
                }
            } else {
                // Optional: Handle the case when no file is selected (if needed)
                $(modal+' #img-thumbnail').attr('src', ''); // Clear the image source
            }
        });

        inFooterScriptDropDownBelowSelect2(modal, categoryTypeId);

        $(modal+' #id').val(arr.id);
        $(modal+' #name').val(arr.name);
        $(modal+' #description').summernote('code', arr.description);
        if(arr.category){
            let data = {
                id: arr.category_id,
                modal: modal
            };
            inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/facility-categories", data);  
        }
        else{
            let data = {
                id: '',
                modal: modal
            };
            inFooterScriptAjaxBasicGet(loadCategoryDropdownResult, "/search/facility-categories", data);
        }
        if(arr.img_uri){
            $(modal+' #img-thumbnail').attr('src', arr.img_uri);
            $(modal+' #img-thumbnail-href').attr('href', arr.img_uri);
        }
        else{
            $(modal+' #img-thumbnail').attr('src', '/upload/no_image.jpg');
            $(modal+' #img-thumbnail-href').attr('href', '/upload/no_image.jpg');
        }

        $(modal).modal('show');
    }

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        let arr = data;
        
        $(modal+' #id').val(arr.id).data('data-info', arr);
        $(modal+' #name').text(arr.name);
        $(modal+' #category_id').text(arr.category.name);
        $(modal+ ' #description').empty();
        $(modal+' #description').append(arr.description);
        $(modal+' #unit_price').text(arr.unit_price);
        if(arr.img_uri){
            $(modal+' #img-thumbnail-href').show();
            $(modal+' #img-thumbnail').attr('src', arr.img_uri);
            $(modal+' #img-thumbnail-href').attr('href', arr.img_uri);
        }
        else{
            $(modal+' #img-thumbnail-href').hide();
        }

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
            $('#add_modal #description').summernote('code', '');
            $('#add_modal #img-thumbnail').attr('src', '/upload/no_image.jpg');
            $('#add_modal #img-thumbnail-href').attr('href', '/upload/no_image.jpg');
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

    function resultContainer(result)
    {
        dt_table.ajax.reload(null, false);
        sweetAlert2(result.status, result.message);
    }
</script>

@stop