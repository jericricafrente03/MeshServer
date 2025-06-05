<div class="modal modal-md" style="display:none;" id="delete_modal" tabindex="-1">
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
        <button type="button" class="btn btn-danger" id="ok_btn" onclick="DeleteOrder()">DELETE</button>
      </div>
    </div>
  </div>
</div>




<script>
    function ShowConfirmDeleteForm(id) {
      let modal = '#delete_modal';
      $(modal).modal('show');
      $('#delete_modal #id').val(id);
      $(modal).on('hidden.bs.modal', function(){
        // Remove focus from the currently focused element inside modal
        $(document.activeElement).blur();
      });
    }


    function DeleteOrder(reform=false) {
        var data = {};
        let modal = "#delete_modal";
        data.id = $('#delete_modal #id').val();
        
        inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/service-requests/delete", "DELETE");
    }

</script>
