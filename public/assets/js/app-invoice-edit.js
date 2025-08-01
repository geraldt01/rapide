/**
 * App Invoice - Edit
 */

'use strict';

(function () {
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
      monthSelectorType: 'static',
      defaultDate: date
    });
  }
  if (dueDate) {
    dueDate.flatpickr({
      monthSelectorType: 'static',
      defaultDate: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 5)
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


  function addItem(type) {
    setTimeout(function(){ 
      let total_item = document.getElementById('hidden-'+type+'-total-item').value;
      let plus = 1;
      let total = parseInt(total_item)+plus;
      if(type == 'package') {
          document.getElementsByName('group-a['+total_item+']['+type+']')[0].id =  type+'-'+total;
          document.getElementsByName('group-a['+total_item+']['+type+'-price]')[0].id =  type+'-price-'+total;
          document.getElementsByName('group-a['+total_item+']['+type+']')[0].setAttribute("onchange", "calculatePackage("+total+")");
          // document.getElementsByName('group-a['+total_item+']['+type+'-price]')[0].setAttribute("onchange", "calculatePackage("+total+")");
      } else if (type == 'labor') {
          document.getElementsByName('group-b['+total_item+']['+type+']')[0].id =  type+'-option-'+total;
          document.getElementsByName('group-b['+total_item+']['+type+'-qty]')[0].id =  type+'-qty-'+total;
          document.getElementsByName('group-b['+total_item+']['+type+'-price]')[0].id =  type+'-price-'+total;
          // document.getElementsByName('group-b['+total_item+']['+type+'-number]')[0].id =  type+'-number-'+total;
          document.getElementsByName('group-b['+total_item+']['+type+']')[0].setAttribute("onchange", "calculateLabor("+total+")");
          document.getElementsByName('group-b['+total_item+']['+type+'-qty]')[0].setAttribute("onchange", "calculateLabor("+total+")");
          document.getElementsByName('group-b['+total_item+']['+type+'-price]')[0].setAttribute("onchange", "calculateLabor("+total+")");
          document.getElementsByName('group-b['+total_item+']['+type+'-amount]')[0].id =  'labor-amount-'+total;

          // document.getElementsByName('group-b['+total_item+']['+type+'-number]')[0].value =  total;

          setTimeout(function(){ 
            document.getElementById('labor-qty-'+total).value = 1;
          }, 800);


      } else {
          document.getElementsByName('group-c['+total_item+']['+type+']')[0].id =  type+'-option-'+total;
          document.getElementsByName('group-c['+total_item+']['+type+'-qty]')[0].id =  type+'-qty-'+total;
          document.getElementsByName('group-c['+total_item+']['+type+'-price]')[0].id =  type+'-price-'+total;
          document.getElementsByName('group-c['+total_item+']['+type+']')[0].setAttribute("onchange", "calculatePart("+total+")");
          document.getElementsByName('group-c['+total_item+']['+type+'-qty]')[0].setAttribute("onchange", "calculatePart("+total+")");
          document.getElementsByName('group-c['+total_item+']['+type+'-price]')[0].setAttribute("onchange", "calculatePart("+total+")");
          document.getElementsByName('group-c['+total_item+']['+type+'-amount]')[0].id =  'part-amount-'+total;

          setTimeout(function(){ 
            document.getElementById('part-qty-'+total).value = 1;
          }, 800);




      }
      document.getElementById('hidden-'+type+'-total-item').value = total;
    }, 1000);
  }

  function deleteItem(id, type) {
    var item_id = id;
    if(type == 1) {
      document.getElementById('part-option-'+id).remove();
      alert(document.getElementById('part-option-'+id).value );
      
    } else {
      document.getElementById('labor-option-'+id).value == "";
      alert(document.getElementById('labor-option-'+id).value );

    }
  }

  function saveInvoice(job_order_id) {
    $.ajax({
      type: "get",
      url: '/app/save-job-order-item/'+ job_order_id,
        data:  $("#form-job-order").serialize(),
        success: function (result) {
            $(".bt-save-changes").addClass("disabled");
            sessionStorage.setItem("updateTriggered", "false");
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
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
              $(".alert-danger p").html("Please enter valid items!");
            $(".alert-danger").removeClass("d-none");
            setTimeout(function(){ 
              $(".alert-danger").addClass("d-none");
              const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
              form.reset();
              location.reload();
          }, 3000);
      },
    });
  }

  function calculatePackage(option_id) {
    var package_id = document.getElementById('package-'+option_id).value ;
    $.ajax({
      type: "get",
      url: '/app/get-job-order-item-package-price/'+ package_id,
        data:  $("").serialize(),
        success: function (result) {

                 const amount = result.price * 1;
                document.getElementById('package-price-'+option_id).value = amount;
                calculateAll();
        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });

  }


  function calculatePart(option_id) {
    var job_order_id = document.getElementById('part-option-'+option_id).value ;
    var part_qty = document.getElementById('part-qty-'+option_id).value ;
    $.ajax({
      type: "get",
      url: '/app/get-job-order-item-price/'+ job_order_id,
        data:  $("").serialize(),
        success: function (result) {

                 const amount = result.price * part_qty;
                document.getElementById('part-price-'+option_id).value = result.price;
                document.getElementById('part-amount-'+option_id).value = amount;
                calculateAll();

        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });

  }
  function calculateLabor(option_id) {
    console.log(option_id);
    // var job_order_id = document.getElementById('labor-option-'+option_id).value ;
    var labor_qty = document.getElementById('labor-qty-'+option_id).value ;
    var labor_price = document.getElementById('labor-price-'+option_id).value ;

    var labor_amount = parseInt(labor_price) * parseInt(labor_qty);
    document.getElementById('labor-amount-'+option_id).value = labor_amount;
  
    calculateAll();

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
    url: '/app/job-order/status-upgrade/'+job_order_id,
      data:  $("#form-job-order").serialize(),
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
    var part_id = document.getElementById(element.id).value ;
    let numberValue = Number(element.id);

    var text_field_id = id.replace(/\D/g, "");

    $.ajax({
    type: "get",
    url: '/app/job-order/get-selected-part/'+ part_id,
      data:  $("").serialize(),
      success: function (result) {
        document.getElementById("part-text-"+text_field_id).value = result.value;
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
    
    if (typeof (this.className === 'form-control invoice-item-qty part' || this.className === 'form-control invoice-item-price part')) {
      calculatePart(option_id);
    }else {
      calculateAll();
    }
  });

  

  function calculateAll() {

  const options = {
    minimumFractionDigits: 2, // Ensures at least two decimal places
    maximumFractionDigits: 2, // Limits to a maximum of two decimal places
    style: 'decimal'          // Specifies decimal formatting
  };



    const myElement1 = document.getElementById('package-price-0');
    if (myElement1) {
        var p_amount1 = myElement1.value;
    } else {
        var p_amount1  = 0;
    }
    const myElement2 = document.getElementById('package-price-1');
    if (myElement2) {
        var p_amount2 = myElement2.value;
    } else {
        var p_amount2  = 0;
    }
    const myElement3 = document.getElementById('package-price-2');
    if (myElement3) {
        var p_amount3 = myElement3.value;
    } else {
        var p_amount3  = 0;
    }
    const myElement4 = document.getElementById('package-price-3');
    if (myElement4) {
        var p_amount4 = myElement4.value;
    } else {
        var p_amount4  = 0;
    }
    const package_sub_total = parseInt(p_amount1)+parseInt(p_amount2)+parseInt(p_amount3)+parseInt(p_amount4);
    document.getElementById('hidden-package-sub-totals').value = package_sub_total;

      //Labor
    const myElementLabor1 = document.getElementById('labor-amount-1');
    if (myElementLabor1) {
        var labor_amount1 = myElementLabor1.value;
    } else {
        var labor_amount1  = 0;
    }
    const myElementLabor2 = document.getElementById('labor-amount-2');
    if (myElementLabor2) {
        var labor_amount2 = myElementLabor2.value;
    } else {
        var labor_amount2  = 0;
    }
    const myElementLabor3 = document.getElementById('labor-amount-3');
    if (myElementLabor3) {
        var labor_amount3 = myElementLabor3.value;
    } else {
        var labor_amount3  = 0;
    }
     const myElementLabor4 = document.getElementById('labor-amount-4');
    if (myElementLabor4) {
        var labor_amount4 = myElementLabor4.value;
    } else {
        var labor_amount4  = 0;
    }
     const myElementLabor5 = document.getElementById('labor-amount-5');
    if (myElementLabor5) {
        var labor_amount5 = myElementLabor5.value;
    } else {
        var labor_amount5  = 0;
    }
    const myElementLabor6 = document.getElementById('labor-amount-6');
    if (myElementLabor6) {
        var labor_amount6 = myElementLabor6.value;
    } else {
        var labor_amount6  = 0;
    }
     const myElementLabor7 = document.getElementById('labor-amount-7');
    if (myElementLabor7) {
        var labor_amount7 = myElementLabor7.value;
    } else {
        var labor_amount7  = 0;
    }
     const myElementLabor8 = document.getElementById('labor-amount-8');
    if (myElementLabor8) {
        var labor_amount8 = myElementLabor8.value;
    } else {
        var labor_amount8  = 0;
    }
     const myElementLabor9 = document.getElementById('labor-amount-9');
    if (myElementLabor9) {
        var labor_amount9 = myElementLabor9.value;
    } else {
        var labor_amount9  = 0;
    }
     const myElementLabor10 = document.getElementById('labor-amount-10');
    if (myElementLabor10) {
        var labor_amount10 = myElementLabor10.value;
    } else {
        var labor_amount10  = 0;
    }
     const myElementLabor11 = document.getElementById('labor-amount-11');
    if (myElementLabor11) {
        var labor_amount11 = myElementLabor11.value;
    } else {
        var labor_amount11  = 0;
    }
     const myElementLabor12 = document.getElementById('labor-amount-12');
    if (myElementLabor12) {
        var labor_amount12 = myElementLabor12.value;
    } else {
        var labor_amount12  = 0;
    }
    const myElementLabor13 = document.getElementById('labor-amount-13');
    if (myElementLabor13) {
        var labor_amount13 = myElementLabor13.value;
    } else {
        var labor_amount13  = 0;
    }
    const myElementLabor14 = document.getElementById('labor-amount-14');
    if (myElementLabor14) {
        var labor_amount14 = myElementLabor14.value;
    } else {
        var labor_amount14  = 0;
    }
    const myElementLabor15 = document.getElementById('labor-amount-15');
    if (myElementLabor15) {
        var labor_amount15 = myElementLabor15.value;
    } else {
        var labor_amount15  = 0;
    }
    const labor_sub_total = parseInt(labor_amount1)+parseInt(labor_amount2)+parseInt(labor_amount3)+parseInt(labor_amount4)+parseInt(labor_amount5)+parseInt(labor_amount6)+parseInt(labor_amount7)+parseInt(labor_amount8)+parseInt(labor_amount9)+parseInt(labor_amount10)+parseInt(labor_amount11)+parseInt(labor_amount12)+parseInt(labor_amount13)+parseInt(labor_amount14)+parseInt(labor_amount15);
    document.getElementById('hidden-labor-sub-totals').value = labor_sub_total;
    document.getElementById('labor-total').value = labor_sub_total;



    //Parts
    const myElementPart1 = document.getElementById('part-amount-1');
    if (myElementPart1) {
        var prt_amount1 = myElementPart1.value;
    } else {
        var prt_amount1  = 0;
    }
    const myElementPart2 = document.getElementById('part-amount-2');
    if (myElementPart2) {
        var prt_amount2 = myElementPart2.value;
    } else {
        var prt_amount2  = 0;
    }
    const myElementPart3 = document.getElementById('part-amount-3');
    if (myElementPart3) {
        var prt_amount3 = myElementPart3.value;
    } else {
        var prt_amount3  = 0;
    }
     const myElementPart4 = document.getElementById('part-amount-4');
    if (myElementPart4) {
        var prt_amount4 = myElementPart4.value;
    } else {
        var prt_amount4  = 0;
    }
     const myElementPart5 = document.getElementById('part-amount-5');
    if (myElementPart5) {
        var prt_amount5 = myElementPart5.value;
    } else {
        var prt_amount5  = 0;
    }
    const myElementPart6 = document.getElementById('part-amount-6');
    if (myElementPart6) {
        var prt_amount6 = myElementPart6.value;
    } else {
        var prt_amount6  = 0;
    }
     const myElementPart7 = document.getElementById('part-amount-7');
    if (myElementPart7) {
        var prt_amount7 = myElementPart7.value;
    } else {
        var prt_amount7  = 0;
    }
     const myElementPart8 = document.getElementById('part-amount-8');
    if (myElementPart8) {
        var prt_amount8 = myElementPart8.value;
    } else {
        var prt_amount8  = 0;
    }
     const myElementPart9 = document.getElementById('part-amount-9');
    if (myElementPart9) {
        var prt_amount9 = myElementPart9.value;
    } else {
        var prt_amount9  = 0;
    }
     const myElementPart10 = document.getElementById('part-amount-10');
    if (myElementPart10) {
        var prt_amount10 = myElementPart10.value;
    } else {
        var prt_amount10  = 0;
    }
     const myElementPart11 = document.getElementById('part-amount-11');
    if (myElementPart11) {
        var prt_amount11 = myElementPart11.value;
    } else {
        var prt_amount11  = 0;
    }
     const myElementPart12 = document.getElementById('part-amount-12');
    if (myElementPart12) {
        var prt_amount12 = myElementPart12.value;
    } else {
        var prt_amount12  = 0;
    }
    const myElementPart13 = document.getElementById('part-amount-13');
    if (myElementPart13) {
        var prt_amount13 = myElementPart13.value;
    } else {
        var prt_amount13  = 0;
    }
    const myElementPart14 = document.getElementById('part-amount-14');
    if (myElementPart14) {
        var prt_amount14 = myElementPart14.value;
    } else {
        var prt_amount14  = 0;
    }
    const myElementPart15 = document.getElementById('part-amount-15');
    if (myElementPart15) {
        var prt_amount15 = myElementPart15.value;
    } else {
        var prt_amount15  = 0;
    }
    const part_sub_total = parseInt(prt_amount1)+parseInt(prt_amount2)+parseInt(prt_amount3)+parseInt(prt_amount4)+parseInt(prt_amount5)+parseInt(prt_amount6)+parseInt(prt_amount7)+parseInt(prt_amount8)+parseInt(prt_amount9)+parseInt(prt_amount10)+parseInt(prt_amount11)+parseInt(prt_amount12)+parseInt(prt_amount13)+parseInt(prt_amount14)+parseInt(prt_amount15);

    document.getElementById('hidden-part-sub-totals').value = part_sub_total;
    document.getElementById('part-total').value = part_sub_total;



    const total_sub = parseInt(package_sub_total) + parseInt(labor_sub_total) + parseInt(part_sub_total); 
    const vat = parseInt(total_sub) * 0.12;

     const total_sub_with_vat = parseInt(total_sub) + parseInt(vat);
      
    document.getElementById('sub-total').value =  total_sub.toLocaleString(undefined, options); 

    document.getElementById('vat').value =  vat.toLocaleString(undefined, options); 
    document.getElementById('total-amount').value =  total_sub_with_vat.toLocaleString(undefined, options); 

     const payment = document.getElementById('payment').value;
     const balance = parseInt(total_sub_with_vat) - parseInt(payment);

    document.getElementById('balance').value =  balance.toLocaleString(undefined, options); 






 

  }
