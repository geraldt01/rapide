/**
 * App Invoice - Edit
 */

'use strict';

(function () {





  let hasReached75 = false;
let hasReached100 = false;

function handleScroll() {
  const scrollTop = window.scrollY;
  const viewportHeight = window.innerHeight;
  const totalHeight = document.documentElement.scrollHeight;

  const scrollPercent = ((scrollTop + viewportHeight) / totalHeight) * 100;


 $(".btn-floating-undo").removeClass("d-none"); 
 $(".btn-floating-redo").removeClass("d-none"); 


  if (scrollPercent >= 50 && !hasReached75) {
    console.log('User reached 75% scroll depth');
    $(".invoice-actions").removeClass("fixed-section");
    $(".invoice-table").addClass("fixed-section-part");
  } else {
    console.log('User less 75% scroll depth');
    $(".invoice-actions").addClass("fixed-section");
    $(".invoice-table").removeClass("fixed-section-part");
  }

    if (scrollPercent >= 56 && !hasReached75) {
    $(".invoice-table").addClass("fixed-section-part");

  } else {
    $(".invoice-table").removeClass("fixed-section-part");

  }

    if (scrollPercent >= 32 && !hasReached75) {
    $(".invoice-table-labor").addClass("fixed-section-labor");

  } else {
    $(".invoice-table-labor").removeClass("fixed-section-labor");

  }

  //   if (scrollPercent >= 250 && !hasReached75) {
  //   $(".invoice-table").removeClass("fixed-section-part");
  //   console.log("remove");
  // } else {
  //   $(".invoice-table").addClass("fixed-section-part");
  //   console.log("add");

  // }


}

// Attach the scroll event listener
window.addEventListener('scroll', handleScroll);






  
  document.body.classList.add('invoice-page');
$(".layout-navbar-fixed").addClass("layout-menu-collapsed");

   var invoice_date = document.getElementById("hidden-invoice-date").value;
   var payment2 = document.getElementById("hidden-payment2").value;

  if(payment2 > 0) {
    addPayment();
  }



  const invoiceItemPriceList = document.querySelectorAll('.invoice-item-price'),
    invoiceItemQtyList = document.querySelectorAll('.invoice-item-qty'),
    date = new Date(),
    invoiceDate = document.querySelector('.invoice-date'),
    dueDate = document.querySelector('.due-date');

  // Price
  if (invoiceItemPriceList) {
    invoiceItemPriceList.forEach(function (invoiceItemPrice) {
      new Cleave(invoiceItemPrice, {
        delimiter: '',
        numeral: true
      });
    });
  }

  // Qty
  if (invoiceItemQtyList) {
    invoiceItemQtyList.forEach(function (invoiceItemQty) {
      new Cleave(invoiceItemQty, {
        delimiter: '',
        numeral: true
      });
    });
  }

  // Datepicker
  if (invoiceDate) {
    invoiceDate.flatpickr({
      dateFormat: "m/d/Y",
      monthSelectorType: 'static',
      defaultDate: invoice_date,
    });
  }
  if (dueDate) {
    dueDate.flatpickr({
      dateFormat: "m/d/Y",
      monthSelectorType: 'static',
      defaultDate: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 30),
       altFormat: "F j, Y - h:i", 
    });
  }
})();

// repeater (jquery)
$(function () {
  var applyChangesBtn = $('.btn-apply-changes'),
    discount,
    tax1,
    tax2,
    discountInput,
    taxInput1,
    taxInput2,
    sourceItem = $('.source-item'),
    adminDetails = {
      'App Design': 'Designed UI kit & app pages.',
      'App Customization': 'Customization & Bug Fixes.',
      'ABC Template': 'Bootstrap 4 admin template.',
      'App Development': 'Native App Development.'
    };

  // Prevent dropdown from closing on tax change
  $(document).on('click', '.tax-select', function (e) {
    e.stopPropagation();
  });

  // On tax change update it's value value
  function updateValue(listener, el) {
    listener.closest('.repeater-wrapper').find(el).text(listener.val());
  }

  
  // Apply item changes btn
  if (applyChangesBtn.length) {
    $(document).on('click', '.btn-apply-changes', function (e) {
      var $this = $(this);
      taxInput1 = $this.closest('.dropdown-menu').find('#taxInput1');
      taxInput2 = $this.closest('.dropdown-menu').find('#taxInput2');
      discountInput = $this.closest('.dropdown-menu').find('#discountInput');
      tax1 = $this.closest('.repeater-wrapper').find('.tax-1');
      tax2 = $this.closest('.repeater-wrapper').find('.tax-2');
      discount = $('.discount');

      if (taxInput1.val() !== null) {
        updateValue(taxInput1, tax1);
      }

      if (taxInput2.val() !== null) {
        updateValue(taxInput2, tax2);
      }

      if (discountInput.val().length) {
        $this
          .closest('.repeater-wrapper')
          .find(discount)
          .text(discountInput.val() + '%');
      }
    });
  }

  // Repeater init
  if (sourceItem.length) {
    sourceItem.on('submit', function (e) {

      e.preventDefault();
    });
    sourceItem.repeater({
      show: function () {
        $(this).slideDown();
        // Initialize tooltip on load of each item
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      },
      hide: function (e) {
        $(this).slideUp();
      }
    });
  }

  // Item details select onchange
  $(document).on('change', '.item-details', function () {

    var $this = $(this),
      value = adminDetails[$this.val()];
    if ($this.next('textarea').length) {
      $this.next('textarea').val(value);
    } else {
      // $this.after('<textarea class="form-control" rows="2">' + value + '</textarea>');
    }
  });


});
sessionStorage.updateTriggered = "false";
showCurrentStatus();

calculateAll();
  var job_order_id = document.getElementById("hidden-job-order-id").value;
 const preview = document.getElementById("btn-preview");
  preview.href = "/app/job-order/preview/"+job_order_id;


  function addItem(type, itemNum) { 
    setTimeout(function(){ 
        let total_item = document.getElementById('hidden-'+type+'-total-item').value;
        let plus = 1;
        let total = parseFloat(10)+plus;
        var job_order_id = document.getElementById("hidden-job-order-id").value;
        let itemNum = document.getElementById('hidden-'+type+'-total-item').value;

      let numPart = parseFloat(itemNum) + 1;

      if(type == 'package') {
     
      } else if (type == 'labor') {
          setTimeout(function(){ 
            document.getElementById('labor-qty-'+total).value = 1;
          }, 800);

      } else {
          setTimeout(function(){ 
            document.getElementById('part-qty-'+total).value = 1;
          }, 800);
      }
      $.ajax({
      type: "get",
      url: '/app/enable-item/'+type,
        data:  {itemNum: numPart, job_order_id: job_order_id },
        success: function (result) {
            if(result.success == true) {
            
            }
          },
        error: function (result, textStatus, errorThrown) {
            console.log(result.success);
        },
      });

      // if(type !== 'packagemanual') {
      //   $('#item-list-'+type+'-'+numPart).removeClass("disable-"+type+"-item");
      // }
  
        $('#item-list-'+type+'-'+numPart).removeClass("disable-"+type+"-item");

        document.getElementById('hidden-'+type+'-total-item').value = numPart;
    }, 1000);
  }

  function deleteItem(id, type, delete_item_id) {
    var item_id = id;
    if(type == 0) {
      deleteItemNow(id, 0, delete_item_id);
    }else if(type == 0.1) {
      deleteItemNow(id, 0.1, delete_item_id);
    }else if(type == 1) {
      deleteItemNow(id, 1, delete_item_id);
    } else {
      deleteItemNow(id, 2, delete_item_id);
    }
    $(".bt-save-changes").removeClass("disabled");
  }

  function deleteItemNow(id, type, delete_item_id) {
    if(type == 0) {
      $.ajax({
      type: "post",
      url: '/app/delete-package-item/'+ delete_item_id,
        data:  $("#form-job-order").serialize(),
        success: function (result) {
            $(".loader").removeClass("d-none");
              document.getElementById('package-note2-'+id).value = "";
              // document.getElementById('package-note-'+id).value = "";
              document.getElementById('package-price-0').value = 0;
            $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              window.location.reload();
              $(".alert-success").addClass("d-none");
          }, 3000);
        },
        error: function (result, textStatus, errorThrown) {
          
        },
      });

      calculateAll();

    }else if(type == 0.1) {
      $.ajax({
      type: "post",
      url: '/app/delete-package-manual-item/'+ delete_item_id,
        data:  $("#form-job-order").serialize(),
        success: function (result) {
            $(".loader").removeClass("d-none");
              document.getElementById('package-manual-cost'+id).value = "";
              document.getElementById('package-qty'+id).value = "";
              document.getElementById('package-manual-part-number'+id).value = "";
              document.getElementById('package-manual-price'+id).value = 0;
              document.getElementById('package-manual-srp-labor'+id).value = 0;
              
            $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              window.location.reload();
              $(".alert-success").addClass("d-none");
          }, 3000);
        },
        error: function (result, textStatus, errorThrown) {
          
        },
      });

      calculateAll();

    }
    
    else if(type == 1) {
      document.getElementById('labor-text-'+id).value = "";
      document.getElementById('labor-cost-'+id).value = 0;
      document.getElementById('labor-price-'+id).value = 0;
      document.getElementById('labor-qty-'+id).value = 1;
      document.getElementById('labor-amount-'+id).value = "";
      document.getElementById('labor-code-'+id).value = "";
      // document.getElementById('labor-part-number-'+id).value = "";
      calculateLabor(id);
       $.ajax({
      type: "post",
      url: '/app/delete-labor-item/'+ delete_item_id,
        data:  $("#form-job-order").serialize(),
        success: function (result) {
            $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
          }, 3000);
        },
        error: function (result, textStatus, errorThrown) {
          
        },
      });
    } else {
   
      calculatePart(id);
       $('#part-option-'+id).html("<option>test</option>");
      document.getElementById('part-text-'+id).value = "";
      // document.getElementById('part-option-'+id).value = "";
      document.getElementById('part-part-note-'+id).value = "";
      document.getElementById('part-part-number-'+id).value = "";
      document.getElementById('part-unit-cost-'+id).value = 0;
      document.getElementById('part-total-cost-'+id).value = 0;
      document.getElementById('part-unit-cost-'+id).value = 0;
      document.getElementById('part-price-'+id).value = 0;
      document.getElementById('part-amount-'+id).value = 0;
      document.getElementById('supplier-'+id).value = "";
      document.getElementById('supplier-inv-'+id).value = "";
      document.getElementById('part-qty-'+id).value = "";
      document.getElementById('part-code-'+id).value = "";


      //        $('#part-option-'+id).html("<option>test</option>");
      // document.getElementById('part-text-'+id).value = "";
        // const inputElements  =  document.getElementsByName('group-c[0][part-option]');
        // const inputValue = inputElements[0].value = ;

// document.getElementById('part-option-'+id).selectedIndex = -1;

//           const inputElements  =  document.getElementsByName('group-c[0][part-option]');
//        const inputValue = inputElements[0].value;

//        $("#select2-part-option-1#select2Basic1-container").html("");
//         console.log("aguy");

      // Returns the selected value
 


     $.ajax({
      type: "post",
      url: '/app/delete-job-order-item/'+ delete_item_id,
        data:  $("#form-job-order").serialize(),
        success: function (result) {
            $(".loader").removeClass("d-none");

            $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
              window.location.reload();
          }, 3000);
        },
        error: function (result, textStatus, errorThrown) {
          
        },
      });
    }
     
  }
  function saveInvoice(job_order_id) {
    $.ajax({
      type: "post",
      url: '/app/save-job-order-item/'+ job_order_id,
        data:  $("#form-job-order").serialize(),
        success: function (result) {
          console.log(result.success);
          if(result.success == true) {
            $(".bt-save-changes").addClass("disabled");
            sessionStorage.setItem("updateTriggered", "false");
            $('#addNewJobOrder').modal('hide');
            $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            setTimeout(function(){ 
            $(".alert-success").addClass("d-none");
              const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
            }, 3000);
          } else {
             $(".alert-danger p").html(result.message);
            $(".alert-danger").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-danger").addClass("d-none");
              const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
          }, 3000);
          }
            
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
              $(".alert-danger p").html(result.message);
            $(".alert-danger").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-danger").addClass("d-none");
              const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
          }, 3000);
      },
    });
  }


  
  // function selectSub() {
  //   const inputElementsPackage  =  document.getElementsByName('group-a[0][package-option]');
  //   var package_id = inputElementsPackage[0].value;

  //      $.ajax({
  //       type: "get",
  //       url: '/app/select-package-sub/'+ package_id,
  //         data:  $("").serialize(),
  //         success: function (result) {
  //                 const amount = result.price * 1;
  //                   document.getElementById('package-price-'+option_id).value = amount;
  //                 calculateAll();
  //         },
  //       error: function (result, textStatus, errorThrown) {
  //           console.log(result.success);
  //       },
  //     });
              
  // }
  
  // function calculatePackageManual(option_id) {
  //   alert(option_id);
  // const inputManual  =  document.getElementsByName('package-sub-option-'+option_id);
  //   alert(inputManual);

  // }
  function calculatePackage(option_id) {
  const inputElements  =  document.getElementsByName('group-a['+option_id+'][package-option]');
  var package_id = inputElements[0].value;

  var job_order_id = document.getElementById('hidden-job-order-id').value;
  
    $.ajax({
      type: "get",
      url: '/app/check-inventory-package/'+ package_id,
        data:  {qty: 1, job_order_id: job_order_id},
        success: function (result) {
              if(result.success == false) {

                $(".alert-danger p").html(result.message);
                $(".alert-danger").removeClass("d-none");
                setTimeout(function(){ 
                  $(".alert-danger").addClass("d-none");
                }, 3000);
                return false;
              } else {
                $("#optionOneTwoHtml").html(result.optionOneTwoHtml);

                
                $(".loader").removeClass("d-none");
                setTimeout(function(){ 
                window.location.replace("/app/job-order/"+job_order_id);
                }, 1000);

                // $.ajax({
                //     type: "get",
                //     url: '/app/get-job-order-item-package-price/'+ package_id,
                //       data:  $("").serialize(),
                //       success: function (result) {
                //               const amount = result.price * 1;
                //                 document.getElementById('package-price-'+option_id).value = amount;
                //               calculateAll();
                //       },
                //     error: function (result, textStatus, errorThrown) {
                //         console.log(result.success);
                //     },
                //   });
                 
              }
          },
          error: function (result, textStatus, errorThrown) {
          },
        });

  }


   function calculatePackageManualItem(option_id, element) {
      // const package_id  =  document.getElementsByName('group-a2['+option_id+'][package-sub-option]');
       const elementValue = element.value;
      $.ajax({
      type: "get",
      url: '/app/check-inventory-package-manual-select/'+ elementValue,
        data:  {},
        success: function (result) {
              if(result.success == false) {
                $(".alert-danger p").html(result.message);
                $(".alert-danger").removeClass("d-none");
                element.value = "";
                document.getElementById('package-manual-cost'+option_id).value = "";
                document.getElementById('package-qty'+option_id).value = "";
                document.getElementById('package-manual-part-number'+option_id).value = "";
                document.getElementById('package-manual-price'+option_id).value = 0;
                var job_order_id = document.getElementById('hidden-job-order-id').value;
                setTimeout(function(){ 
                  $(".loader").removeClass("d-none");
                }, 1500)
                setTimeout(function(){ 
                   window.location.replace("/app/job-order/"+job_order_id);
                }, 2000);
                
          

                return false;
              } else {

                document.getElementById('package-manual-cost'+option_id).value = result.getInventory.cost;
                document.getElementById('package-qty'+option_id).value = 1;
                let total_cost = 1 * result.getInventory.cost;
                document.getElementById('package-manual-total-cost'+option_id).value = total_cost;
                document.getElementById('package-manual-part-number'+option_id).value = result.getInventory.part_number;
                document.getElementById('package-manual-srp-labor'+option_id).value = result.getInventory.price;
                calculateAll();
              }
          },
          error: function (result, textStatus, errorThrown) {
          },
        });


   }


   function calculatePackageManual(option_id) {

    const options = {
      minimumFractionDigits: 2, // Ensures at least two decimal places
      maximumFractionDigits: 2, // Limits to a maximum of two decimal places
      style: 'decimal'          // Specifies decimal formatting
    };


    const original_option_id = option_id;

       let package_manual_qty = document.getElementById('package-qty'+original_option_id).value ;
      let package_manual_cost = document.getElementById('package-manual-cost'+original_option_id).value ;

      package_manual_cost = package_manual_cost.replace(/,/g, '');
      package_manual_qty = package_manual_qty.replace(/,/g, '');
  
    
      let amount = package_manual_qty * package_manual_cost;

      amount = amount.toLocaleString(undefined, options);
      document.getElementById('package-manual-total-cost'+original_option_id).value = amount;
  }



  function calculatePart(option_id) {

    const options = {
      minimumFractionDigits: 2, // Ensures at least two decimal places
      maximumFractionDigits: 2, // Limits to a maximum of two decimal places
      style: 'decimal'          // Specifies decimal formatting
    };
    const original_option_id = option_id;
    option_id -=1;
    const inputElementsPart  =  document.getElementsByName('group-c['+option_id+'][part-option]');

     let get_part_qty = document.getElementById('part-qty-'+original_option_id).value;

    var inventory_id = inputElementsPart[0].value;
    if(inventory_id > '') {
    $.ajax({
      type: "get",
      url: '/app/check-inventory/'+ inventory_id,
        data:  {qty: get_part_qty},
        success: function (result) {
              if(result.success == false) {
                 document.getElementById('part-qty-'+original_option_id).value = 0;

                $(".alert-danger p").html(result.message);
                $(".alert-danger").removeClass("d-none");
                setTimeout(function(){ 
                  $(".alert-danger").addClass("d-none");
                }, 3000);
                return false;
              } else {

                  let part_qty = document.getElementById('part-qty-'+original_option_id).value ;
                  let part_price = document.getElementById('part-price-'+original_option_id).value ;

                  part_price = part_price.replace(/,/g,'');
                  part_qty = part_qty.replace(/[^0-9]/g, '');

                  console.log(part_price);
                  console.log(part_qty);
                  let amount = part_price * part_qty;
                  amount = amount.toLocaleString(undefined, options);
                  document.getElementById('part-amount-'+original_option_id).value = amount;
                  calculateAll(original_option_id);
              }
          },
          error: function (result, textStatus, errorThrown) {
            
          },
        });
    }
  }

  function calculateLabor(option_id) {

  const options = {
      minimumFractionDigits: 2, // Ensures at least two decimal places
      maximumFractionDigits: 2, // Limits to a maximum of two decimal places
      style: 'decimal'          // Specifies decimal formatting
    };


    if(option_id > 0) {
    // var job_order_id = document.getElementById('labor-option-'+option_id).value ;
      let labor_qty = document.getElementById('labor-qty-'+option_id).value ;
      let labor_price = document.getElementById('labor-price-'+option_id).value ;


      labor_price = labor_price.replace(/,/g,'');
      labor_qty = labor_qty.replace(/[^0-9]/g, '').toLocaleString(undefined, options);


      let labor_amount = labor_price * parseFloat(labor_qty);
      console.log(labor_price);
      console.log(labor_amount);
      labor_amount = labor_amount.toLocaleString(undefined, options);
      document.getElementById('labor-amount-'+option_id).value = labor_amount;
    
      calculateAll();
    }


    // $.ajax({
    //   type: "get",
    //   url: '/app/get-job-order-item-price/'+ job_order_id,
    //     data:  $("").serialize(),
    //     success: function (result) {

    //         const amount = result.price * labor_qty;
    //       document.getElementById('labor-price-'+option_id).value = result.price;
    //       document.getElementById('labor-amount-'+option_id).value = amount;
    //        calculateAll();

    //     },
    //   error: function (result, textStatus, errorThrown) {
    //       console.log(result.success);
    //   },
    // });
  }
  function copyPayment() {
     document.getElementById('balance').value = 0;
    $(".bt-save-changes").removeClass("disabled");
    sessionStorage.setItem("updateTriggered", "true");
   document.getElementById("payment").value = document.getElementById("total-amount").value;
  }

  function duplicateParts(job_order_id) {
         saveInvoice(job_order_id)

    $(".bt-save-changes").removeClass("disabled");
         

   var job_order_id = document.getElementById("hidden-job-order-id").value;
     $.ajax({
      type: "get",
      url: '/app/duplicate-parts/'+ job_order_id,
        data:  $("").serialize(),
        success: function (result) {
            console.log(result.jobOrderPartDuplicate);
          


        result.jobOrderPartDuplicate.forEach((value, index, self) => {
          console.log(value.part_value);
          const count = index+1;
          console.log(count);
          if(count > 10 && value.part_value > '') {
            addItem('labor');
          }

          if(value.part_value > '') {
            document.getElementById('labor-text-'+count).value = "Replace " + value.part_value;
            // document.getElementById('labor-part-number-'+count).value = value.part_number;
          }
        });
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });


  }

  


   function showCurrentStatus() {
    var status_id = document.getElementById('hidden-job-order-current-status').value ;
      $.ajax({
    type: "get",
    url: '/app/job-order/change-status/'+ status_id,
      data:  $("").serialize(),
      success: function (result) {
        console.log(result.value);
          document.getElementById('status-text').value = result.value.status_value.toUpperCase(); 
         if(result.value.status_id == 1) {
         $("#status-text").addClass("bg-label-warning");
          $("#status-text").removeClass("bg-label-info");
          $("#status-text").removeClass("alert-solid-success");
        } else if (result.value.status_id  == 2) {
          $("#status-text").addClass("bg-label-info");
          $("#status-text").removeClass("bg-label-warning");
          $("#status-text").removeClass("alert-solid-success");
        } else if (result.value.status_id  == 3) {
          $("#status-text").addClass("alert-solid-success");
          $("#status-text").removeClass("bg-label-info");
          $("#status-text").removeClass("bg-label-warning");
        } else {
          $("#status-text").removeClass("alert-solid-success");
          $("#status-text").removeClass("bg-label-info");
          $("#status-text").removeClass("bg-label-warning");
        }
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
   }


   
   function showStatus() {
    var status_id = document.getElementById('job-order-status').value ;
    var current_status = document.getElementById('hidden-job-order-current-status').value ;
    if(current_status !== status_id) {
        $('#addNewJobOrderUpdate').modal('show');
    }
     $.ajax({
    type: "get",
    url: '/app/job-order/change-status/'+ status_id,
      data:  $("").serialize(),
      success: function (result) {
        console.log(result.value);
        $("#upgrade-status").html(result.value.status_value.toUpperCase());
         if(result.value.status_id == 1) {
          // $("#status-text").addClass("bg-label-warning");
          $("#upgrade-status").addClass("bg-label-warning");
          $("#upgrade-status").removeClass("bg-label-info");
          $("#upgrade-status").removeClass("alert-solid-success");
          // $("#status-text").removeClass("bg-label-info");
          // $("#status-text").removeClass("alert-solid-success");

          document.getElementById('hidden-job-order-new-status').value = result.value.status_id; 
        } else if (result.value.status_id  == 2) {
          // $("#status-text").addClass("bg-label-info");
          $("#upgrade-status").addClass("bg-label-info");
          $("#upgrade-status").removeClass("bg-label-warning");
          $("#upgrade-status").removeClass("alert-solid-success");
          // $("#status-text").removeClass("bg-label-warning");
          // $("#status-text").removeClass("alert-solid-success");
          document.getElementById('hidden-job-order-new-status').value = result.value.status_id; 

        } else if (result.value.status_id  == 3) {
          // $("#status-text").addClass("alert-solid-success");
          $("#upgrade-status").addClass("alert-solid-success");
          $("#upgrade-status").removeClass("bg-label-info");
          $("#upgrade-status").removeClass("bg-label-warning");
          // $("#status-text").removeClass("bg-label-info");
          // $("#status-text").removeClass("bg-label-warning");
          document.getElementById('hidden-job-order-new-status').value = result.value.status_id; 

        } else {
          // $("#status-text").removeClass("alert-solid-success");
          $("#upgrade-status").addClass("alert-solid-success");
          $("#upgrade-status").removeClass("bg-label-info");
          $("#upgrade-status").removeClass("bg-label-warning");
          // $("#status-text").removeClass("bg-label-info");
          // $("#status-text").removeClass("bg-label-warning");
          document.getElementById('status-text').value =""; 

        }
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
     
  }

  
 function doChangeStatus() {
  var job_order_id = document.getElementById('hidden-job-order-id').value;
  var job_order_new_status = document.getElementById('hidden-job-order-new-status').value;
  $.ajax({
    type: "get",
    url: '/app/job-order/status-upgrade-now/'+job_order_id,
      data:  {job_order_new_status: job_order_new_status},
      success: function (result) {
          if(result.success == true) {
            $('#addNewJobOrderUpdate').modal('hide');
            $(".loader").removeClass("d-none");
            setTimeout(function(){ 
             window.location.replace("/app/job-order/"+result.newJobOrderId);
            }, 3000);
          }
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }

  function populateOption(element) {
    var id = element.id; 
    // var part_id = document.getElementById(element.id).value ;
    let numberValue = Number(element.id);



    // var text_field_id = id.replace(/\D/g, "");
const first = element -1;
  const inputElements  =  document.getElementsByName('group-c['+first+'][part-option]');
  const inputValue = inputElements[0].value;
    $.ajax({
    type: "get",
    url: '/app/job-order/get-selected-part/'+ inputValue,
      data:  $("").serialize(),
      success: function (result) {
        document.getElementById("part-text-"+element).value = result.value;
        // document.getElementById("part-part-note-"+element).value = result.part_note;
        document.getElementById("part-part-number-"+element).value = result.part_number;
        document.getElementById("part-unit-cost-"+element).value = result.cost;
        document.getElementById("part-price-"+element).value = result.price;

        calculatePart(element);
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });
  }


// var copyTextareaBtn = document.querySelector('.js-textareacopybtn');

// copyTextareaBtn.addEventListener('click', function(event) {
//   var copyTextarea = document.querySelector('.js-copytextarea');
//   copyTextarea.focus();
//   copyTextarea.select();

//   try {
//     var successful = document.execCommand('copy');
//     var msg = successful ? 'successful' : 'unsuccessful';
//     console.log('Copying text command was ' + msg);
//   } catch (err) {
//     console.log('Oops, unable to copy');
//   }
// });

  function showNoteAlert(int) {
    $("#icon-"+int).css("display", "block");
    setTimeout(function(){ 
      $("#icon-"+int).css("display", "none");
    }, 1000);

      var copyTextarea = document.querySelector('#js-copytextarea-'+int);
      copyTextarea.focus();
      copyTextarea.select();

      try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
      } catch (err) {
        console.log('Oops, unable to copy');
      }



  }
 $("select").change(function() {
    $(".bt-save-changes").removeClass("disabled");
    sessionStorage.setItem("updateTriggered", "true"); 
 });

  $("textarea").keyup(function() {
    $(".bt-save-changes").removeClass("disabled");
    sessionStorage.setItem("updateTriggered", "true");
 });
 
 $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
    
  $("input").keyup(function() {
    sessionStorage.setItem("updateTriggered", "true");
    $(".bt-save-changes").removeClass("disabled");
    console.log(this.className);
    var option_id = this.id.replace(/[^0-9]/g, '');
    if (this.className == 'form-control invoice-payment mb-3 text-right' || this.className == 'form-control invoice-item-price package mb-3') {
      calculateAll();
    } 
     if (typeof (this.className === 'form-control invoice-item-qty labor' || this.className === 'form-control invoice-item-price labor')) {
      calculateLabor(option_id);
    }
    
     if (typeof (this.className === 'form-control package-manual-qty' || this.className === 'form-control package-manual-srp-labor')) {
      calculatePackageManual(option_id);

    }

    if (typeof (this.className === 'form-control invoice-item-qty part' || this.className === 'form-control invoice-item-price part')) {
      calculatePart(option_id);
    }else {
      calculateAll();
    }
  });

  

  // setInterval(function(){
  // var job_order_id = document.getElementById("hidden-job-order-id").value;
  // console.log("test");
  //   saveInvoice(job_order_id)
  // }, 10000)

    
  function copyParts(element) {
    const first = element -1;
    const inputElements  =  document.getElementsByName('group-c['+first+'][part-option]');
    const inputValue = inputElements[0].value;


    $.ajax({
    type: "get",
    url: '/app/job-order/get-selected-part/'+ inputValue,
      data:  $("").serialize(),
      success: function (result) {
        // document.getElementById("part-text-"+element).value = result.value;


          let myVariableValue = result.value;

          // 2. Use navigator.clipboard.writeText() to copy the value
          navigator.clipboard.writeText(myVariableValue)
            .then(() => {
              // Optional: Provide feedback to the user that the copy was successful
              console.log('Text copied to clipboard successfully!');
            })
            .catch(err => {
              // Optional: Handle any errors during the copy operation
              console.error('Failed to copy text: ', err);
            });



         $("#icon-part-"+element).css("display", "block");
          setTimeout(function(){ 
            $("#icon-part-"+element).css("display", "none");
          }, 1000);

            // var inputElements  =  document.getElementsByName('group-c['+first+'][part-option]');
       
                 

            try {
              var successful = document.execCommand('copy');
              var msg = successful ? 'successful' : 'unsuccessful';
              console.log('Copying text command was ' + msg);
            } catch (err) {
              console.log('Oops, unable to copy');
            }


        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });


  }

  function recalculateAll() {
    setTimeout(function(){ 
       calculateAll();
     }, 1000);
  }



  function changeColor(item) {
    var val = document.getElementById('font-color-'+item).checked;
    if(val == false) {
        $("#labor-text-"+item).removeClass("c-red");
    } else {
      $("#labor-text-"+item).addClass("c-red");
    }
    $(".bt-save-changes").removeClass("disabled");
  }
  function addPayment() {
    $(".second-payment").removeClass("d-none");
  }
  function calculateAll(id) {
    $(".bt-save-changes").removeClass("disabled");
    
    // $(".invoice-actions").addClass("fixed-section");

  const options = {
    minimumFractionDigits: 2, // Ensures at least two decimal places
    maximumFractionDigits: 2, // Limits to a maximum of two decimal places
    style: 'decimal'          // Specifies decimal formatting
  };


    const myElement1 = document.getElementById('package-price-0');
    if (myElement1) {
        var p_amount1 = 0;
    } else {
        var p_amount1  = 0;
    }
    const myElement2 = document.getElementById('package-price-1');
    if (myElement2) {
        var p_amount2 = myElement2.value.replace(/,/g, '');
    } else {
        var p_amount2  = 0;
    }
    const myElement3 = document.getElementById('package-price-2');
    if (myElement3) {
        var p_amount3 = myElement3.value.replace(/,/g, '');
    } else {
        var p_amount3  = 0;
    }
    const myElement4 = document.getElementById('package-price-3');
    if (myElement4) {
        var p_amount4 = myElement4.value.replace(/,/g, '');
    } else {
        var p_amount4  = 0;
    }


    if(p_amount1 > 0) {
      // document.getElementById('package-note2-0').value = "";
    }

    var p_myElementPackageManual1 = 0;
    var p_myElementPackageManual2 = 0;
    var p_myElementPackageManual3 = 0;
    var p_myElementPackageManual4 = 0;
    var p_myElementPackageManual5 = 0;
    var p_myElementPackageManual6 = 0;
    var p_myElementPackageManual7 = 0;

    const myElementPackageManual1 = document.getElementById('package-manual-srp-labor0');
    if (myElementPackageManual1) {
        var p_myElementPackageManual1 = myElementPackageManual1.value.replace(/,/g, '');
    }

    const myElementPackageManual2 = document.getElementById('package-manual-srp-labor1');
    if (myElementPackageManual2) {
        var p_myElementPackageManual2 = myElementPackageManual2.value.replace(/,/g, '');
    } 

    const myElementPackageManual3 = document.getElementById('package-manual-srp-labor2');
    if (myElementPackageManual3) {
        var p_myElementPackageManual3 = myElementPackageManual3.value.replace(/,/g, '');
    } 
    const myElementPackageManual4 = document.getElementById('package-manual-srp-labor3');
    if (myElementPackageManual4) {
        var p_myElementPackageManual4 = myElementPackageManual4.value.replace(/,/g, '');
    } 

    const myElementPackageManual5 = document.getElementById('package-manual-srp-labor4');
    if (myElementPackageManual5) {
        var p_myElementPackageManual5 = myElementPackageManual5.value.replace(/,/g, '');
    }

    const myElementPackageManual6 = document.getElementById('package-manual-srp-labor5');
    if (myElementPackageManual6) {
        var p_myElementPackageManual6 = myElementPackageManual6.value.replace(/,/g, '');
    }

    const myElementPackageManual7 = document.getElementById('package-manual-srp-labor6');
    if (myElementPackageManual7) {
        var p_myElementPackageManual7 = myElementPackageManual7.value.replace(/,/g, '');
    } 



   

    // const package_sub_total = parseFloat(p_amount1)+parseFloat(p_amount2)+parseFloat(p_amount3)+parseFloat(p_amount4);

    const package_sub_total = parseFloat(p_myElementPackageManual1)+parseFloat(p_myElementPackageManual2)+parseFloat(p_myElementPackageManual3)+parseFloat(p_myElementPackageManual4)+parseFloat(p_myElementPackageManual5)+parseFloat(p_myElementPackageManual6)+parseFloat(p_myElementPackageManual7);
    document.getElementById('hidden-package-sub-totals').value = package_sub_total;

    // Package Manual

  //  if(id) {
  //      const package_manual_qty = document.getElementById('package-qty'+id).value ;
  //     const package_manual_cost = document.getElementById('package-manual-cost'+id).value ;
  //     const package_manual_total_cost = package_manual_qty * package_manual_cost;
  //     document.getElementById('package-manual-total-cost'+id).value =  truncateToTwoDecimals(package_manual_total_cost); 
  // }


 
    
      //Labor
    const myElementLabor1 = document.getElementById('labor-amount-1');
    if (myElementLabor1) {
        var labor_amount1 = myElementLabor1.value.replace(/,/g, '') ;
    } else {
        var labor_amount1  = 0;
    }
    const myElementLabor2 = document.getElementById('labor-amount-2');
    if (myElementLabor2) {
        var labor_amount2 = myElementLabor2.value.replace(/,/g, '');
    } else {
        var labor_amount2  = 0;
    }
    const myElementLabor3 = document.getElementById('labor-amount-3');
    if (myElementLabor3) {
        var labor_amount3 = myElementLabor3.value.replace(/,/g, '');
    } else {
        var labor_amount3  = 0;
    }
     const myElementLabor4 = document.getElementById('labor-amount-4');
    if (myElementLabor4) {
        var labor_amount4 = myElementLabor4.value.replace(/,/g, '');
    } else {
        var labor_amount4  = 0;
    }
     const myElementLabor5 = document.getElementById('labor-amount-5');
    if (myElementLabor5) {
        var labor_amount5 = myElementLabor5.value.replace(/,/g, '');
    } else {
        var labor_amount5  = 0;
    }
    const myElementLabor6 = document.getElementById('labor-amount-6');
    if (myElementLabor6) {
        var labor_amount6 = myElementLabor6.value.replace(/,/g, '');
    } else {
        var labor_amount6  = 0;
    }
     const myElementLabor7 = document.getElementById('labor-amount-7');
    if (myElementLabor7) {
        var labor_amount7 = myElementLabor7.value.replace(/,/g, '');
    } else {
        var labor_amount7  = 0;
    }
     const myElementLabor8 = document.getElementById('labor-amount-8');
    if (myElementLabor8) {
        var labor_amount8 = myElementLabor8.value.replace(/,/g, '');
    } else {
        var labor_amount8  = 0;
    }
     const myElementLabor9 = document.getElementById('labor-amount-9');
    if (myElementLabor9) {
        var labor_amount9 = myElementLabor9.value.replace(/,/g, '');
    } else {
        var labor_amount9  = 0;
    }
     const myElementLabor10 = document.getElementById('labor-amount-10');
    if (myElementLabor10) {
        var labor_amount10 = myElementLabor10.value.replace(/,/g, '');
    } else {
        var labor_amount10  = 0;
    }
     const myElementLabor11 = document.getElementById('labor-amount-11');
    if (myElementLabor11) {
        var labor_amount11 = myElementLabor11.value.replace(/,/g, '');
    } else {
        var labor_amount11  = 0;
    }
     const myElementLabor12 = document.getElementById('labor-amount-12');
    if (myElementLabor12) {
        var labor_amount12 = myElementLabor12.value.replace(/,/g, '');
    } else {
        var labor_amount12  = 0;
    }
    const myElementLabor13 = document.getElementById('labor-amount-13');
    if (myElementLabor13) {
        var labor_amount13 = myElementLabor13.value.replace(/,/g, '');
    } else {
        var labor_amount13  = 0;
    }
    const myElementLabor14 = document.getElementById('labor-amount-14');
    if (myElementLabor14) {
        var labor_amount14 = myElementLabor14.value.replace(/,/g, '');
    } else {
        var labor_amount14  = 0;
    }
    const myElementLabor15 = document.getElementById('labor-amount-15');
    if (myElementLabor15) {
        var labor_amount15 = myElementLabor15.value.replace(/,/g, '');
    } else {
        var labor_amount15  = 0;
    }


      const myElementLabor16 = document.getElementById('labor-amount-16');
    if (myElementLabor16) {
        var labor_amount16 = myElementLabor16.value.replace(/,/g, '');
    } else {
        var labor_amount16  = 0;
    }
      const myElementLabor17 = document.getElementById('labor-amount-17');
    if (myElementLabor17) {
        var labor_amount17 = myElementLabor17.value.replace(/,/g, '');
    } else {
        var labor_amount15  = 0;
    }
      const myElementLabor18 = document.getElementById('labor-amount-18');
    if (myElementLabor18) {
        var labor_amount18 = myElementLabor18.value.replace(/,/g, '');
    } else {
        var labor_amount18  = 0;
    }
      const myElementLabor19 = document.getElementById('labor-amount-19');
    if (myElementLabor19) {
        var labor_amount19 = myElementLabor19.value.replace(/,/g, '');
    } else {
        var labor_amount19  = 0;
    }
      const myElementLabor20 = document.getElementById('labor-amount-20');
    if (myElementLabor20) {
        var labor_amount20 = myElementLabor20.value.replace(/,/g, '');
    } else {
        var labor_amount20  = 0;
    }
    const labor_sub_total = parseFloat(labor_amount1)+parseFloat(labor_amount2)+parseFloat(labor_amount3)+parseFloat(labor_amount4)+parseFloat(labor_amount5)+parseFloat(labor_amount6)+parseFloat(labor_amount7)+parseFloat(labor_amount8)+parseFloat(labor_amount9)+parseFloat(labor_amount10)+parseFloat(labor_amount11)+parseFloat(labor_amount12)+parseFloat(labor_amount13)+parseFloat(labor_amount14)+parseFloat(labor_amount15)+parseFloat(labor_amount16)+parseFloat(labor_amount17)+parseFloat(labor_amount18)+parseFloat(labor_amount19)+parseFloat(labor_amount20);
    document.getElementById('hidden-labor-sub-totals').value = labor_sub_total;
    document.getElementById('labor-total').value = labor_sub_total.toLocaleString(undefined, options);



    //Parts
    const myElementPart1 = document.getElementById('part-amount-1');
    if (myElementPart1) {
        var prt_amount1 = myElementPart1.value.replace(/,/g, '');
    } else {
        var prt_amount1  = 0;
    }
    const myElementPart2 = document.getElementById('part-amount-2');
    if (myElementPart2) {
        var prt_amount2 = myElementPart2.value.replace(/,/g, '');
    } else {
        var prt_amount2  = 0;
    }
    const myElementPart3 = document.getElementById('part-amount-3');
    if (myElementPart3) {
        var prt_amount3 = myElementPart3.value.replace(/,/g, '');
    } else {
        var prt_amount3  = 0;
    }
     const myElementPart4 = document.getElementById('part-amount-4');
    if (myElementPart4) {
        var prt_amount4 = myElementPart4.value.replace(/,/g, '');
    } else {
        var prt_amount4  = 0;
    }
     const myElementPart5 = document.getElementById('part-amount-5');
    if (myElementPart5) {
        var prt_amount5 = myElementPart5.value.replace(/,/g, '');
    } else {
        var prt_amount5  = 0;
    }
    const myElementPart6 = document.getElementById('part-amount-6');
    if (myElementPart6) {
        var prt_amount6 = myElementPart6.value.replace(/,/g, '');
    } else {
        var prt_amount6  = 0;
    }
     const myElementPart7 = document.getElementById('part-amount-7');
    if (myElementPart7) {
        var prt_amount7 = myElementPart7.value.replace(/,/g, '');
    } else {
        var prt_amount7  = 0;
    }
     const myElementPart8 = document.getElementById('part-amount-8');
    if (myElementPart8) {
        var prt_amount8 = myElementPart8.value.replace(/,/g, '');
    } else {
        var prt_amount8  = 0;
    }
     const myElementPart9 = document.getElementById('part-amount-9');
    if (myElementPart9) {
        var prt_amount9 = myElementPart9.value.replace(/,/g, '');
    } else {
        var prt_amount9  = 0;
    }
     const myElementPart10 = document.getElementById('part-amount-10');
    if (myElementPart10) {
        var prt_amount10 = myElementPart10.value.replace(/,/g, '');
    } else {
        var prt_amount10  = 0;
    }
     const myElementPart11 = document.getElementById('part-amount-11');
    if (myElementPart11) {
        var prt_amount11 = myElementPart11.value.replace(/,/g, '');
    } else {
        var prt_amount11  = 0;
    }
     const myElementPart12 = document.getElementById('part-amount-12');
    if (myElementPart12) {
        var prt_amount12 = myElementPart12.value.replace(/,/g, '');
    } else {
        var prt_amount12  = 0;
    }
    const myElementPart13 = document.getElementById('part-amount-13');
    if (myElementPart13) {
        var prt_amount13 = myElementPart13.value.replace(/,/g, '');
    } else {
        var prt_amount13  = 0;
    }
    const myElementPart14 = document.getElementById('part-amount-14');
    if (myElementPart14) {
        var prt_amount14 = myElementPart14.value.replace(/,/g, '');
    } else {
        var prt_amount14  = 0;
    }
    const myElementPart15 = document.getElementById('part-amount-15');
    if (myElementPart15) {
        var prt_amount15 = myElementPart15.value.replace(/,/g, '');
    } else {
        var prt_amount15  = 0;
    }

      const myElementPart16 = document.getElementById('part-amount-16');
    if (myElementPart16) {
        var prt_amount16 = myElementPart16.value.replace(/,/g, '');
    } else {
        var prt_amount16  = 0;
    }
      const myElementPart17 = document.getElementById('part-amount-17');
    if (myElementPart17) {
        var prt_amount17 = myElementPart17.value.replace(/,/g, '');
    } else {
        var prt_amount17  = 0;
    }
      const myElementPart18 = document.getElementById('part-amount-18');
    if (myElementPart18) {
        var prt_amount18 = myElementPart18.value.replace(/,/g, '');
    } else {
        var prt_amount18  = 0;
    }
      const myElementPart19 = document.getElementById('part-amount-19');
    if (myElementPart19) {
        var prt_amount19 = myElementPart19.value.replace(/,/g, '');
    } else {
        var prt_amount19  = 0;
    }

      const myElementPart20 = document.getElementById('part-amount-20');
    if (myElementPart20) {
        var prt_amount20 = myElementPart20.value.replace(/,/g, '');
    } else {
        var prt_amount20  = 0;
    }
    const part_sub_total = parseFloat(prt_amount1)+parseFloat(prt_amount2)+parseFloat(prt_amount3)+parseFloat(prt_amount4)+parseFloat(prt_amount5)+parseFloat(prt_amount6)+parseFloat(prt_amount7)+parseFloat(prt_amount8)+parseFloat(prt_amount9)+parseFloat(prt_amount10)+parseFloat(prt_amount11)+parseFloat(prt_amount12)+parseFloat(prt_amount13)+parseFloat(prt_amount14)+parseFloat(prt_amount15)+parseFloat(prt_amount16)+parseFloat(prt_amount17)+parseFloat(prt_amount18)+parseFloat(prt_amount19)+parseFloat(prt_amount20);

    document.getElementById('hidden-part-sub-totals').value = part_sub_total;
    document.getElementById('part-total').value = part_sub_total.toLocaleString(undefined, options);


    const total_sub = parseFloat(package_sub_total) + parseFloat(labor_sub_total) + parseFloat(part_sub_total); 
    const amount_net_vat =  parseFloat(total_sub) / 1.12;

    const vat = parseFloat(amount_net_vat) * 0.12;

     const total_sub_with_vat = parseFloat(total_sub) + parseFloat(vat);
      
    document.getElementById('sub-total').value =  total_sub.toLocaleString(undefined, options); 
    document.getElementById('amount-net-vat').value =  amount_net_vat.toLocaleString(undefined, options); 



    document.getElementById('vat').value =  vat.toLocaleString(undefined, options); 

     const discount =  document.getElementById('discount').value;

    if(discount > 0) {
      const caculateWithDiscount =  parseFloat(total_sub) -  parseFloat(discount);
      document.getElementById('total-amount').value =  caculateWithDiscount.toLocaleString(undefined, options); 
    } else {
    document.getElementById('total-amount').value =  total_sub.toLocaleString(undefined, options); 

    }

    var payment =  document.getElementById('payment').value;

     var payment2 = document.getElementById('payment2').value;
    console.log(payment);

      payment = payment.replace(/,/g, ""); 
      payment2 = payment2.replace(/,/g, "");


     const total_amount =  document.getElementById('total-amount').value;


     if(payment2 > 0) {
        const final_total_amount = total_amount.replace(/,/g, "");
        var balance = parseFloat(final_total_amount) - parseFloat(payment) -  parseFloat(payment2);
     } else {
      const final_total_amount = total_amount.replace(/,/g, "");
        var balance = parseFloat(final_total_amount) - parseFloat(payment);

    }

    console.log(balance);
    console.log(balance);
    document.getElementById('balance').value =  balance.toLocaleString(undefined, options); 


 if(id) {
       const unit_cost = document.getElementById('part-unit-cost-'+id).value ;
      const part_qty = document.getElementById('part-qty-'+id).value ;

      console.log("check:");

      const total_cost = part_qty * unit_cost;

      document.getElementById('part-total-cost-'+id).value =  truncateToTwoDecimals(total_cost); 
 }

  }


  function filterOption() {
    document.getElementById("mop2").value = "";

    $("#option-cash").removeClass("d-none");
    $("#option-gcash").removeClass("d-none");
    $("#option-mobile").removeClass("d-none");
    $("#option-check_others").removeClass("d-none");

    var mop = document.getElementById("mop").value;
    $("#option-"+mop).addClass("d-none");
  }


  function editPaymentLabel() {
     $('#editPaymentLabel').modal('show');
      var label = document.getElementById("hidden-payment-label").value;
     document.getElementById("payment-label-field").value = label;
      var jo_id = document.getElementById("hidden-job-order-id").value;
     document.getElementById("hidden-job-order-id-payment-label").value = jo_id;


  }

  function truncateToTwoDecimals(num) {
  return Math.trunc(num * 100) / 100;
}

  function changePaymentLabel() {
      $.ajax({
      type: "get",
      url: '/app/change-payment-label/',
        data:  $("#editPaymentLabelForm").serialize(),
        success: function (result) {
            $('#editPaymentLabel').modal('hide');
            $(".alert-success p").html(result.message);
            $(".alert-success").removeClass("d-none");
            document.getElementById("hidden-payment-label").value = result.label;
            

            $("#display-payment").html(result.label);
            setTimeout(function(){ 
              $(".alert-success").addClass("d-none");
              const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
       
          }, 3000);
        },
      error: function (result, textStatus, errorThrown) {
          
      },
    });
  }



  const undoBtn = document.getElementById("undoBtn");
const redoBtn = document.getElementById("redoBtn");

undoBtn.addEventListener("click", () => {
    // Note: document.execCommand is deprecated, but widely used for this purpose
    document.execCommand('undo', false, null);
});

redoBtn.addEventListener("click", () => {
    document.execCommand('redo', false, null);
});