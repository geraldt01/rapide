<!-- Edit User Modal -->
<div class="modal fade" id="editCustomerInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Edit Customer</h3>
        </div>
        <form id="editCustomerInfoForm" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden-customer-id-edit" id="hidden-customer-id-edit" value="{{$customerInfo->owner_id}}" />

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditCustomerName" name="modalEditCustomerName" class="form-control" />
              <label for="modalEditCustomerName">Customer Name</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditContact" name="modalEditContact" class="form-control" />
              <label for="modalEditContact">Contact</label>
            </div>
          </div>
            <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditAddress" name="modalEditAddress" class="form-control" />
              <label for="modalEditAddress">Address</label>
            </div>
          </div>
          
      
          <div class="col-12 text-center">
            <button type="reset" class="btn btn-primary me-sm-3 me-1" onclick="editCustomerInfo()">Update</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit User Modal -->
