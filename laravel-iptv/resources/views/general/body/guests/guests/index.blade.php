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
    @include('general/body/guests/guests/modals/add/form')
    @include('general/body/guests/guests/modals/edit/form')
    @include('general/body/guests/guests/modals/view/form')
    @include('general/body/guests/guests/modals/delete/form')
    @include('general/body/guests/guests/modals/changeOrder/form')
    
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

        inFooterScriptAjaxDataTable({
            tableId: '#table',  
            scrollX: true,
            serverSide: true,
            stateSave: true,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
                @can('guests.create')
                    { text: 'Add Guest', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddModal();
                    }},
                @endcan
            ],
            pageLength: 10,
            searching: true,
            url: '/guests/data',  
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
                { data: 'name', name: 'name', title: 'Name', width: '20%',
                    render: function(data, type, row) {
                        return `${row.name}`;
                    } 
                },
                
                { data: 'city', name: 'city', title: 'City', width: '10%'},
                { data: 'country', name: 'country', title: 'Country' , width: '10%'},
                { data: 'mobile_no', name: 'mobile_no', title: 'Mobile No' , orderable: false, searchable: false, width: '10%'},
                

                
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
        let countryCode = '#country_id';

        inFooterScriptDatePicker(modal, "#birthdate");
        inFooterScriptDropDownAboveSelect2(modal, countryCode);

        let countryData = {
            id: '',
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadCountryDropdownResult, "/search/countries", countryData);

        $(modal).modal('show');
    }

    function showEditModal(id, data){
        let data_id = id;
        let modal = '#edit_modal';
        let arr = data;
        let countryCode = '#country_id';

        inFooterScriptDateTimePicker(modal, "#birthdate", arr.birthdate);
        inFooterScriptDropDownAboveSelect2(modal, countryCode);

        let countryData = {
            id: arr.country_id,
            modal: modal
        };
        inFooterScriptAjaxBasicGet(loadCountryDropdownResult, "/search/countries", countryData);

        $(modal+' #id').val(arr.id);
        $(modal+' #title').val(arr.title);
        $(modal+' #firstname').val(arr.firstname);
        $(modal+' #lastname').val(arr.lastname);
        $(modal+' #birthdate').val(arr.birthdate);
        $(modal+' #landline_no').val(arr.landline_no);
        $(modal+' #mobile_no').val(arr.mobile_no);
        $(modal+' #email').val(arr.email);
        $(modal+' #street1').val(arr.street1);
        $(modal+' #street2').val(arr.street2);
        $(modal+' #city').val(arr.city);
        $(modal+' #state_region').val(arr.state_region);
        $(modal+' #zip_code').val(arr.zip_code);

        $(modal).modal('show');
    }

    function showViewModal(id, data){
        let data_id = id;
        let modal = '#view_modal';
        let arr = data;
        let name = (arr.title)?arr.title+' '+arr.firstname+' '+arr.lastname:arr.firstname+' '+arr.lastname;

        $(modal+' #id').val(arr.id).data('data-info', arr);
        $(modal+' #name').text(name);
        $(modal+' #birthdate').text(arr.birthdate);
        $(modal+' #landline_no').text(arr.landline_no);
        $(modal+' #mobile_no').text(arr.mobile_no);
        $(modal+' #email').text(arr.email);
        $(modal+' #street1').text(arr.street1);
        $(modal+' #street2').text(arr.street2);
        $(modal+' #city').text(arr.city);
        $(modal+' #state_region').text(arr.state_region);
        $(modal+' #country').text(arr.country.country_name);
        $(modal+' #zip_code').text(arr.zip_code);

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
        }
        else{
            $(modal).modal('hide');
        }
        
    }

    function loadCountryDropdownResult(result, data){
        let len = result.length;
        
        $(data.modal+" #country_id").empty();
        $(data.modal+" #country_id").append(`<option></option>`);
        for( let i = 0; i<len; i++){
            let id = result[i]['id'];
            let name = result[i]['country_name'];
           
            if(data.id==id){$(data.modal+" #country_id").append("<option selected value='"+id+"'>"+name+"</option>");}
            else{$(data.modal+" #country_id").append("<option value='"+id+"'>"+name+"</option>");}
        }
    };

</script>


@stop