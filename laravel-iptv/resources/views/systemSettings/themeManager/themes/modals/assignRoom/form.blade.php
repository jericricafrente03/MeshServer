<div class="modal" id="assign_room_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Assign Room</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="form-body">
                <div class="row">
                    
                <form id="edit_form" onsubmit="return false;">
                    <div class="col-md-12">
                        <input type="hidden" id="id" value="0">
                        
                        <div class="row g-4">

                            <!-- details info start -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                @include('systemSettings/themeManager/themes/modals/assignRoom/partials/details')
                            </div>
                           
                        </div>
                    </div>
                </form>
            
                </div><!--end row-->
            </div>
            
        </div>

        <!-- footer starts -->
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm btn-primary" id="ok_btn" onclick="uAssignRoomValidateForm()"></i> Update</button>
        </div>
        <!-- footer end -->
        
      </div>
    </div>
  </div>

  <script>
    function uAssignRoomValidateForm(reform = false)
    {
        let data = {};
        let flag = true;

        $('.error_txt').remove();
        $(".form-control").removeClass("is-invalid");

        $('#assign_room_modal input, #assign_room_modal textarea, #assign_room_modal select').each(function() {
            data[this.id] = this.value;
        });
        

        if(flag) {
            let modal= "#assign_room_modal";
           
            let roomIds = $(modal + ' #duallistbox').val(); // Get selected values as an array
            // Add room IDs directly to the `data` object
            if (roomIds) {
                data['room_id'] = roomIds; // Add the array to the data object under 'room_id'
            }

            inFooterScriptAjaxCreateUpdateSubmit(data, reform, modal, "/themes/assign-room", "PUT");
        }

        function validatorForSelect2(response, modal){
            sweetAlert2('warning', 'Check field inputs.');
            $.each(response, function (key, val) {
                switch (key) {
                    case 'video_uri':
                        $(modal + " #" + 'videoValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                        $(modal + " #" +key).addClass("is-invalid");
                        break;
                    case 'room_id':
                        $(modal + " #" + 'roomValidation').after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                        $(modal + " #" +key).addClass("is-invalid");
                        break;
                    default:
                        $(modal + " #" + key).after('<span class="error_txt" style="color:red; text-indent:15px;">' + val[0] + '</span>');
                        $(modal + " #" +key).addClass("is-invalid");
                        break;
                }
            });
        }

    }

  </script>
