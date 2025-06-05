<div class="modal modal-md" style="display:none;" id="delete_store_document_modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-red">Warning! Delete confirmation.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
              <form name="delete_form"  id="delete_form"  >
                    <p>You are about to remove a Document record from document. This procedure is irreversible. </p>
                    <p id="title_document_id_text" class="fw-bold"><p>
                    <input id="reload" value="false" type="hidden" />
                    <input id="id" type="hidden" />
                    <p>This will delete the following:</p>
                    <ul>
                        <li>Document Record</li>
                    </ul>
                   
             </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="delete_btn" onclick="saveDeleteDocument()">DELETE</button>
        </div>
      </div>
    </div>
  </div>
  
  
  
  
  <script>
      function clickDeleteDocumentBtn(event, id) {
          event.preventDefault();
          $('#delete_store_document_modal').modal('show');
          $('#delete_store_document_modal #id').val(id);
          $('#title_document_id_text').html('Document: '+id);
      }
  
  
      function saveDeleteDocument() {
          var data = {};
              $("#delete_btn").val('Deleting... please wait!').attr('disabled', 'disabled');
            data.id = $('#delete_store_document_modal #id').val();
          
          //console.log(data);
          sweetAlertLoading();
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type: "DELETE",
              url: "/admin/store-document/delete",
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
                  $('#delete_store_document_modal').modal('toggle');
                  
                    // table_document.ajax.reload(null, false);
                    reloadDocumentDataTable($('#delete_store_document_modal #id').val());
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
  