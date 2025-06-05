<div class="modal modal-md" style="display:none;" id="delete_store_folder_modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-red">Warning! Delete confirmation.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form name="delete_form"  id="delete_form"  >
                <p>You are about to DELETE a Folder record. This procedure is irreversible. </p>
                <p id="title_folder_id_text"><p>
                <input id="id" type="hidden" />
                <input id="pharmacy_store_id" type="hidden" />
                <p>This will delete the following:</p>
                <ul>
                    <li>Folder File Records</li>
                </ul>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="delete_btn" onclick="saveDeleteFolder()">DELETE</button>
        </div>
      </div>
    </div>
  </div>
  
  
  
  
<script>
    function clickDeleteFolderBtn(event, folder, pharmacy_store_id = null) {
        event.preventDefault();
        $('#delete_store_folder_modal').modal('show');
        $('#delete_store_folder_modal #id').val(folder.id);
        $('#delete_store_folder_modal #pharmacy_store_id').val(pharmacy_store_id);
        $('#delete_store_folder_modal #title_folder_id_text').html(`ID: <b>#${folder.id}</b>`);
        $('#delete_store_folder_modal #delete_form ul').html(`
            <li><b class="text-danger">${folder.name}</b> Folder</li>
            <li><b class="text-danger">${folder.files.length}</b> File(s)</li>
        `);
    }
  
  
    function saveDeleteFolder() {
        var data = {};
        $("#delete_btn").val('Deleting... please wait!').attr('disabled', 'disabled');
        data.id = $('#delete_store_folder_modal #id').val();
        data.pharmacy_store_id = $('#delete_store_folder_modal #pharmacy_store_id').val();

        //console.log(data);
        sweetAlertLoading();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "DELETE",
            url: "/admin/store-folders/delete",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(msg) {
            //success
                Swal.fire({
                    position: 'center',
                    icon: msg.status,
                    title: msg.message,
                    showConfirmButton: false,
                    timer: 4000
                });
                $("#delete_btn").val('DELETE').removeAttr('disabled');
                $('#delete_store_folder_modal').modal('toggle');
                
                window.location.reload(true);
            },error: function(msg) {
                if(msg.status == 403) {
                    sweetAlert2('warning', "403 Forbidden: You don't have permission to do this action");
                }
                $("#delete_btn").val('DELETE').removeAttr('disabled');
                console.log(msg.responseText);
            }

        });
          
    }
  
</script>
  