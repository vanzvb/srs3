$(document).ready(function () {
    $.ajaxSetup({
      'headers': {
        'X-CSRF-TOKEN': $("meta[name=\"csrf-token\"]").attr("content")
      }
    });
    $("#sticker_renewal_form").on("submit", function (_0x5346e3) {
      _0x5346e3.preventDefault();
      let _0xdc3f5a = $(this).find("button[type=\"submit\"]");
      _0xdc3f5a.prop("disabled", true);
      _0xdc3f5a.html("<div class=\"spinner-border spinner-border-sm\" role=\"status\">\n                            <span class=\"visually-hidden\">Loading...</span>\n                        </div>\n                        <br>\n                        Submitting...");
      $('#request_renewal_msg').html('');
      $.ajax({
        'url': "/v3/sticker/request/renewal",
        'type': "post",
        'data': $(this).serialize(),
        'success': function (_0x4aa2ac) {
          if (_0x4aa2ac.status == 0x1) {
            $("#request_renewal_msg").html("<div class=\"alert alert-success col-md-8 p-3 mt-4\" role=\"alert\">\n                                    <div class=\"row text-center\">\n                                        <strong>Renewal Request Submitted Successfully!</strong>\n                                    </div>\n                                    <br>\n                                    <div class=\"row text-center\">\n                                        <p>Application renewal link will be sent to your email</p>\n                                    </div>\n                                </div>");
            $('#sticker_renewal_form').find('#email').val('');
            _0xdc3f5a.prop("disabled", false);
            _0xdc3f5a.html("Submit");
          }
        },
        'error': function (_0x482230) {
          var _0x17f9c8 = '';
          if (_0x482230.responseJSON.errors) {
            $.each(_0x482230.responseJSON.errors, function (_0x4ce116, _0x3a2a62) {
              _0x17f9c8 += _0x3a2a62 + "<br>";
            });
          } else {
            _0x17f9c8 += "Error Occured. Please refresh page and try again.";
          }
          var _0x1c4555 = "<div class=\"alert alert-danger col-md-8 p-3 mt-3\" role=\"alert\">\n                                <div class=\"text-center\">\n                                    <strong>" + _0x17f9c8 + "</strong>\n                                </div>\n                            </div>";
          $("#request_renewal_msg").html(_0x1c4555);
          _0xdc3f5a.prop("disabled", false);
          _0xdc3f5a.html("Submit");
        }
      });
    });
    $("#modal_vid_1").on("click", function (_0x10c846) {
      _0x10c846.preventDefault();
      $("#video01").show();
      $("#video02").hide();
      $("#video03").hide();
      $("#videoModal").show();
    });
    $('#modal_vid_2').on('click', function (_0x295efb) {
      _0x295efb.preventDefault();
      $('#video01').hide();
      $("#video02").show();
      $("#video03").hide();
      $("#videoModal").show();
    });
    $('#modal_vid_3').on("click", function (_0x267d89) {
      _0x267d89.preventDefault();
      $('#video01').hide();
      $("#video02").hide();
      $('#video03').show();
      $('#videoModal').show();
    });
    $(".closeVideoModal").on("click", function () {
      $("#videoModal").hide();
      $("#video01").trigger("pause");
      $('#video01').get(0x0).currentTime = 0x0;
      $("#video02").trigger("pause");
      $('#video02').get(0x0).currentTime = 0x0;
      $("#video03").trigger('pause');
      $("#video03").get(0x0).currentTime = 0x0;
    });
  });