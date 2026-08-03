<!-- Edit User Modal -->
<div class="modal fade" id="editCustomerInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Edit Customer</h3>
        </div>
        <form id="editCustomerInfoForm" class="row g-4" autocomplete="off" onsubmit="return false">
          <input type="hidden" name="hidden-customer-id-edit" id="hidden-customer-id-edit" value="{{$customerInfo->owner_id}}" />

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditCustomerName" name="modalEditCustomerName" class="form-control" autocomplete="off" />
              <label for="modalEditCustomerName">Customer Name</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditContact" name="modalEditContact" class="form-control" autocomplete="off" />
              <label for="modalEditContact">Contact</label>
            </div>
          </div>
            <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalEditAddress" name="modalEditAddress" class="form-control" autocomplete="off" />
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




<!-- Edit Car Modal -->
<div class="modal fade" id="editCarInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Editing {{$firstCar->plate_number}}</h3>
        </div>
        <form id="editCarInfoForm" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden-selected-car-id" id="hidden-selected-car-id" value="{{$customerInfo->car_id}}" />
          <!-- Manufacturer -->
          <div class="mb-3 col ecommerce-select2-dropdown">
            <div class="form-floating form-floating-outline">
              <select id="select-manufacturer" class="select2 form-select" name="manufacturer" data-placeholder="Select Manufacturer">
                <option value="">Select Manufacturer</option>
                @foreach($carManufacturerOptions as $carOption)
                  @if($carOption->value == $firstCar->manufacturer) 
                  <option value="{{$carOption->value}}" selected>{{$carOption->value}}</option>
                  @else
                  <option value="{{$carOption->value}}" >{{$carOption->value}}</option>
                  @endif
                @endforeach
              </select>
              <label for="Manufacturer">Manufacturer</label>
            </div>
          </div>
           <!-- Vehicle Model -->
           <div class="form-floating form-floating-outline mb-4">
            <input type="text" class="form-control" id="ecommerce-vehicle-model" placeholder="Vehicle Model"  value="{{$firstCar->vehicle_model}}" name="vehicleModel" aria-label="Vehicle Model">
            <label for="ecommerce-plate-number">Vehicle Model</label>
          </div>
               <!-- Vehicle Model -->
           <div class="form-floating form-floating-outline mb-4">
            <input type="number" class="form-control" id="ecommerce-year-model" placeholder="Year Model" name="yearModel" value="{{$firstCar->year}}" aria-label="Year Model">
            <label for="ecommerce-plate-number">Year Model</label>
          </div>

          <!-- Transmission -->
          <div class="mb-3 col ecommerce-select2-dropdown">
            <div class="form-floating form-floating-outline">
              <select id="select-tansmission" class="select2 form-select" name="transmission" data-placeholder="Select Transmission">
                <option value="">Select Transmission</option>
                  @if($firstCar->transmission == "AT") 
                   <option value="AT" selected>Automatic</option>
                   <option value="MT">Manual</option>
                  @else
                  <option value="AT">Automatic</option>
                   <option value="MT" selected>Manual</option>
                  @endif

                
              </select>
              <label for="Transmission">Transmission</label>
            </div>
          </div>


        <!-- Fuel Type -->
          <div class="mb-3 col ecommerce-select2-dropdown">
            <div class="form-floating form-floating-outline">
              <select id="select-fuel-type" class="select2 form-select" name="fuelType" data-placeholder="Select Fuel Type">
                  @if($firstCar->fuel_type == "Diesel") 
                   <option value="Diesel" selected>Diesel</option>
                   <option value="Gas">Gas</option>
                  @else
                   <option value="Diesel" >Diesel</option>
                  <option value="Gas" selected>Gas</option>
                  @endif
              </select>
              <label for="Fuel Type">Fuel Type </label>
            </div>
          </div>


          <!-- Plate Number -->
           <div class="form-floating form-floating-outline mb-4">
            <input type="text" class="form-control" id="ecommerce-plate-number" name="plateNumber" max="10" placeholder="Plate Number"  value="{{$firstCar->plate_number}}" aria-label="Plate Number">
            <label for="ecommerce-plate-number">Plate Number</label>
          </div>

           <!-- MIleage -->
           <div class="form-floating form-floating-outline mb-4 d-flex">
            <input type="text" class="form-control" id="ecommerce-mileage" name="mileage" value="{{$firstCar->mileage}}" placeholder="Mileage"  aria-label="Mileage">
            <label for="ecommerce-mileage">Mileage</label>
              <span class="p-2 mt-1">KMS</span>
          </div>
        </div>
   





          <div class="col-12 text-center">
            <button type="reset" class="btn btn-primary me-sm-3 me-1" onclick="editCarInfo()">Update</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Car Modal -->

<script>

      document.addEventListener('DOMContentLoaded', function() {
    const numberInput = document.getElementById('ecommerce-mileage'); // Replace with your input's ID
    numberInput.addEventListener('change', function(event) {

      // Get the current value and remove existing commas
      const options = {
        minimumFractionDigits: 2, // Ensures at least two decimal places
        maximumFractionDigits: 2, // Limits to a maximum of two decimal places
        style: 'decimal'          // Specifies decimal formatting
      };
      var inputValue = event.target.value;
      const finalNum =  parseInt(inputValue).toLocaleString(undefined, options);
        document.getElementById("ecommerce-mileage").value = finalNum.replace(".00", "");
    });
 
 
  });


  </script>