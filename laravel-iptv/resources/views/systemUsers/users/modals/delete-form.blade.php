<div class="modal modal-md" style="display:none;" id="delete_form_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-red">Warning! Delete confirmation.</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
        <form name="delete_form"  id="delete_form"  >
          <input id="id" type="hidden" />
        </form>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="delete_btn" onclick="DeleteOrder()">DELETE</button>
      </div>
    </div>
  </div>
</div>




<script>
    function ShowConfirmDeleteForm(id) {
      let modal = '#delete_form_modal';
      $(modal).modal('show');
      $('#delete_form_modal #id').val(id);
      $(modal).on('hidden.bs.modal', function(){
          // Remove focus from the currently focused element inside modal
          $(document.activeElement).blur();
      });
    }


    function DeleteOrder() {
        var data = {};
            $("#delete_btn").val('Deleting... please wait!').attr('disabled', 'disabled');
          data.id = $('#delete_form_modal #id').val();
        
        //console.log(data);
        sweetAlertLoading();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/admin/user/delete_user",
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
                $('#delete_form_modal').modal('toggle');
                table_user.ajax.reload(null, false);
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
