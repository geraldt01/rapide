/**
 * App Invoice List (jquery)
 */

'use strict';

$(function () {

    setTimeout(() => {
    $('#btn-payment-confirm').html('Done Payment?');
    $('#btn-payment-confirm').addClass('btn-success-pay');
    $('#btn-payment-confirm').removeClass('click-disabled');
    $('#btn-payment-confirm').removeClass('click-disabled');
    $(".note-alert").removeClass("d-none");
  }, 8000);

});


  function paymentConfirm() {
    $(".loader").removeClass("d-none");
    $(".section-body").addClass("d-none");
    $.ajax({
      type: "get",
        url: 'https://hooks.zapier.com/hooks/catch/22946871/27119hc/?system_name=Rapide&reply_link=https://rapidepandanangeles.com"',
        data: "" ,
        success: function (result) {
          setTimeout(function(){ 
          $(".confirm-body").removeClass("d-none");
            $(".loader").addClass("d-none");
            const form = document.getElementById('editUserForm'); // Replace 'myForm' with your form's ID
        }, 3000);

        },
        error: function (data, textStatus, errorThrown) {
            console.log(data.success);

        },
      });
    }

    function notify() {
      $("#btn-payment-confirm-success").html("Done!");
          $.ajax({
          type: "get",
            url: 'https://hooks.zapier.com/hooks/catch/22946871/27119hc/?system_name=Rapide&reply_link=https://rapidepandanangeles.com"',
            data: "" ,
            success: function (result) {
            },
            error: function (data, textStatus, errorThrown) {
                console.log(data.success);

            },
          });
    }