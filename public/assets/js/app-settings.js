/**
 * App eCommerce Settings Script
 */
'use strict';

//Javascript to handle the e-commerce settings page

$(function () {
  // Select2
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') // for dynamic placeholder
      });
    });
  }
});

(function () {
  // Phone Number
  const phoneMaskList = document.querySelectorAll('.phone-mask');

  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
})();

function editNote(note_id) {
  if(note_id) {
  $.ajax({
    type: "get",
    url: '/app/special-note/get/'+ note_id,
      data:  $("#formSpecialNote").serialize(),
        success: function (result) {
          document.getElementById('modalNote').value = result.SpecialNoteData.value;
        document.getElementById('btn-save-note').setAttribute('onclick', 'saveSpecialNote('+result.SpecialNoteData.id+')');
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  } else {
  }

}
function showNotes() {
 $.ajax({
    type: "get",
    url: '/app/special-note/show/',
      data:  $("").serialize(),
      success: function (result) {
        $('#addNewSpecialNote').modal('hide');
        $("#tbl-note-body").html(result.specianNoteHtml);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
}

function saveSpecialNote(note_id) {
  if(note_id) {
  $.ajax({
    type: "get",
    url: '/app/special-note/update/'+ note_id,
      data:  $("#formSpecialNote").serialize(),
      success: function (result) {
        $('#addNewSpecialNote').modal('hide');
          showNotes();
          $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
            }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  } else {
     $.ajax({
      type: "get",
     url: '/app/special-note/save/',
       data:  $("#formSpecialNote").serialize(),
      success: function (result) {
          $('#addNewSpecialNote').modal('hide');
          showNotes();
             $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
            }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }
}


    
  function createNote() {
       document.getElementById('modalNote').value = "";
       $("#label-action").html("Adding New");
  }

  function promptDeleteNote(note_id) {
          $('#deleteSpecialNote').modal('show');
           document.getElementById('hidden-note-id').value = note_id;
  }

  function deleteNote(note_id) {
     var note_id =  document.getElementById('hidden-note-id').value;
    $.ajax({
      type: "get",
     url: '/app/special-note/delete/'+note_id,
       data:  $("#formSpecialNote").serialize(),
      success: function (result) {
          showNotes();
          $('#deleteSpecialNote').modal('hide');
            $(".alert-danger h4").html('<i class="mdi mdi-check-circle-outline mdi-24px me-2"><?i>Deleted!');
            $(".alert-danger p").html(result.message);
          $(".alert-danger").removeClass("d-none");
          setTimeout(function(){ 
            $(".alert-danger").addClass("d-none");
          }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }





  
// Manufacturer

function editManufacturer(manufacturer_id) {
  if(manufacturer_id) {
  $.ajax({
    type: "get",
    url: '/app/car-manufacturer/get/'+ manufacturer_id,
      data:  $("#formManufacturerOption").serialize(),
        success: function (result) {
          document.getElementById('modalManufacturer').value = result.ManufacturerData.value;
        document.getElementById('btn-save-manufacturer').setAttribute('onclick', 'saveManufacturer('+result.ManufacturerData.id+')');
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  } else {
  }

}
function showManufacturers() {
 $.ajax({
    type: "get",
    url: '/app/car-manufacturer/show/',
      data:  $("").serialize(),
      success: function (result) {
          $('#addNewManufacturerOption').modal('hide');
          $("#tbl-manufacturer-body").html(result.manufacturerHtml);
          console.log(result.manufacturerHtml);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
}

function saveManufacturer(manufacturer_id) {
  if(manufacturer_id) {
  $.ajax({
    type: "get",
    url: '/app/car-manufacturer/update/'+ manufacturer_id,
      data:  $("#formManufacturerOption").serialize(),
      success: function (result) {
        $('#addNewManufacturerOption').modal('hide');
          showManufacturers();
          $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
            }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  } else {
     $.ajax({
      type: "get",
     url: '/app/car-manufacturer/save/',
       data:  $("#formManufacturerOption").serialize(),
      success: function (result) {
          $('#addNewManufacturerOption').modal('hide');
          showManufacturers();
             $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
            }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }
}


    
  function createManufacturer() {
        document.getElementById('btn-save-manufacturer').setAttribute('onclick', 'saveManufacturer()');
    
       document.getElementById('modalManufacturer').value = "";
       $("#label-action").html("Adding New");
  }

  function promptDeleteManufacturer(manufacturer_id) {
          $('#deleteManufacturerOption').modal('show');
           document.getElementById('hidden-manufacturer-id').value = manufacturer_id;
  }

  function deleteManufacturer(manufacturer_id) {
     var manufacturer_id =  document.getElementById('hidden-manufacturer-id').value;
    $.ajax({
      type: "get",
     url: '/app/car-manufacturer/delete/'+manufacturer_id,
       data:  $("#formManufacturerOption").serialize(),
      success: function (result) {
          showManufacturers();
          $('#deleteManufacturerOption').modal('hide');
            $(".alert-danger h4").html('<i class="mdi mdi-check-circle-outline mdi-24px me-2"><?i>Deleted!');
            $(".alert-danger p").html(result.message);
          $(".alert-danger").removeClass("d-none");
          setTimeout(function(){ 
            $(".alert-danger").addClass("d-none");
          }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }









  
// Vehicle Type

function editVehicleType(vehicle_type_id) {
  if(vehicle_type_id) {
  $.ajax({
    type: "get",
    url: '/app/car-vehicle-type/get/'+ vehicle_type_id,
      data:  $("#formVehicleTypeOption").serialize(),
        success: function (result) {
          document.getElementById('modalVehicleType').value = result.VehicleTypeData.value;
        document.getElementById('btn-save-vehicle-type').setAttribute('onclick', 'saveVehicleType('+result.VehicleTypeData.id+')');
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  } else {
  }

}
function showVehicleTypes() {
 $.ajax({
    type: "get",
    url: '/app/car-vehicle-type/show/',
      data:  $("").serialize(),
      success: function (result) {
          $('#addNewVehicleTypeOption').modal('hide');
          $("#tbl-vehicle-type-body").html(result.vehicleTypeHtml);
          console.log(result.vehicleTypeHtml);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
}

function saveVehicleType(vehicle_type_id) {
  if(vehicle_type_id) {
  $.ajax({
    type: "get",
    url: '/app/car-vehicle-type/update/'+ vehicle_type_id,
      data:  $("#formVehicleTypeOption").serialize(),
      success: function (result) {
        $('#addNewVehicleTypeOption').modal('hide');
          showVehicleTypes();
          $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
            }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  } else {
     $.ajax({
      type: "get",
     url: '/app/car-vehicle-type/save/',
       data:  $("#formVehicleTypeOption").serialize(),
      success: function (result) {
          $('#addNewVehicleTypeOption').modal('hide');
          showVehicleTypes();
             $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
            }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }
}


    
  function createVehicleType() {
       document.getElementById('modalVehicleType').value = "";
       $("#label-action").html("Adding New");
  }

  function promptDeleteVehicleType(vehicle_type_id) {
          $('#deleteVehicleTypeOption').modal('show');
           document.getElementById('hidden-vehicle-type-id').value = vehicle_type_id;
  }

  function deleteVehicleType(vehicle_type_id) {
     var vehicle_type_id =  document.getElementById('hidden-vehicle-type-id').value;
    $.ajax({
      type: "get",
     url: '/app/car-vehicle-type/delete/'+vehicle_type_id,
       data:  $("#formVehicleTypeOption").serialize(),
      success: function (result) {
          showVehicleTypes();
          $('#deleteVehicleTypeOption').modal('hide');
            $(".alert-danger h4").html('<i class="mdi mdi-check-circle-outline mdi-24px me-2"><?i>Deleted!');
            $(".alert-danger p").html(result.message);
          $(".alert-danger").removeClass("d-none");
          setTimeout(function(){ 
            $(".alert-danger").addClass("d-none");
          }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }
