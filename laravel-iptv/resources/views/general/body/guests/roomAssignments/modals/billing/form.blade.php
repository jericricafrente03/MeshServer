<div class="modal" id="billing_modal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">

        <div class="modal-header py-1">
          <h5 class="modal-title">Guest Billing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          
            <div class="card">
                <div class="card-header dt-card-header-billing">
                    <select name='length_change' id='length_change_billing' class="table_length_change form-select">
                    </select>
                    <input type="text" id="search_input_billing" class="table_search_input form-control" placeholder="Search...">

                    <button class="btn btn-primary dropdown-toggle ms-2" id="statusAllDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">Status</button>
                    <ul class="dropdown-menu">
                        @foreach ($statusDropdown as $status)
                            <li><a class="dropdown-item mass-status-dropdown" data-value="{{$status->name}}" data-id="{{$status->id}}" href="javascript:void(0)"  class="me-1"><i class="fa-solid fa-caret-right" style="color: {{$status->bg_color}};"></i> {{$status->name}}</a></li>
                        @endforeach
                    </ul>

                    <button class="btn btn-warning dropdown-toggle" id="paymentAllDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">Payment</button>
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
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <!-- <th style="text-align:right; font-size: 15px;" >Total Balance:</th> -->
                                    <th style="font-size: 15px;" >Total Balance:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
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

 
