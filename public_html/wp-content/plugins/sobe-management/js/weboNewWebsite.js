(function($) {
  $.fn.checkInput = function(phpFunc, btnID) {
    $(this).blur(function(e) {
      var dataObj = {
        action: phpFunc,
        domain: $(this).val(),
        inputID: $(this).attr("id")
      };
      // console.log(dataObj);

      var inputID = "#" + dataObj.inputID;
      console.log("inputID = " + inputID);
      $.ajax({
        url: my_ajax_object.ajax_url, //admin-ajax.php?action=zendvn_check_form
        type: "GET",
        data: dataObj,
        dataType: "json",
        beforeSend: function() {
          // $(inputID)
          //   .next("span")
          //   .remove();
          // $(inputID).after("<span>Checking...</span>");
        },
        function(res) {
          console.log(res);
          // $(inputID)
          //   .next("span")
          //   .remove();
          // if (data.status == false) {
          //   $("#" + btnID).attr("disabled", "disabled");
          //   $(inputID).after("<span>" + data.errors.msg + "</span>");
          // } else {
          //   $("#" + btnID).removeAttr("disabled");
          //   $(inputID).after("<span>OK</span>");
          // }
        }
      });
    });
  };
})(jQuery);

jQuery(document).ready(function($) {
  // $("#domain").checkInput("checkDuplicate", "btn-save-change");

  $("#domain").blur(function() {
    var dataObj = {
      action: "checkDuplicate",
      domain: $(this).val(),
      inputID: $(this).attr("id")
    };

    $.ajax({
      url: my_ajax_object.ajax_url, //admin-ajax.php?action=zendvn_check_form
      type: "POST",
      data: dataObj,
      dataType: "json",
      beforeSend: function() {
        // $(inputID)
        //   .next("span")
        //   .remove();
        // $(inputID).after("<span>Checking...</span>");
      },
      function(res) {
        console.log(res);
        // $(inputID)
        //   .next("span")
        //   .remove();
        // if (data.status == false) {
        //   $("#" + btnID).attr("disabled", "disabled");
        //   $(inputID).after("<span>" + data.errors.msg + "</span>");
        // } else {
        //   $("#" + btnID).removeAttr("disabled");
        //   $(inputID).after("<span>OK</span>");
        // }
      }
    });
  });
});
