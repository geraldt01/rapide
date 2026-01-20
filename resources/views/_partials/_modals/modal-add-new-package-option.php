<!-- Edit User Modal -->
<div class="modal fade" id="addNewPackageOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Editing</span> Package </h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formPackageOption" class="row g-4" onsubmit="return false">
         
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalPackage" name="modalPackage" class="form-control" placeholder="Package" />
              <label for="modaladdPlateNumber">Package</label>
            </div>
          </div>
           
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modalPrice" name="modalPrice" class="form-control" placeholder="Price" />
              <label for="modaladdPrice">Price</label>
            </div>
          </div>
         
          <div class="col-12 text-center">
            <button type="button" class="btn btn-primary me-sm-3 me-1" id="btn-save-package" onclick="savePackage()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Add ackage Sub Item Modal -->
<div class="modal fade" id="addNewPackageSubItemOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action-sub">Adding Package Sub Item for </span><b><span id="package-name"></span></b></h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formPackageSubItemOption" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden_package_id" id="hidden-sub-package-id" />
          <input type="hidden" name="hidden_package_part_id" id="hidden-package-part-id" />
          
           <input type="hidden" id="package_details" name="package_details" class="form-control" placeholder="Item Details" />
          <div class="col-12 col-md-12">
            <div class=" row w-100  p-2" id="item-list-package-"   data-repeater-item>
              <select id="display-package-option-3" name="package-option" class="select2" data-allow-clear="true"  style="width: 0px;" onchange="populatePartDetails()">
              </select>
            </div>
          </div>
           
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="package_qty" name="package_qty" class="form-control" placeholder="Quantity"  value="1" onchange="computeTotalCost();computeSellPrice()" />
              <label for="modaladdPrice">Quantity</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="package_part_number" name="package_part_number" class="form-control" placeholder="Part Number" />
              <label for="modaladdPrice">Part Number</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="supplier" name="supplier" class="form-control" placeholder="Supplier" />
              <label for="modaladdPrice">Supplier</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="supplier_inv" name="supplier_inv" class="form-control" placeholder="Supplier Inv" />
              <label for="modaladdPrice">Supplier Inv</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="package_unit_cost" name="package_unit_cost" class="form-control" placeholder="Unit Cost" value="0"  onchange="computeTotalCost()"/>
              <label for="modaladdPrice">Unit Cost</label>
            </div>
          </div>


           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="package_total_cost" name="package_total_cost" class="form-control" placeholder="Unit Total Cost" />
              <label for="modaladdPrice">Unit Total Cost</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="package_unit_selling_price_with_labor" name="package_unit_selling_price_with_labor" class="form-control" placeholder="Price" onchange="computeSellPrice()"/>
              <label for="modaladdPrice">Price</label>
            </div>
          </div>

           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="package_sell_price" name="package_sell_price" class="form-control" placeholder="Sell Price"/>
              <label for="modaladdPrice">Sell Price</label>
            </div>
          </div>


         
          <div class="col-12 text-center">
            <button type="button" class="btn btn-primary me-sm-3 me-1" id="btn-save-package-sub-item" onclick="savePackageSubItem()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>





<!-- Delete Package Modal -->
<div class="modal fade" id="deletePackageOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Deleting</span> Package </h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formPackageOption" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden_vehicle_type_id" id="hidden-package-id" />
          <div class="col-12 col-md-12">
            <h6 class="text-center">Are you sure you want to delete this Package?</h6>
          </div>
          <div class="col-12 text-center">
            <button type="button" class="btn btn-outline-danger delete-customer waves-effect" onclick="deletePackage()">Delete Package</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Delete Package Modal -->
<div class="modal fade" id="deletePackageSubitemOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Deleting</span> Package Sub Item</h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formPackageSubItemOption" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden_vehicle_type_id" id="hidden-package-sub-item-id" />
          <div class="col-12 col-md-12">
            <h6 class="text-center">Are you sure you want to delete this Package?</h6>
          </div>
          <div class="col-12 text-center">
            <button type="button" class="btn btn-outline-danger delete-customer waves-effect" onclick="deletePackageSubItem()">Delete Package</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>