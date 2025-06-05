<div class="modal" id="view_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">View Emergency Message</h5>
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
                                @include('general/body/messages/broadcastMessages/tickers/modals/view/partials/details')
                            </div>
                           
                        </div>
                    </div>
            
                </div><!--end row-->
            </div>
            
        </div>
        
      </div>
    </div>
  </div>
