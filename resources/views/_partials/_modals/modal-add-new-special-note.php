<!-- Edit User Modal -->
<div class="modal fade" id="addNewSpecialNote" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Editing</span> Special Note </h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formSpecialNote" class="row g-4" onsubmit="return false">
         
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id="modalNote" name="modalNote" class="form-control" placeholder="Note" />
              <label for="modaladdPlateNumber">Note</label>
            </div>
          </div>
           
         
          <div class="col-12 text-center">
            <button type="button" class="btn btn-primary me-sm-3 me-1" id="btn-save-note" onclick="saveSpecialNote()">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Delete Note Modal -->
<div class="modal fade" id="deleteSpecialNote" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2"><span id="label-action">Deleting</span> Special Note </h3>
          <!-- <p class="pt-1">Updating user details will receive a privacy audit.</p> -->
        </div>
        <form id="formSpecialNote" class="row g-4" onsubmit="return false">
          <input type="hidden" name="hidden_note_id" id="hidden-note-id" />
          <div class="col-12 col-md-12">
            <h6 class="text-center">Are you sure you want to delete this note?</h6>
          </div>
          <div class="col-12 text-center">
            <button type="button" class="btn btn-outline-danger delete-customer waves-effect" onclick="deleteNote()">Delete Note</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>