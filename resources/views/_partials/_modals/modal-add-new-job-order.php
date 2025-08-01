<!-- Edit User Modal -->
<div class="modal fade" id="addNewJobOrder" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Add Estimate for <span id="car-plate-number" class="text-primary mb-0"></span></h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="editUserForm" class="row g-4" onsubmit="return false">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <!-- <input type="text" id="bs-rangepicker-single" name="modalDate" class="form-control" /> -->
                <input type="date"  id="bs-rangepicker-single" class="form-control invoice-date" placeholder="<?php echo date("m/d/Y"); ?>" value="<?php echo date("m/d/Y"); ?>"/>
              <label for="bs-rangepicker-single">Date</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEst" name="modalEst" class="form-control" placeholder="EST#" value="0000"/>
              <label for="modalEst">EST#</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalPlateNumber" name="modalPlateNumber" class="form-control" placeholder="PlateNumber" disabled/>
              <label for="modalPlateNumber">Plate Number</label>
            </div>
          </div>
              <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalManufacturer" name="modalManufacturer" class="form-control" placeholder="Vehicle Manufacturer" />
              <label for="modalManufacturer">Vehicle Manufacturer </label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalVehicleModel" name="modalVehicleModel" class="form-control" placeholder="Vehicle Model" />
              <label for="modalVehicleModel">Vehicle Model</label>
            </div>
          </div>
           <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <!-- <select id="modalVehicleType" class="select2 form-select" name="modalVehicleType" data-placeholder="Select Manufacturer">
                <option value="">Select Vehicle Type</option>
                <option value="sedan">Sedan</option>
                <option value="suv">SUV</option>
                <option value="hatchback">Hatchback</option>
                <option value="pickup">Pickup</option>
                <option value="mpv">MPV</option>
                <option value="others">Others</option>
              </select> -->


         <div class="form-floating form-floating-outline">
              <input type="text" id="modalVehicleType" name="modalVehicleType" class="form-control"  />
              <label for="modalManufacturer">Vehicle Type </label>
            </div>



            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalMileage" name="modalMileage" class="form-control" placeholder="Mileage" />
              <label for="modalMileage">Mileage</label>
            </div>
          </div>
           <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalYearModel" name="modalYearModel" class="form-control" placeholder="modalYearModel" />
              <label for="modalYearModel">Year Model</label>
            </div>
          </div>
          <!-- <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="modalStatus" name="modalStatus" class="form-select" aria-label="Default select example">
                <option selected>Status</option>
                <option value="estimate" selected>Estimate</option>
              
              </select>
              <label for="modalStatus">Status</label>
            </div>
          </div> -->
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1" onclick="saveJobOrder()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>