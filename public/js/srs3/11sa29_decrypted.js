$(document).ready(function () {
    $.ajaxSetup({
      'headers': {
        'X-CSRF-TOKEN': $("meta[name=\"csrf-token\"]").attr("content")
      }
    });
    var _0x26b6cc = new Date();
    _0x26b6cc.setDate(_0x26b6cc.getDate() + 0x1);
    $("#date").attr("min", _0x26b6cc.toISOString().split('T')[0x0]);
    getAvailableTimeSlots = () => {
      $.ajax({
        'url': "/v3/sticker/appt/appt_timeslots",
        'data': {
          'date': $("#date").val(),
          'time': $("#time").val()
        },
        'success': function (_0x47a317) {
          var _0x26dd85 = "<div class=\"row\">";
          $.each(_0x47a317, function (_0x1f9874, _0x516612) {
            _0x26dd85 += "<div class=\"col-4 col-md-2 p-2\">\n                                <input type=\"radio\" class=\"btn-check\" name=\"timeslot\" id=\"time_" + _0x516612.formattedTime + "\" autocomplete=\"off\" value=\"" + _0x516612.formattedTime + "\">\n                                <label class=\"btn btn-outline-success\" for=\"time_" + _0x516612.formattedTime + "\">" + _0x516612.formattedTime + "</label>\n                             </div>";
          });
          _0x26dd85 += '</div>';
          $('#timeslots').html(_0x26dd85);
          $("#submit_appt_btn").show();
        },
        'error': function (_0x3519dd) {
          var _0x4e19f6 = _0x3519dd.responseJSON;
          var _0x1e5c75 = "<div style=\"color: red; font-weight: bold;\">";
          if (_0x4e19f6.weekend) {
            _0x1e5c75 += "<p>" + _0x4e19f6.weekend + "</p>";
          }
          if (_0x4e19f6.blocked) {
            _0x1e5c75 += "<p>" + _0x4e19f6.blocked + "</p>\n                             <p>No Appointment</p>\n                             <p>" + _0x4e19f6.datetime + "</p>";
          }
          if (_0x4e19f6.no_timeslots) {
            _0x1e5c75 += '<p>' + _0x4e19f6.no_timeslots + "</p>";
            $("#submit_appt_btn").hide();
          }
          _0x1e5c75 += "</div>";
          $("#timeslots").html(_0x1e5c75);
        }
      });
    };
    getAvailableTimeSlots();
    $("#time").on('change', function () {
      getAvailableTimeSlots();
    });
    $("#date").on("change", function () {
      getAvailableTimeSlots();
    });
    $("#setApptForm").on("submit", function (_0x5105cf) {
      _0x5105cf.preventDefault();
      window.scrollTo(0x0, 0x0);
      $("#request_load").show();
      if (!$("input[name=\"timeslot\"]:radio").is(":checked")) {
        return false;
      } else {
        $.ajax({
          'url': "/v3/sticker_appointment",
          'type': 'POST',
          'data': {
            'date': $('#date').val(),
            'timeslot': $("input[name=\"timeslot\"]:checked").val(),
            'srn': $("#srn").val()
          },
          'success': function (_0x4fe382) {
            var _0x13b802 = '';
            if (_0x4fe382.status == 0x1) {
              $("#apptFormDiv").html('');
              _0x13b802 += "<div class=\"alert alert-success\" role=\"alert\">\n                                    <div class=\"col-12 text-center\">\n                                        <strong>" + _0x4fe382.msg + "</strong>\n                                    </div>\n                                    <div class=\"col-12 mt-3 text-center\">\n                                        <strong>" + _0x4fe382.date + " " + _0x4fe382.time + "</strong>\n                                    </div>\n                                </div>";
            } else if (_0x4fe382.status == 0x0) {
              _0x13b802 += "<div class=\"alert alert-warning\" role=\"alert\">\n                                    <div class=\"col-12 text-center\">\n                                        <strong>" + _0x4fe382.msg + "</strong>\n                                    </div>\n                                    <div class=\"col-12 mt-3 text-center\">\n                                        <strong>Please select another time slot</strong>\n                                    </div>\n                                </div>";
              getAvailableTimeSlots();
            }
            $('#appointment_load').hide();
            $("#appointment_msg").html(_0x13b802);
          }
        });
      }
    });
  });