<!-- Add New Address Modal -->
<div class="modal fade" id="addNewJobOrderUpdate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-simple modal-add-new-address">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body p-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="address-title mb-2 pb-1">Update this to <span id="upgrade-status"></span> </h3>
          <p class="address-subtitle">Are you sdure you want to change do this action?</p>
        </div>
        <form id="addNewAddressForm" class="row g-4" onsubmit="return false">

          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1" onclick="doChangeStatus()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add New Address Modal -->
