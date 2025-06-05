<div class="modal" id="theme_applications_modal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Theme Applications</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="card">
                <div class="card-header dt-card-header-themeapplications">
                    <select name='length_change' id='length_change_themeapplications' class="table_length_change form-select">
                    </select>
                    <input type="text" id="search_input_themeapplications" class="table_search_input form-control" placeholder="Search...">

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item mass-payment-dropdown" data-value="1" href="javascript:void(0)"  class="me-1"><i class="fa-solid fa-caret-right"></i> PAID</a></li>
                        <li><a class="dropdown-item mass-payment-dropdown" data-value="0" href="javascript:void(0)"  class="me-1"><i class="fa-solid fa-caret-right"></i> PENDING</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table row-border table-hover" style="width:100%">
                            <thead></thead>
                            <tbody>                                   
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer py-0">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

 
