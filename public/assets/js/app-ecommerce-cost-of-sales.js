/**
 * app-ecommerce-product-list
 */

'use strict';

// Datatable (jquery)
$(function () {
$(".layout-navbar-fixed").addClass("layout-menu-collapsed");

  let borderColor, bodyBg, headingColor;
 
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed, so add 1
  const day = String(today.getDate()).padStart(2, '0');

  const dateToday = `${year}-${month}-${day}`;

console.log(dateToday);
  // Example usage:
    var pathArray = window.location.pathname.split('/');
    const dateYmd = pathArray[3];
    if(dateYmd) {
      const formattedDate = convertDateFormat(dateYmd);
      document.getElementById("flatpickr-date").value = formattedDate;
      var getDate = dateYmd;
    } else {
      window.location.pathname = '/app/cost-of-sales/'+dateToday;
      document.getElementById("flatpickr-date").value = dateToday;
      var getDate = dateToday;

    }
    $(".btn-date-edit").attr("onclick", "getFormCashBalance('"+getDate+"')");

    // getCashBlance(getDate);
$.ajax({
    type: "get",
    url: '/json/cost-of-sales/'+getDate,
      data:  $("").serialize(),
      success: function (result) {
        console.log("test");
       
          if(result.success == true) {
              $("#tblcosHtml").html(result.cosHtml);
            $(".btn-date").removeClass("disabled");
            $(".btn-date-edit").removeClass("d-none");
            $(".btn-date").addClass("d-none");
            
          } else {
            $(".btn-date").removeClass("d-none");
            $(".btn-date").removeClass("disabled");
            $(".btn-date-edit").addClass("d-none");

          }
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });

// let nf = new Intl.NumberFormat('en-US');

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}


var nf = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'PHP',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});
nf.format(123456.789); // ‘$123,456.79’




  $.ajax({
    type: "get",
    url: '/app/get/total/'+getDate,
      data:  $("#editCashBalanceForm").serialize(),
      success: function (result) {
        console.log(result);
          if(result.success == true) {
          $("#total-sales").html(nf.format(result.total_sales));
          $("#total-cars").html(result.total_cars);
          $("#month-sales").html( nf.format(result.total_monthly_sales));
          $("#total-month-cars").html(result.total_monthly_cars);
          $("#total-cash").html(result.total_cash);
          $("#total-gcash").html(result.total_gcash);
          $("#total-mobile-check").html(result.total_mobile_check);
          $("#total-others").html(result.total_others);
          $("#grand-total").html(result.grand_total);

          
          
          } else {
            $(".btn-date").removeClass("d-none");
            $(".btn-date").removeClass("disabled");
            $(".btn-date-edit").addClass("d-none");

          }
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });



  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table
  var dt_product_table = $('.datatables-products'),
    productAdd = baseUrl + 'app/car/add',
    statusObj = {
      1: { title: 'Estimate', class: 'bg-label-warning' },
      2: { title: 'Job Order', class: 'bg-label-info' },
      3: { title: 'Completed', class: 'bg-label-success' }
    },
    categoryObj = {
      0: { title: 'Sedan' },
      1: { title: 'SUV' },
      2: { title: 'Hatchback' },
      3: { title: 'Pickup' },
      4: { title: 'MPV' },
      5: { title: 'Others' }
    },
    // stockObj = {
    //   0: { title: 'Out_of_Stock' },
    //   1: { title: 'In_Stock' }
    // },
    stockFilterValObj = {
      0: { title: 'Out of Stock' },
      1: { title: 'In Stock' }
    };

  // E-commerce Products datatable

  if (dt_product_table.length) {
    var pathArray = window.location.pathname.split('/');
    var date = pathArray[3];
    var dt_products = dt_product_table.DataTable({
      // ajax: assetsPath + 'json/ecommerce-product-list.json', // JSON file to add data
      ajax: '/json/cost-of-sales/?date='+ date, // JSON file to add data
      columns: [
        // columns according to JSON
        { data: 'job_order_number' },
        { data: 'product_name' },
        { data: 'cash' },
        { data: 'gcash' },
        { data: 'mobile_check' },
        { data: 'status' },
        { data: 'amount' },
        { data: 'jo_id' }
      ],
      columnDefs: [

       {
          // Sku
          targets: 0,
          render: function (data, type, full, meta) {
            var $job_order_number = full['job_order_number'];
            var $jo_id = full['jo_id'];
            if($job_order_number  == null || $job_order_number == 0) {
              return '<span>'+$jo_id+'</span>';
            } else {
            return '<span>' + $job_order_number + '</span>';

            }
          }
        },
        {
          // Product name and product_brand
          targets: 1,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['product_name'],
              $id = full['id'],
              $product_brand = full['product_brand'],
              $car_overview_link = full['car_overview_link'],
              $image = full['image'];
            if ($image) {
              // For Product image

              var $output =
                '<img src="' +
                assetsPath +
                'img/ecommerce-images/' +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded-2">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['product_brand'],
                $car_overview_link = full['car_overview_link'],
                
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for Product name and product_brand
            var $row_output =
              '<a href="'+$car_overview_link+'"><div class=" justify-content-end align-items-center product-name">' +
              '<div class="avatar-wrapper me-3">' +
            
              '</div>' +
              '<div class=" flex-column">' +
              '<span class="text-nowrap text-heading fw-medium">' +
              $name +
              '</span>' +
              '<small class="text-truncate d-none d-sm-block">' +
              $product_brand +
              '</small>' +
              '</div>' +
              '</div></a>';
            return $row_output;
          }
        },

         {
          // Sku
          targets: 2,
          render: function (data, type, full, meta) {
            var $cash = full['cash'];
            if($cash > 0) {
              $cash = "₱"+$cash.toLocaleString()+".00";
            } else {
              $cash = "";
            }
            return '<span>' + $cash + '</span>';
          }
        },

         {
          // Sku
          targets: 3,
          render: function (data, type, full, meta) {
            var $gcash = full['gcash'];
            if($gcash > 0) {
              $gcash = "₱"+$gcash.toLocaleString()+".00";
            } else {
              $gcash = "";
            }
            return '<span>' + $gcash + '</span>';
          }
        },
        {
          // Sku
          targets: 4,
          render: function (data, type, full, meta) {
            var $mobile_check = full['mobile_check'];
             if($mobile_check > 0) {
              $mobile_check = "₱"+$mobile_check.toLocaleString()+".00";
            } else {
              $mobile_check = "";
            }
            return '<span>' + $mobile_check + '</span>';
          }
        },
          {
          // Sku
          targets: 5,
          render: function (data, type, full, meta) {
            var $others = full['others'];
            if($others > 0) {
              $others = "₱"+$others.toLocaleString()+".00";
            } else {
              $others = "";
            }
            return '<span>' + $others + '</span>';
          }
        },

         {
          // Sku
          targets: 6,
          render: function (data, type, full, meta) {
            var $amount = full['amount'];

            return '<span>' + $amount + '</span>';
          }
        },

        
        // {
        //   // price
        //   targets: 6,
        //   render: function (data, type, full, meta) {
        //     var $price = full['price'];

        //     return '<span>' + $price + '</span>';
        //   }
        // },
        // {
        //   // qty
        //   targets: 7,
        //   responsivePriority: 4,
        //   render: function (data, type, full, meta) {
        //     var $qty = full['qty'];

        //     return '<span>' + $qty + '</span>';
        //   }
        // },
        {
          // Status
          targets: -2,
          render: function (data, type, full, meta) {
            var $status = full['status'];

            return (
              '<span class="badge rounded-pill ' +
              statusObj[$status].class +
              '" text-capitalized>' +
              statusObj[$status].title +
              '</span>'
            );
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var $job_order_link = full['job_order_link'];

            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a  href="'+$job_order_link+'"><button class="btn btn-sm btn-icon"><i class="mdi mdi-pencil-outline"></i></button></a>' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical me-2"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:0;" class="dropdown-item">View</a>' +
              '<a href="javascript:0;" class="dropdown-item">Suspend</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [2, 'asc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex border-top rounded-0 flex-wrap py-md-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Product',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle me-3 waves-effect waves-light',
          text: '<i class="mdi mdi-export-variant me-1"></i><span class="d-none d-sm-inline-block">Export </span>',
          buttons: [
            {
              extend: 'print',
              text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        // {
        //   text: '<i class="mdi mdi-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add Car</span>',
        //   className: 'add-new btn btn-primary ms-n1 waves-effect waves-light',
        //   action: function () {
        //     window.location.href = productAdd;
        //   }
        // }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['product_name'];
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
      },
      initComplete: function () {
        // Adding status filter once table initialized
        this.api()
          .columns(-2)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductStatus" class="form-select text-capitalize"><option value="">Status</option></select>'
            )
              .appendTo('.product_status')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + statusObj[d] + '">' + statusObj[d] + '</option>');
              });
          });
        // Adding category filter once table initialized
        this.api()
          .columns(3)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductCategory" class="form-select text-capitalize"><option value="">Category</option></select>'
            )
              .appendTo('.product_category')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + categoryObj[d] + '">' + categoryObj[d] + '</option>');
              });
          });
        // Adding stock filter once table initialized
        this.api()
          .columns(4)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductStock" class="form-select text-capitalize"><option value=""> Stock </option></select>'
            )
              .appendTo('.product_stock')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('');
              });
          });
      }
    });
    console.log(dt_products);
    $('.dataTables_length').addClass('mt-0 mt-md-3');
    $('.dt-action-buttons').addClass('pt-0');
    $('.dt-buttons').addClass('d-flex flex-wrap');
  }

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});


function changeDate() {

 const newDate =  document.getElementById("flatpickr-date").value;

const convertedDate = convertDateFjYtoYmd(newDate);

    window.location.replace("/app/cost-of-sales/"+convertedDate);
}


function convertDateFjYtoYmd(dateString) {
  const dateObject = new Date(dateString);

  const year = dateObject.getFullYear();
  const month = dateObject.getMonth() + 1;
  const day = dateObject.getDate();

  const formattedMonth = month < 10 ? '0' + month : month;
  const formattedDay = day < 10 ? '0' + day : day;

  return `${year}-${formattedMonth}-${formattedDay}`;
}



function convertDateFormat(dateString) {
  // 1. Create a Date object from the Y-m-d string
  const date = new Date(dateString);

  // 2. Format the Date object to F j, Y
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };
  return date.toLocaleDateString('en-US', options);
}


    function exportCsv() {

      var pathArray = window.location.pathname.split('/');
      const dateYmd = pathArray[3];
       $.ajax({
        type: "get",
        url: '/app/export/cost-of-sales/'+dateYmd,
          data:  $("#editCashBalanceForm").serialize(),
          success: function (result) {
           window.open('/download/'+result.fileName, '_blank').focus();

        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
          
      },
    });
    
    }

// function addNewCashBalance() {
//    var pathArray = window.location.pathname.split('/');
//   const dateYmd = pathArray[3];


//   $.ajax({
//     type: "get",
//     url: '/app/create/cash-balance/'+dateYmd,
//       data:  $("#editCashBalanceForm").serialize(),
//       success: function (result) {
//           if(result.success == true) {
//            getCashBlance(dateYmd);
//               $('#createCashBalance').modal('hide');
//             $(".alert-success p").html(result.message);
//               $(".alert-success").removeClass("d-none");
//             setTimeout(function(){ 
//               $(".alert-success").addClass("d-none");
//           }, 3000);
//           }
//         },
//       error: function (result, textStatus, errorThrown) {
//           console.log(result.success);
//       },
//     });
// }



// function saveRemarks() {

//    var pathArray = window.location.pathname.split('/');
//    const date = pathArray[3];

//    var remarks = document.getElementById("remarks").value;
//    var checkedBy = document.getElementById("input-checked-by").value;
//    var preparedBy = document.getElementById("input-prepared-by").value;

// $.ajax({
//     type: "get",
//     url: '/app/save-daily-sales-remarks/'+date,
//       data:  {remarks: remarks, checkedBy: checkedBy, preparedBy: preparedBy},
//       success: function (result) {
//           if(result.success == true) {
//             console.log(result);
//            $(".alert-success p").html(result.message);
//               $(".alert-success").removeClass("d-none");
//             setTimeout(function(){ 
//               $(".alert-success").addClass("d-none");
//             }, 3000);

//           }
//         },
//       error: function (result, textStatus, errorThrown) {
//           console.log(result.success);
//       },
//     });
// }



// function getFormCashBalance(date) {
// $.ajax({
//     type: "get",
//     url: '/app/get-form/cash-balance/'+date,
//       data:  $("#editCashBalanceForm").serialize(),
//       success: function (result) {
//           if(result.success == true) {
//             console.log(result);
//              document.getElementById("modaladdCash1000").value = result.cashData.cash_1000;
//              document.getElementById("modaladdCash500").value = result.cashData.cash_500;
//              document.getElementById("modaladdCash200").value = result.cashData.cash_200;
//              document.getElementById("modaladdCash100").value = result.cashData.cash_100;
//              document.getElementById("modaladdCash50").value = result.cashData.cash_50;
//              document.getElementById("modaladdCash20").value = result.cashData.cash_20;
//              document.getElementById("modaladdCash10").value = result.cashData.cash_10;
//              document.getElementById("modaladdCash5").value = result.cashData.cash_5;
//              document.getElementById("modaladdLooseCoins").value = result.cashData.loose_coins;
//              document.getElementById("modaladdLooseChange").value = result.cashData.change;
//             $(".btn-submit").attr("onclick", "updateCashBalance('"+date+"')");
//           }
//         },
//       error: function (result, textStatus, errorThrown) {
//           console.log(result.success);
//       },
//     });
// }

// function updateCashBalance(date) {
//   $.ajax({
//     type: "get",
//     url: '/app/update/cash-balance/'+date,
//       data:  $("#editCashBalanceForm").serialize(),
//       success: function (result) {
//           if(result.success == true) {
//             reloadCashBalance(date);
//                $('#createCashBalance').modal('hide');
//             $(".alert-success p").html(result.message);
//               $(".alert-success").removeClass("d-none");
//             setTimeout(function(){ 
//               $(".alert-success").addClass("d-none");
//           }, 3000);
//           }
//         },
//       error: function (result, textStatus, errorThrown) {
//           console.log(result.success);
//       },
//     });
// }


// function reloadCashBalance(getDate) {
// $.ajax({
//     type: "get",
//     url: '/app/get/cash-balance/'+getDate,
//       data:  $("#editCashBalanceForm").serialize(),
//       success: function (result) {
//         console.log("test");
       
//           if(result.success == true) {
//               $("#tblcashbalancehtml").html(result.htmlCashBalance);
//             $(".btn-date").removeClass("disabled");
//             $(".btn-date-edit").removeClass("d-none");
//             $(".btn-date").addClass("d-none");
            
//           } else {
//             $(".btn-date").removeClass("d-none");
//             $(".btn-date").removeClass("disabled");
//             $(".btn-date-edit").addClass("d-none");

//           }
//         },
//       error: function (result, textStatus, errorThrown) {
//           console.log(result.success);
//       },
//     });
// }


 
//    var pathArray = window.location.pathname.split('/');
//     const dateYmd = pathArray[3];
//     if(dateYmd) {
//       const formattedDate = convertDateFormat(dateYmd);
//       document.getElementById("flatpickr-date").value = formattedDate;
//       var getDate = dateYmd;
//     } else {
//       window.location.pathname = '/app/sales-report/'+dateToday;
//       document.getElementById("flatpickr-date").value = dateToday;
//       var getDate = dateToday;

//     }
//     $(".btn-date-edit").attr("onclick", "getFormCashBalance('"+getDate+"')");


//     function copyPreparedBy() {
//       $(".btn-prepared-by").removeClass("disabled");
//         $.ajax({
//         type: "get",
//         url: '/app/get-user-name',
//           data:  $("").serialize(),
//           success: function (result) {
//           document.getElementById("input-prepared-by").value = result.userName;
//         },
//       error: function (result, textStatus, errorThrown) {
//           console.log(result.success);
//       },
//     });
//     }


    // function copyCheckedBy() {
    //   $(".btn-checked-by").removeClass("disabled");
    //     $.ajax({
    //     type: "get",
    //     url: '/app/get-user-name',
    //       data:  $("").serialize(),
    //       success: function (result) {
    //       document.getElementById("input-checked-by").value = result.userName;
    //     },
    //   error: function (result, textStatus, errorThrown) {
    //       console.log(result.success);
    //   },
    // });
    // }

    


    // function getCashBlance(getDate) {
    //     $.ajax({
    //     type: "get",
    //     url: '/app/get/cash-balance/'+getDate,
    //       data:  $("#editCashBalanceForm").serialize(),
    //       success: function (result) {
    //         console.log("est");
    //         console.log(result);
       

    //     if(result.remarks>'') {
    //        const myTextarea = document.getElementById('remarks');
    //       myTextarea.value = result.remarks[0].remarks; 
    //       document.getElementById('input-checked-by').value = result.remarks[0].checked_by; 
    //       document.getElementById('input-prepared-by').value = result.remarks[0].prepared_by; 
    //     }
      

    //     console.log(result);
    //       if(result.success == true) {
    //           $("#tblcashbalancehtml").html(result.htmlCashBalance);
    //         $(".btn-date").removeClass("disabled");
    //         $(".btn-date-edit").removeClass("d-none");
    //         $(".btn-date").addClass("d-none");
    //       } else {
    //         $(".btn-date").removeClass("d-none");
    //         $(".btn-date").removeClass("disabled");
    //         $(".btn-date-edit").addClass("d-none");

    //       }
    //     },
    //   error: function (result, textStatus, errorThrown) {
    //       console.log(result.success);
    //   },
    // });

    // }