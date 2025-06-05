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
    @include('systemUsers/roles/modals/add-role-form')
    @include('systemUsers/roles/modals/edit-role-form')
    @include('systemUsers/roles/modals/delete-form')
@stop

@section('pages_specific_scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let table_role;

    $('#addRole_modal').on('hidden.bs.modal', function(){
        
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
        
        $('.error_txt').remove();
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();    
    });

    $('#editRole_modal').on('hidden.bs.modal', function(){
        
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
    
        $('.error_txt').remove();
        $(this)
        .find("input,textarea,select")
        .val('')
        .end();    
    });

    function showAddNewForm(){
        $('#addRole_modal').modal('show');

        let data = {};
        
        $( '#permissions' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
            allowClear: true,
        } );   

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/admin/permission/data",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {
                
                var len = data.data.permissions.length;
                
                $("#permissions").empty();
                for( var i = 0; i<len; i++){
                    // var id = data.data[i]['id'];
                    var name = data.data.permissions[i]['name'];
                    var display_name = data.data.permissions[i]['display_name'];
                    $("#permissions").append("<option value='"+name+"'>"+display_name+"</option>");
                }
            }
        });
    }

    $("#editRole_modal").on('show.bs.modal', function (e) {
        let triggerLink = $(e.relatedTarget);
        let display_name = triggerLink.data("display_name");
        let description = triggerLink.data("description");
        let id = triggerLink.data("id");
        let name = triggerLink.data("name");
        let level = triggerLink.data("level");

        let data = {
            role_id: id
        };

        $( '#epermissions' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
            allowClear: true,
        } );   

        $("input#edisplay_name").val(display_name);
        $("textarea#edescription").val(description);
        $("input#eid").val(id);
        $("input#ename").val(name);
        $("input#elevel").val(level);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/admin/permission/data",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {
                
                var all = data.data['all'];
                var permissions = data.data['permissions'];
                var len = all.length;
                
                $("#epermissions").empty();
                for( var i = 0; i<len; i++){
                    var id = all[i]['id'];
                    var name = all[i]['name'];
                    var display_name = all[i]['display_name'];
                    var selected = permissions.includes(name) ? 'selected' : '';
                    $("#epermissions").append("<option value='"+name+"' "+selected+">"+display_name+"</option>");
                }
            }
        });
    });

    $(document).ready(function() {

        const role_table = $('#dt_table').DataTable({
            scrollX: true,
            serverSide: true,
            stateSave: true,
            pageLength: 50,
            dom: 'fBtip',
            buttons: [
                @can('role.create')
                    { text: 'Add Role', className: 'btn btn-primary', action: function ( e, dt, node, config ) {
                        showAddNewForm();
                    }},
                @endcan
            ],
            searching: true,
            ajax: {
                url: "/admin/role/data",
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
                { data: 'display_name', name: 'display_name', title: 'Name' },
                { data: 'actions', name: 'actions', title: 'Action' , orderable: false},
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

    function deleteRole(id){
        sweetAlertLoading();
        $.ajax({
            url: "/admin/role/delete_role",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            },
            dataType: 'json',
            success:function(response){
                //sweetAlert(response.status,response.message);
                Swal.fire({
                    position: 'center',
                    icon: response.status,
                    title: response.message,
                    showConfirmButton: false,
                    timer: 4000
                });
                table_role.ajax.reload(null, false);
            }
        });
    }

    

</script>



@stop