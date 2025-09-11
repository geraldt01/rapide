<!-- Edit User Modal -->
<div class="modal fade" id="createCashBalance" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Create Daily Cash Balance <span id="car-plate-number" class="text-primary mb-0"></span></h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="editCashBalanceForm" class="row g-4" onsubmit="return false">
         
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash1000" name="modaladdCash1000" class="form-control" />
              <label for="modaladdCash1000">1,000</label>
            </div>
          </div>
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash500" name="modaladdCash500" class="form-control"/>
              <label for="modaladdCash500">500</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash200" name="modaladdCash200" class="form-control" />
              <label for="modaladdCash200">200</label>
            </div>
          </div>
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash100" name="modaladdCash100" class="form-control" />
              <label for="modaladdCash100">100</label>
            </div>
          </div>
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash50" name="modaladdCash50" class="form-control"/>
              <label for="modaladdCash50">50</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash20" name="modaladdCash20" class="form-control"/>
              <label for="modaladdCash20">20</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash10" name="modaladdCash10" class="form-control"  />
              <label for="modaladdCash10">10</label>
            </div>
          </div>
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdCash5" name="modaladdCash5" class="form-control" />
              <label for="modaladdCash5">5</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdLooseCoins" name="modaladdLooseCoins" class="form-control"/>
              <label for="modaladdLooseCoins">LOOSE COINS</label>
            </div>
          </div>
           <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="number" id="modaladdLooseChange" name="modaladdLooseChange" class="form-control" value="2500"/>
              <label for="modaladdLooseChange">CHANGE, ADMIN DRAWER</label>
            </div>
          </div>
           
          <!-- <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modaladdYearModel" name="modaladdYearModel" class="form-control" placeholder="Year Model" />
              <label for="modaladdYearModel">Year Model</label>
            </div>
          </div> -->
         
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 btn-submit" onclick="addNewCashBalance()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>