<!-- Edit User Modal -->
<div class="modal fade" id="addNewCar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Add New Car  <span id="car-plate-number" class="text-primary mb-0"></span></h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="editUserForm" class="row g-4" onsubmit="return false">
         
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modaladdPlateNumber" name="modaladdPlateNumber" class="form-control" placeholder="PlateNumber" />
              <label for="modaladdPlateNumber">Plate Number</label>
            </div>
          </div>
              <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
               <select id="modaladdManufacturer" class="select2 form-select" name="modaladdManufacturer" data-placeholder="Select Manufacturer">
              </select>
              <label for="modaladdManufacturer">Vehicle Manufacturer </label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modaladdVehicleModel" name="modaladdVehicleModel" class="form-control" placeholder="Vehicle Model" />
              <label for="modaladdVehicleModel">Vehicle Model</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
           <div class="form-floating form-floating-outline">
            <input type="number" class="form-control" id="modaladdYearModel" placeholder="Year Model" name="modaladdYearModel" aria-label="Year Model">
            <label for="modaladdYearModel">Year Model</label>
          </div>
          </div>
           <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="modaladdTransmission" class="select2 form-select" name="modaladdTransmission" data-placeholder="Select Transmission">
                  <option value="">Select Transmission</option>
                  <option value="AT">Automatic</option>
                  <option value="MT">Manual</option>
              </select>
              <label for="Transmission">Transmission</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select id="modaladdFuelType" class="select2 form-select" name="modaladdFuelType" data-placeholder="Select Fuel Type">
                <option value="">Select Fuel Type</option>
                <option value="Diesel">Diesel</option>
                <option value="Gas">Gas</option>
              </select>
              <label for="Fuel Type">Fuel Type</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modaladdMileage" name="modaladdMileage" class="form-control" placeholder="Mileage" />
              <label for="modaladdMileage">Mileage</label>
            </div>
          </div>
          <!-- <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modaladdYearModel" name="modaladdYearModel" class="form-control" placeholder="Year Model" />
              <label for="modaladdYearModel">Year Model</label>
            </div>
          </div> -->
         
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1" onclick="addNewCar()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>