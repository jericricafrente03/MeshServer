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
                @if(session('role_status'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('role_status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="dt_table" class="table row-border hover" style="width:100%">
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
    @include('systemUsers/logs/modals/view-form')
    
@stop

@section('pages_specific_scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let table_role;

    $('#view_modal').on('hidden.bs.modal', function(){
        
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
        
        $('.error_txt').remove();
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();    
    });

    function showForm(data){
        $('#view_modal').modal('show');
        
        let description = $(data).data('description');
        
        // let parsedDescription = JSON.parse(description);

        // Clear previous modal content
        $('#from-column').empty();
        $('#to-column').empty();
        $('#log-column').empty();
        $('.from-to-holder').hide();
        $('.log-holder').hide();

        // Check if "from" or "to" exists in the description
        if (description.from && description.to) {
            // If both "from" and "to" exist, populate both columns
            $('.from-to-holder').show();
            $('.log-holder').hide();
            $.each(description.from, function(key, value) {
                $('#from-column').append('<li>' + key + ': ' + value + '</li>');
            });
            $.each(description.to, function(key, value) {
                $('#to-column').append('<li>' + key + ': ' + value + '</li>');
            });
        } else {
            // If only one exists, show the entire data without separation
            $('.from-to-holder').hide();
            $('.log-holder').show();
            $.each(description, function(key, value) {
                $('#log-column').append('<li>' + key + ': ' + value + '</li>');
            });
            // let descriptionString = JSON.stringify(description, null, 2);
            // $('#from-column').append('<li>' + descriptionString + '</li>');
            // $('#to-column').hide(); // Hide the "to" column if not needed
        }
        
    }

    $(document).ready(function() {

        const role_table = $('#dt_table').DataTable({
            scrollX: true,
            serverSide: true,
            stateSave: true,
            pageLength: 50,
            dom: 'fBtip',
            order: [[0, 'desc']],
            buttons: [
    
            ],
            searching: true,
            ajax: {
                url: "/admin/log/data",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (data) {
                    data.search = $('input[type="search"]').val();
                }
            },
            columns: [
                { data: 'id', name: 'id', title: 'ID' },
                { data: 'activity', name: 'activity', title: 'Activity' },
                { data: 'username', name: 'username', title: 'Username' },
                { data: 'ip_address', name: 'ip_address', title: 'IP Address' },
                { data: 'created_at', name: 'created_at', title: 'Date Time' },
                { data: 'actions', name: 'actions', title: 'Action' , orderable: false, searchable: false},
            ],
            initComplete: function( settings, json ) {
                selected_len = role_table.page.len();
				$('#length_change').append($('<option>', {value: 5,text: 'Show 5',selected:(selected_len == "5") ?true: false}));
				$('#length_change').append($('<option>', {value: 10,text: 'Show 10',selected:(selected_len == "10") ?true: false}));
				$('#length_change').append($('<option>', {value: 50,text: 'Show 50',selected:(selected_len == "50") ?true: false}));
				$('#length_change').append($('<option>', {value: 100,text: 'Show 100',selected:(selected_len == "100") ?true: false}));
				$('#length_change').append($('<option>', {value: 1000,text: 'Show 1000',selected:(selected_len == "1000") ?true: false}));
            }

        });

        table_role = role_table;
        table_role.buttons().container().appendTo( '.dt-card-header' );
        $('#search_input').val(table_role.search());
		$('#search_input').keyup(function(){ table_role.search($(this).val()).draw() ; })
	    $('#length_change').change( function() { table_role.page.len($(this).val()).draw() });


    });

    

</script>



@stop