$(document).ready(function () {
  $(document).on("click", ".modal_img", function (_0x4f57bf) {
    _0x4f57bf.preventDefault();
    if ($(this).attr("data-type") == 'pdf') {
      $('#embed01').attr('src', $(this).attr('data-value'));
      $("#img01").hide();
      $('#embed01').show();
    } else {
      $("#img01").attr("src", $(this).attr("data-value"));
    }
    $("#imgModal").show();
  });
  $(".closeImgModal").on('click', function () {
    $("#imgModal").hide();
    $("#embed01").hide();
    $("#embed01").attr('src', '');
    $("#img01").show();
  });
  $.ajaxSetup({
    'headers': {
      'X-CSRF-TOKEN': $("meta[name=\"csrf-token\"]").attr("content")
    }
  });
  $("#approve_btn").on('click', function () {
    Swal.fire({
      'title': "Are you sure?",
      'text': "You are about to submit!",
      'icon': 'warning',
      'showCancelButton': true,
      'confirmButtonColor': "#3085d6",
      'cancelButtonColor': '#d33',
      'confirmButtonText': "Yes, Submit it!"
    }).then(_0x483950 => {
      if (_0x483950.isConfirmed) {

    // Get unchecked vehicles
    let uncheckedVehicles = [];
    let rejectionReasons = {};  // To store rejection reasons
    // let validationPassed = true;  // Flag to check if all validation passes

        $("input[name='selectedVehicles[]']:not(:checked)").each(function () {
          uncheckedVehicles.push($(this).val()); // Store the vehicle ID (value of checkbox)

          // Get the corresponding rejection reason (from the same row)
          let vehicleId = $(this).val();
          let rejectionReason = $(this).closest('tr').find('.rejection-remarks').val();
          
          // if (rejectionReason.trim() === '') {
          //   // If rejection reason is empty, flag validation as failed
          //   validationPassed = false;
          //   $(this).closest('tr').find('.rejection-remarks').css('border', '1px solid red');  // Optional: Highlight empty fields
          // } else {
          //   // Otherwise, store rejection reason
          //   rejectionReasons[vehicleId] = rejectionReason;
          // }
          rejectionReasons[vehicleId] = rejectionReason;
        });

        // If validation fails, show an error message and stop submission
        // if (!validationPassed) {
        //   Swal.fire({
        //     'title': 'Error!',
        //     'text': 'Please provide rejection reasons for all unchecked vehicles.',
        //     'icon': 'error',
        //     'showCancelButton': false,
        //     'confirmButtonColor': '#3085d6',
        //     'cancelButtonColor': '#d33',
        //     'confirmButtonText': 'Okay'
        //   });
        //   return;  // Stop further execution
        // }

        $.ajax({
          'url': '/hoa-approvers3/sticker/request/hoa_approval',
          'method': "POST",
          'data': {
            'request_id': $("#request_id").val(),
            uncheckedVehicles: uncheckedVehicles, // send unchecked vehicles data
            'rejection_reasons': rejectionReasons
          },
          'success': function (_0x2ddd50) {
            Swal.fire({
              'title': "Success!",
              'text': _0x2ddd50.msg,
              'icon': "success",
              'showCancelButton': false,
              'confirmButtonColor': "#3085d6",
              'cancelButtonColor': "#d33",
              'confirmButtonText': "Okay"
            }).then(_0x34c9ed => {
              $("#request_btns").addClass("d-none");
              $('#details_status').text(_0x2ddd50.status_text);
              $("#routelog_tbody tr:nth-child(3) td:nth-child(2)").text(_0x2ddd50.action_by);
              $("#routelog_tbody tr:nth-child(3) td:nth-child(3)").text(_0x2ddd50.updated_at);

                // Redirect to specified URL if provided
                  // if (_0x2ddd50.redirect_url) {
                    window.location.href = '/hoa-approvers3';
                // }
            });
          },
          'error': function (_0x3e621c) {
            Swal.fire({
              'title': "Error!",
              'text': "Something went wrong.",
              'icon': "error",
              'showCancelButton': false,
              'confirmButtonColor': "#3085d6",
              'cancelButtonColor': "#d33",
              'confirmButtonText': "Okay"
            });
          }
        });
      }
    });
  });
  $("#submit_reject_btn").on("click", function (_0xcd7302) {
    _0xcd7302.preventDefault();
    let _0x4501de = $("#reject_reason").val();
    let _0x8f60e4 = $("#request_id").val();
    if (_0x4501de == '' || _0x4501de == null) {
      Swal.fire({
        'title': "Invalid Reason!",
        'text': "Please indicate reason of rejection.",
        'icon': "warning",
        'showCancelButton': false,
        'confirmButtonColor': '#3085d6',
        'cancelButtonColor': '#d33',
        'confirmButtonText': "Okay"
      });
    } else {
      $.ajax({
        'url': '/hoa-approvers3/srs/request/' + _0x8f60e4,
        'method': 'DELETE',
        'data': {
          'reason': _0x4501de
        },
        'success': function (_0x16b08d) {
          Swal.fire({
            'title': "Success!",
            'text': _0x16b08d.msg,
            'icon': "success",
            'showCancelButton': false,
            'confirmButtonColor': '#3085d6',
            'cancelButtonColor': "#d33",
            'confirmButtonText': "Okay"
          }).then(_0x5a5dc7 => {
            $("#rejectRequestModal").modal("hide");
            $("#request_btns").addClass("d-none");
            $("#details_status").text(_0x16b08d.status_text).addClass("text-danger");
            let _0x42966e = "\n                     <tr class=\"text-danger fw-bold\">\n                        <td>Rejected</td>\n                        <td>" + _0x16b08d.action_by + "</td>\n                        <td>" + _0x16b08d.updated_at + "</td>\n                        <td>" + _0x16b08d.reject_reason + "</td>\n                     </tr>\n                  ";
            $("#routelog_tbody").append(_0x42966e);
          });
        },
        'error': function (_0x5f47c9) {
          Swal.fire({
            'title': "Error!",
            'text': "Something went wrong.",
            'icon': 'error',
            'showCancelButton': false,
            'confirmButtonColor': "#3085d6",
            'cancelButtonColor': "#d33",
            'confirmButtonText': "Okay"
          });
        }
      });
    }
  });
  $("#back_button").on("click", function () {
    window.history.back();
  });
  $("#refresh_button").on("click", function () {
    $("#refresh_button").html("<i class=\"fas fa-spinner fa-spin\"></i>");
    location.reload();
  });
});