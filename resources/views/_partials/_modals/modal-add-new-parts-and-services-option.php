<!-- Edit User Modal -->
<div class="modal fade" id="addNewPartsAndServicesOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Editing</span> Parts and Services </h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formPartsAndServicesOption" class="row g-4" onsubmit="return false">
         
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalPartsAndServices" name="modalPartsAndServices" class="form-control" placeholder="Parts and Services" />
              <label for="modaladdPlateNumber">Parts and Services</label>
            </div>
          </div>
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalCost" name="modalCost" class="form-control" placeholder="Cost" />
              <label for="modalCost">Cost</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalPrice" name="modalPrice" class="form-control" placeholder="price" />
              <label for="modalPrice">Price</label>
            </div>
          </div>
         
          <div class="col-12 text-center">
            <button type="button" class="btn btn-primary me-sm-3 me-1" id="btn-save-parts-and-services" onclick="savePartsAndServices()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Delete Parts and Services Modal -->
<div class="modal fade" id="deletePartsAndServicesOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Deleting</span> Parts and Services </h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formPartsAndServicesOption" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden_vehicle_type_id" id="hidden-parts-and-services-id" />
          <div class="col-12 col-md-12">
            <h6 class="text-center">Are you sure you want to delete this Parts and Services?</h6>
          </div>
          <div class="col-12 text-center">
            <button type="button" class="btn btn-outline-danger delete-customer waves-effect" onclick="deletePartsAndServices()">Delete Parts and Services</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>