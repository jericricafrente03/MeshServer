@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->

        <div class="position-fixed top-0 end-0 p-3 mt-5" style="z-index: 11" id="alert-message-for-rbac-updates"></div>

        <div class="card">
            <div class="card-header dt-card-header">
                <select name='length_change' id='length_change' class="table_length_change form-select">
                </select>
                <input type="text" id="search_input" class="table_search_input form-control" placeholder="Search...">
                <select name='group' id='group' class="table_length_change me-3 form-select">
                    <option value="">All Group (Menu)</option>
                    @foreach ($permissionGroups as $key => $val)
                        <option value="{{$key}}">{{ is_int($key) ? $val : ucwords(str_replace("_", " ", $key)) }}</option>
                    @endforeach
                </select>
                <!-- <select name='division' id='division' class="table_length_change form-select">
                    <option value="">All Division</option>
                    <option value="menu_store">Store Pages</option>
                    <option value="menu_store.unique">Pharmacies</option>
                    <option value="system_settings">System Settings</option>
                    <option value="general">Admin+</option>
                </select> -->
            </div>
            <div class="card-body">
                @if(session('role_status'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('role_status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="dt_table" class="table row-border hover dataTables_scrollBody" style="width:100%">
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
</div>
@stop

@section('pages_specific_scripts')

<style>
    /* MAKE LEFT COLUMN FIXEZ */
    thead th:nth-child(1),
    .sorting_1 {
        left: 0 !important;
        z-index: 1 !important;
        position: -webkit-sticky !important;
        position: sticky !important;
        left: 0 !important;
        /* background-color: #c1c1c1 !important; */
        width: 100% !important;
        min-width: 10rem !important;
    }
    tr.odd td:hover {
        background-color: #c2f4f5 !important;
    }
    tr.even td:hover {
        background-color: #c2f4f5 !important;
    }

    /* Change the color of the form-check-input when checked */
    .form-check-input-not-central:checked {
        background-color: #dc3545; /* Bootstrap's danger color */
        border-color: #dc3545;
    }

    /* Optional: Change the color of the form-check-input's focus shadow */
    .form-check-input-not-central:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
</style>
<script>
    let table_role;

    function togglePermission(role_id, permission_id) {
        var value = $('#r'+role_id+'-p'+permission_id).prop('checked');
        let data = {
            role_id: role_id,
            permission_id: permission_id,
            value: value
        };
        sweetAlertLoading();
        let text = 'Removed Access';
        let color = 'warning';
        if(value == 'true' || value == true || value == 1) {
            text = 'Added Access';
            color = 'success';
        }
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/admin/rbac/update_permission",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {
                
                table_role.ajax.reload(null, false);
                console.log("result",data)
                // sweetAlert2(data.status, data.message);
                Swal.close();
                $('#alert-message-for-rbac-updates').append(`<div id="alert-${data.data.id}" class="alert alert-${color} alert-dismissible shadow fade show py-2 mb-2 me-2" style="max-width: 500px;">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-gray"><i class='bx bxs-check-circle'></i>
                        </div>
                        <div class="ms-3">
                            <div><b>${text}</b> to ${data.permission} for <b>${data.data.display_name}</b></div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
                setTimeout(function() {
                    $(`#alert-${data.data.id}`).alert('close');
                }, 2500);
                
            },error: function(msg) {
                //general error
                console.log("Error");
                console.log(msg);
                // $.each(msg.responseJSON.errors,function (key , val){
                //     sweetAlert2('warning', 'Check field inputs.');
                //     $("#"+key ).after( '<span class="error_txt" style="color:red; text-indent:15px;">'+val[0]+'</span>' );
                //     console.log(key);
                // });
            }
        });
    }

    $(document).ready(function() {

        let rolesCol;
        let data = {};
        let roleHeaders = [
            // { data: 'id', name: 'id', title: 'ID' },
            { data: 'display_name', name: 'display_name', title: 'Name' },
            // { data: 'actions', name: 'actions', title: 'Action' , orderable: false, searchable: false},
        ];

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/admin/rbac/get_roles",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {
                rolesCol = data.data;
                $.each(rolesCol, function(index, value){
                    const r = {
                        data: 'r-'+index, name: 'r-'+index, title: value
                    };
                    roleHeaders.push(r);
                });

                data = {};
                const permission_table = $('#dt_table').DataTable({
                    scrollX: true,
                    serverSide: true,
                    stateSave: true,
                    pageLength: 50,
                    dom: 'fBtip',
                    buttons: [],
                    searching: true,
                    fixedColumns: true,
                    ajax: {
                        url: "/admin/rbac/data",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function (data) {
                            data.search = $('input[type="search"]').val();
                            data.division = $('#division').val();
                            data.group = $('#group').val();
                        }
                    },
                    columns: roleHeaders,
                    // "createdRow": function( row, data, dataIndex ) {
                    //     if ( data['actions'] == "create" ) { 
                    //         console.log("row",row)       
                    //         // $(row).addClass('cred');
                    //         $(row).css('background-color', '#000000');
                    //     }
                    // },
                    initComplete: function( settings, json ) {
                        selected_len = permission_table.page.len();
                        $('#length_change').append($('<option>', {value: 5,text: 'Show 5',selected:(selected_len == "5") ?true: false}));
                        $('#length_change').append($('<option>', {value: 10,text: 'Show 10',selected:(selected_len == "10") ?true: false}));
                        $('#length_change').append($('<option>', {value: 50,text: 'Show 50',selected:(selected_len == "50") ?true: false}));
                        $('#length_change').append($('<option>', {value: 100,text: 'Show 100',selected:(selected_len == "100") ?true: false}));
                        $('#length_change').append($('<option>', {value: 1000,text: 'Show 1000',selected:(selected_len == "1000") ?true: false}));
                    }

                });

                table_role = permission_table;
                table_role.buttons().container().appendTo( '.dt-card-header' );
                $('#search_input').val(table_role.search());
                $('#search_input').keyup(function(){ table_role.search($(this).val()).draw() ; })
                $('#length_change').change( function() { table_role.page.len($(this).val()).draw() });
                $('#division').change( function() { table_role.draw() ; });
                $('#group').change( function() { table_role.draw() ; });
            }
        });

        $('#dt_table tbody').on('mouseenter', 'td', function() {
            // Change background color of all sibling td elements
            $(this).siblings().css('background-color', '#f5f5f5');
            $(this).closest('tr').find('td:first-child').css('background-color', '#f5f5f5 !important');
        }).on('mouseleave', 'td', function() {
            // Revert background color of all sibling td elements
            $(this).siblings().css('background-color', '');
            $(this).closest('tr').find('td:first-child').css('background-color', '');
        });

    });    

</script>
@stop