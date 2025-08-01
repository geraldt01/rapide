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
    alert("fsrs");

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



  function addItem(type) {
    setTimeout(function(){ 
      let total_item = document.getElementById('hidden-'+type+'-total-item').value;
      let plus = 1;
      let total = parseInt(total_item)+plus;
      if(type == 'package') {
          document.getElementsByName('group-a['+total_item+']['+type+']')[0].id =  type+'-option-'+total;
          document.getElementsByName('group-a['+total_item+']['+type+'-qty]')[0].id =  type+'-qty-'+total;
          document.getElementsByName('group-a['+total_item+']['+type+'-price]')[0].id =  type+'-price-'+total;
          document.getElementsByName('group-a['+total_item+']['+type+']')[0].setAttribute("onchange", "calculatePackage("+total+")");
          document.getElementsByName('group-a['+total_item+']['+type+'-qty')[0].setAttribute("onchange", "calculatePackage("+total+")");
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

        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });


  }
  function calculateLabor(option_id) {
    var job_order_id = document.getElementById('labor-option-'+option_id).value ;
    var labor_qty = document.getElementById('labor-qty-'+option_id).value ;
    $.ajax({
      type: "get",
      url: '/app/get-job-order-item-price/'+ job_order_id,
        data:  $("").serialize(),
        success: function (result) {

                 const amount = result.price * labor_qty;
                document.getElementById('labor-price-'+option_id).value = result.price;
                document.getElementById('labor-amount-'+option_id).value = amount;

        },
      error: function (result, textStatus, errorThrown) {
          console.log(result.success);
      },
    });



  }