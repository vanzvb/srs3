$(document).ready(function () {
    $("#req_status_form").on("submit", function (_0x1f3ec6) {
      _0x1f3ec6.preventDefault();
      $("#stat_errror_msg").html('');
      $('#last_col').hide();
      $("#look_for_details").html('');
      $.ajax({
        'url': '/v3/sticker/request/status',
        'type': "POST",
        'data': {
          '_token': $("input[name=\"_token\"]").val(),
          'reqId': $("#req_id").val()
        },
        'success': function (_0x51fd1b) {
          if (_0x51fd1b) {
            var _0x83e825 = "<tr>\n                    <td>" + _0x51fd1b.request_id + "</td>\n                    <td>" + _0x51fd1b.request_date + "</td>";
            if (_0x51fd1b.status == 0x0) {
              if (_0x51fd1b.rejected == null || _0x51fd1b.rejected == '') {
                _0x83e825 += "\n                                <td>Pending</td>\n                            ";
              } else {
                _0x83e825 += "\n                            <td>\n                                Rejected\n                            </td>\n                            ";
                $('#look_for_details').html("\n                                <p style=\"color: #dc3545; font-size: .875em;\">\n                                <strong>\n                                    For more details, please check the email you provided.\n                                </strong>\n                                </p>\n                            ");
              }
            } else {
              if (_0x51fd1b.status == 0x1) {
                if (_0x51fd1b.rejected == null || _0x51fd1b.rejected == '') {
                  _0x83e825 += "\n                                <td>Pending - Last Approval</td>\n                            ";
                } else {
                  _0x83e825 += "\n                            <td>\n                                Rejected\n                            </td>\n                            ";
                  $("#look_for_details").html("\n                                <p style=\"color: #dc3545; font-size: .875em;\">\n                                <strong>\n                                    For more details, please check the email you provided.\n                                </strong>\n                                </p>\n                            ");
                }
              } else {
                if (_0x51fd1b.status == 0x2) {
                  _0x83e825 += "\n                        <td>Approved</td>\n                        ";
                } else {
                  if (_0x51fd1b.status == 0x3) {
                    $("#last_col").show();
                    $("#last_col").text("Appointment Date & Time");
                    _0x83e825 += "\n                        <td>\n                            Appointment Set\n                        </td>\n                        <td>\n                            " + _0x51fd1b.appointment + "\n                        </td>";
                  } else {
                    if (_0x51fd1b.status == 0x4) {
                      _0x83e825 += "<td>Closed</td>";
                    } else if (_0x51fd1b.status == 0x3d || _0x51fd1b.status == 0x3e) {
                      _0x83e825 += "\n                        <td>\n                            Rejected\n                        </td>\n                        ";
                      $("#look_for_details").html("\n                                <p style=\"color: #dc3545; font-size: .875em;\">\n                                <strong>\n                                    For more details, please check the email you provided.\n                                </strong>\n                                </p>\n                            ");
                    }
                  }
                }
              }
            }
            _0x83e825 += "</tr>";
            $("#stat_msg table tbody").html(_0x83e825);
            $("#stat_msg").show();
          }
        },
        'error': function (_0x3258a3) {
          var _0x4e51d3 = '';
          $.each(_0x3258a3.responseJSON.errors, function (_0x2205ec, _0x39285c) {
            _0x4e51d3 += "<p style=\"color: #dc3545; font-size: .875em;\"><strong>" + _0x39285c[0x0] + "</strong></p>";
          });
          $('#stat_msg').hide();
          $("#stat_msg table tbody").html('');
          $('#stat_errror_msg').html(_0x4e51d3);
        }
      });
    });
  });