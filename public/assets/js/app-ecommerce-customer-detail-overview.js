/**
 * Page Detail overview
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_customer_order = $('.datatables-customer-order'),
    order_details = baseUrl + 'app/ecommerce/order/details',
    statusObj = {
      1: { title: 'Estimate', class: 'bg-label-info' },
      2: { title: 'Job Order', class: 'bg-label-warning' },
      3: { title: 'Delivered', class: 'bg-label-success' },
      4: { title: 'Out for delivery', class: 'bg-label-primary' }
    };

  // orders datatable
  if (dt_customer_order.length) {
    var car_id = document.getElementById("hidden-selected-car-id").value;

    var dt_order = dt_customer_order.DataTable({
      ajax:  '/json/ecommerce-job-order/'+car_id, // JSON file to add data
    // ajax: assetsPath + '/json/ecommerce-job-order/';

      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'order' },
        { data: 'date' },
        { data: 'spent' },
        { data: ' ' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
          // order order number
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $id = full['order'];

            return "<a href='" + order_details + "'><span>#" + $id + '</span></a>';
          }
        },
        {
          // date
          targets: 3,
          render: function (data, type, full, meta) {
            var date = new Date(full.date); // convert the date string to a Date object
            var formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            return '<span class="text-nowrap">' + formattedDate + '</span > ';
          }
        },
        // {
        //   // status
        //   targets: 4,
        //   render: function (data, type, full, meta) {
        //     var $status = full['status'];

        //     return (
        //       '<span class="badge rounded-pill ' +
        //       statusObj[$status].class +
        //       '" text-capitalized>' +
        //       statusObj[$status].title +
        //       '</span>'
        //     );
        //   }
        // },
        {
          // spent
          targets: 4,
          render: function (data, type, full, meta) {
            var $js_list = full['js_list'];

            return '<span >' + $js_list + '</span>';
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var $id = full['id'];
            return (
              '<div>' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical ms-4"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="/app/job-order/' + $id + '" class="dropdown-item">View</a>' +
              '<a href="javascript:;" class="dropdown-item  delete-record">Delete</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"card-header d-flex flex-wrap py-3 py-sm-2"<"head-label text-center me-4 ms-1">f' +
        '>t' +
        '<"row mx-4"' +
        '<"col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0"i>' +
        '<"col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end"p>' +
        '>',
      lengthMenu: [6, 30, 50, 70, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search order'
      },
      // Buttons with Dropdown

      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['order'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });

    console.log(dt_order);

    var plate_number = document.getElementById("hidden-selected-car-plate-number").value;
    document.getElementById("modalPlateNumber").value = plate_number;
    var vehicle_model = document.getElementById("hidden-selected-vehicle-model").value;
    document.getElementById("modalVehicleModel").value = vehicle_model;
    var vehicle_model = document.getElementById("hidden-selected-vehicle-manufacturer").value;
    document.getElementById("modalManufacturer").value = vehicle_model;
 var vehicle_type = document.getElementById("hidden-selected-vehicle-type").value;
    document.getElementById("modalVehicleType").value = vehicle_type;
 var modalMileage = document.getElementById("hidden-selected-mileage").value;
    document.getElementById("modalMileage").value = modalMileage;
 var modalYearModel = document.getElementById("hidden-selected-year").value;
    document.getElementById("modalYearModel").value = modalYearModel;

    
        console.log(dt_order);
    $('div.head-label').html('<h5 class="card-title mb-0 text-nowrap">Estimates/Job Orders for <span id="selected-car-job-order" class="text-primary mb-0"><strong>'+plate_number+'</strong></span></h5>');
  }

  // Delete Record
  $('.datatables-orders tbody').on('click', '.delete-record', function () {
    dt_order.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});


  


  
function getDropdown() {
  $.ajax({
    type: "get",
    url: '/app/get-dropdown-options',
    data:  $("").serialize(),
    success: function (data) {
      console.log(data.carVehicleTypeOptions);
      console.log(data.carManufacturerOptions);
      $("#modaladdVehicleyType").html(data.carVehicleTypeOptions);
      $("#modaladdManufacturer").html(data.carManufacturerOptions);

    },
    error: function (data, textStatus, errorThrown) {
        console.log(data.success);

    },
  });
}
function addJobOrder() {
    var plate_number = document.getElementById("hidden-selected-car-plate-number").value;
    var car_id = document.getElementById("hidden-selected-car-id").value;
    $("#car-plate-number").html(plate_number);

modalMileage
  const dateInput = document.getElementById('bs-rangepicker-single');
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;

    dateInput.value = formattedDate; // Sets the input to today's date
  // console.log(id);
  // // $('#modal-drawng').modal('open');
  // $.ajax({
  //   type: "get",
  //   url: '/app/get-dropdown-options',
  //   data:  $("").serialize(),
  //   success: function (data) {
  //     console.log(data.carVehicleTypeOptions);
  //     $("#modalVehicleType").html(data.carVehicleTypeOptions);
  //   },
  //   error: function (data, textStatus, errorThrown) {
  //       console.log(data.success);

  //   },
  // });
}

function saveJobOrder() {
    var car_id = document.getElementById("hidden-selected-car-id").value;
    var date = document.getElementById("bs-rangepicker-single").value;
    var est = document.getElementById("modalEst").value;
    var modalPlateNumber = document.getElementById("modalPlateNumber").value;
    var modalManufacturer = document.getElementById("modalManufacturer").value;
    var modalVehicleModel = document.getElementById("modalVehicleModel").value;
    var modalMileage = document.getElementById("modalMileage").value;
    var modalStatus = "estimate";
   
  $.ajax({
    type: "get",
    url: '/app/save-job-order/'+ car_id,
    data:  {car_id: car_id,date:date,est:est,modalPlateNumber:modalPlateNumber,modalManufacturer:modalManufacturer,modalVehicleModel:modalVehicleModel,modalMileage:modalMileage,modalStatus:modalStatus },
    success: function (result) {
        $('#addNewJobOrder').modal('hide');
        $(".alert-success p").html(result.message);
        $(".alert-success").removeClass("d-none");
      setTimeout(function(){ 
        $(".alert-success").addClass("d-none");
        const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
        form.reset();
        location.reload();
    }, 3000);


    },
    error: function (data, textStatus, errorThrown) {
        console.log(data.success);

    },
  });
}


function addNewCar() {
    // var car_id = document.getElementById("hidden-selected-car-id").value;
    var owner_id = document.getElementById("hidden-customer-id").value;
    var modalPlateNumber = document.getElementById("modaladdPlateNumber").value;
    var modalManufacturer = document.getElementById("modaladdManufacturer").value;
    var modalVehicleType = document.getElementById("modaladdVehicleyType").value;
    var modalVehicleModel = document.getElementById("modaladdVehicleModel").value;
    var modaladdYearModel = document.getElementById("modaladdYearModel").value;
    var modalMileage = document.getElementById("modaladdMileage").value;
  $.ajax({
    type: "get",
    url: '/app/add-new-car/',
    data:  {modaladdYearModel: modaladdYearModel, modalVehicleType:modalVehicleType,owner_id:owner_id,modalPlateNumber:modalPlateNumber,modalManufacturer:modalManufacturer,modalVehicleModel:modalVehicleModel,modalMileage:modalMileage },
    success: function (result) {
        $(".show-car-added").removeClass("d-none");
        $(".show-car-added").html(result.htmlAddCar);
        $('#addNewCar').modal('hide');
        $(".alert-success p").html(result.message);
        $(".alert-success").removeClass("d-none");
      setTimeout(function(){ 
        $(".alert-success").addClass("d-none");
        const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
    }, 3000);


    },
    error: function (data, textStatus, errorThrown) {
        console.log(data.success);

    },
  });
}
