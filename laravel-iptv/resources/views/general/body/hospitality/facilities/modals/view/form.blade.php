<div class="modal" id="view_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">View Facilities</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="id" value="0">
                        
                        <div class="row g-4">

                            <!-- details info start -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @include('general/body/hospitality/facilities/modals/view/partials/details')
                            </div>
                           
                        </div>
                    </div>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
          @can('facilities.edit')
            <button type="button" class="btn btn-sm btn-primary" id="edit_btn" onclick="switchToEditModal()"></i> Edit</button>
          @endcan
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function switchToEditModal()
    {
      let modal = '#view_modal';
      let data = $(modal + ' #id').data('data-info');
      showEditModal($(modal + ' #id').val(), data);
      $(modal).modal('hide');
    }

  </script>
