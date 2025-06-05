<div class="modal" id="change_order_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Facility Category Change Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                <form action="" method="POST" id="#change_order_form" onsubmit="return false;">
                    <div class="col-md-12">
                        <input type="hidden" id="id" value="0">
                        
                        <div class="row g-4">

                            <!-- details info start -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @include('general/body/hospitality/facilityCategories/modals/changeOrder/partials/details')
                            </div>
                           
                        </div>
                    </div>
                </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="cValidateForm()"></i> Update</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function cValidateForm(reform = false)
    {
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#change_order_modal input, #change_order_modal textarea, #change_order_modal select').each(function() {
            
            data[this.id] = this.value;

        });

        if(flag) {
            let modal= "#change_order_modal";
            console.log(data);
            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/facility-categories/order", "PUT");
        }
    }

  </script>
