$(document).ready(function () {
    let _0x4d8ad3 = new Date();
    let _0x3ee702 = document.getElementById("year_model");
    let _0x6b0daf = _0x4d8ad3.getFullYear() + 0x1;
    while (_0x6b0daf >= 0x7b7) {
      let _0x4c4f09 = document.createElement('option');
      _0x4c4f09.text = _0x6b0daf;
      _0x4c4f09.value = _0x6b0daf;
      _0x3ee702.add(_0x4c4f09);
      _0x6b0daf -= 0x1;
    }
    var _0x20d540 = [];
    var _0x49e45d = [];
    var _0x3e1bf5 = [];
    var _0x35b3fd = "<option disabled selected value=\"\" style=\"color: grey;\">Please select HOA</option>";
    var _0x4ab78b = "<option disabled selected value=\"\" style=\"color: grey;\">Please select HOA</option>";
    var _0x123c57 = "<option disabled selected value=\"\" style=\"color: grey;\">Please select HOA</option>";
    var _0x3c5ccc = "<option value=\"\" style=\"color: grey;\">---</option>";
    const _0x5e4395 = document.querySelector('canvas');
    const _0x2d4d07 = new SignaturePad(_0x5e4395);
    let _0x5f50b5 = 0x1;
    let _0x4f4ff9 = $("#vehicle_tab").find("#v_req_type_tab").clone().html();
    let _0x18bd6c = $("#vehicle_tab").find("#v_plate_no_tab").clone().html();
    let _0xe2d04c = $("#vehicle_tab").find("#v_brand_tab").clone().html();
    let _0xd6a312 = $("#vehicle_tab").find("#v_series_tab").clone().html();
    let _0x5f3513 = $("#vehicle_tab").find('#v_year_model_tab').clone().html();
    let _0x482b28 = $("#vehicle_tab").find('#v_color_tab').clone().html();
    let _0x54376c = $("#vehicle_tab").find("#v_type_tab").clone().html();
    let _0x81c471 = $("#vehicle_tab").find('#v_sticker_no_tab').clone().html();
    let _0x18ed89 = $('#vehicle_tab').find("#v_or_tab").clone().html();
    let _0x392719 = $("#vehicle_tab").find("#v_cr_tab").clone().html();
    function _0x43e60d(_0x3de49b, _0x70ade0) {
      _0x3de49b.prop("disabled", true);
      _0x3de49b.hide();
      _0x70ade0.prop('disabled', false);
      _0x70ade0.show();
      _0x70ade0.focus();
    }
    $(document).on('change', "#brand", function () {
      if (this.options[this.selectedIndex].value == "Others") {
        _0x43e60d($(this), $(this).next('input'));
        this.selectedIndex = '0';
      }
    });
    $(document).on("blur", '#brand-input', function () {
      if (this.value == '') {
        _0x43e60d($(this), $(this).prev("select"));
      }
    });
    $(document).on("change", "#city", function () {
      if (this.value == "Others") {
        _0x43e60d($(this), $(this).next("input"));
        this.selectedIndex = '0';
      }
    });
    $(document).on("blur", "#city-input", function () {
      if (this.value == '') {
        console.log("test");
        _0x43e60d($(this), $(this).prev("select"));
      }
    });
    $(document).on("change", '#v_color', function () {
      if (this.options[this.selectedIndex].value == 'Others') {
        _0x43e60d($(this), $(this).next("input"));
        this.selectedIndex = '0';
      }
    });
    $(document).on("blur", '#v_color-input', function () {
      if (this.value == '') {
        _0x43e60d($(this), $(this).prev("select"));
      }
    });
    addVehicle = (_0x1fd64f = 0x1) => {
      _0x5f50b5++;
      let _0x58cdc9 = '';
      for (let _0xd0a980 = 0x0; _0xd0a980 < _0x1fd64f; _0xd0a980++) {
        _0x58cdc9 += "<div class=\"p-3 p-md-4 card shadow rounded mb-2 mb-md-4\">\n                   <div class=\"card-header text-end\" style=\"background-color: white; border-bottom: 0;\">\n                       <button class=\"btn-close v_remove_btn\"></button>\n                   </div>\n                   <div class=\"row mt-2 g-2\">\n                       <div class=\"col-6 col-md-3\">\n                           " + _0x4f4ff9 + "\n                       </div>\n                   </div>\n                   <div class=\"row mt-2 g-2\">\n                       <div class=\"col-6 col-md\">\n                           " + _0x18bd6c + "\n                       </div>\n                       <div class=\"col-6 col-md\">\n                           " + _0xe2d04c + "\n                       </div>\n                       <div class=\"col-6 col-md\">\n                           " + _0xd6a312 + "\n                       </div>\n                       <div class=\"col-6 col-md\">\n                           " + _0x5f3513 + "\n                       </div>\n                   </div>\n                   <div class=\"row mt-2 g-2 g-md-3\">\n                       <div class=\"col-6 col-md-3\">\n                           " + _0x482b28 + "\n                       </div>\n                       <div class=\"col-6 col-md-3\">\n                           " + _0x54376c + "\n                       </div>\n                       <div class=\"col-6 col-md-3 class-sticker_no\" style=\"display: none;\">\n                           " + _0x81c471 + "\n                       </div>\n                   </div>\n                   <div class=\"row mt-2 g-2 g-md-3\">\n                       <div class=\"col-12 col-md-3\">\n                           " + _0x18ed89 + "\n                       </div>\n                       <div class=\"col-12 col-md-3\">\n                           " + _0x392719 + "\n                       </div>\n                   </div>\n                   </div>";
      }
      $("#vehicles_row").append(_0x58cdc9);
    };
    getSubCategories = () => {
      $.ajax({
        'url': "/sticker/request/sub_categories",
        'data': {
          'category': $("#category").val()
        },
        'success': function (_0x59e6f0) {
          var _0x186c43 = '';
          $.each(_0x59e6f0, function (_0x474218, _0x475c44) {
            if (_0x475c44.category_id == 0x1) {
              _0x20d540.push({
                'value': _0x475c44.id,
                'name': _0x475c44.name
              });
              _0x186c43 += "<option value=\"" + _0x475c44.id + "\">" + _0x475c44.name + "</option>";
            } else {
              if (_0x475c44.category_id == 0x2) {
                _0x49e45d.push({
                  'value': _0x475c44.id,
                  'name': _0x475c44.name
                });
              } else if (_0x475c44.category_id == 0x3) {
                _0x3e1bf5.push({
                  'value': _0x475c44.id,
                  'name': _0x475c44.name
                });
              }
            }
          });
          $('#sub_category').html(_0x186c43);
          getRequirements();
        }
      });
    };
    getRequirements = () => {
      // $.ajax({
      //   'url': '/sticker/request/requirements',
      //   'data': {
      //     'sub_category': $("#sub_category").val()
      //   },
      //   'success': function (_0x45dc6d) {
      //     var _0x5cdd0e = '';
      //     if (_0x45dc6d.length) {
      //       $.each(_0x45dc6d, function (_0x457fd2, _0x40bd70) {
      //         let _0x38d18a = '';
      //         if (_0x40bd70.required) {
      //           _0x38d18a = 'required';
      //         }
      //         _0x5cdd0e += "<tr>\n                                   <td>\n                                       <div class=\"my-3\">\n                                           <label class=\"form-label\">" + _0x40bd70.description + "</label>\n                                       </div>\n                                   </td>\n                                   <td>\n                                       <div class=\"my-3\">\n                                           <input class=\"form-control form-control-sm\" type=\"file\" accept=\"image/*\" name=\"" + _0x40bd70.name + "\" id=\"\" " + _0x38d18a + ">\n                                       </div>\n                                   </td>\n                               </tr>";
      //       });
      //       $('#requirements_tbody').html(_0x5cdd0e);
      //       $("#requirements_table").show();
      //     } else {
      //       $("#requirements_tbody").html(_0x5cdd0e);
      //       $("#requirements_table").hide();
      //     }
      //   }
      // });
    };
    getHoas = () => {
      $.ajax({
        'url': "/sticker/request/hoas",
        'success': function (_0x2fa88d) {
          $.each(_0x2fa88d, function (_0x152a41, _0x113b46) {
            if (_0x113b46.type == 0x0 || _0x113b46.type == 0x3) {
              if (_0x113b46.type == 0x0) {
                _0x35b3fd += "<option value=\"" + _0x113b46.id + "\">" + _0x113b46.name + '</option>';
                _0x123c57 += "<option value=\"" + _0x113b46.id + "\">" + _0x113b46.name + "</option>";
              } else {
                _0x123c57 += "<option value=\"" + _0x113b46.id + "\">" + _0x113b46.name + "</option>";
              }
            } else if (_0x113b46.type == 0x1) {
              _0x4ab78b += "<option value=\"" + _0x113b46.id + "\">" + _0x113b46.name + "</option>";
            }
          });
          $('#hoa').html(_0x35b3fd);
        }
      });
    };
    getNRHoas = () => {
      $.ajax({
        'url': "/sticker/request/hoas/nr",
        'success': function (_0x47d61d) {
          $.each(_0x47d61d, function (_0xcb0b8d, _0x2c8cd0) {
            _0x3c5ccc += "<option value=\"" + _0x2c8cd0.id + "\">" + _0x2c8cd0.name + "</option>";
          });
        }
      });
    };
    toggleStickerNo = _0x38cc7c => {
      var _0x4db00d = _0x38cc7c.val();
      var _0x2a809f = _0x38cc7c.closest(".row").next().next().find(".class-sticker_no");
      if (_0x4db00d == 0x0) {
        _0x2a809f.hide();
        _0x2a809f.find(".form-floating input").val('');
      } else if (_0x4db00d == 0x1) {
        _0x2a809f.show();
        _0x2a809f.find(".form-floating input").val('');
      }
    };
    toggleUndertaking = () => {
      if ($('#checkboxCertify').is(":checked")) {
        $("#sticker_request_form button[type=\"submit\"]").prop("disabled", false);
        $("#undertaking_tab").show();
      } else {
        $("#sticker_request_form button[type=\"submit\"]").prop('disabled', true);
        $("#undertaking_tab").hide();
      }
    };
    toggleDOS = () => {
      if ($("#sub_category option:selected").text() == "Deed of Sale (DOS)") {
        $("#srs_tab").hide();
      } else {
        $('#srs_tab').show();
      }
      if ($("#sub_category option:selected").text() == "Deed of Sale (DOS)") {
        $("#dos_msg").show();
      } else {
        $('#dos_msg').hide();
      }
    };
    getSubCategories();
    getHoas();
    getNRHoas();
    $(document).on("change", ".req_type", function () {
      toggleStickerNo($(this));
    });
    $("#clearSig").on("click", function () {
      _0x2d4d07.clear();
    });
    $('#sticker_request_form').on('submit', function () {
      if (!_0x2d4d07.isEmpty()) {
        $("#sig-input").val(_0x2d4d07.toDataURL());
      }
      return true;
    });
    changeSubCategories = () => {
      var _0x5dd5c5 = $("#category").val();
      var _0x5b45d5 = '';
      switch (_0x5dd5c5) {
        case '1':
          for (let _0x2d51b5 = 0x0; _0x2d51b5 < _0x20d540.length; _0x2d51b5++) {
            _0x5b45d5 += "<option value=\"" + _0x20d540[_0x2d51b5].value + "\">" + _0x20d540[_0x2d51b5].name + "</option>";
          }
          $("#hoa").html(_0x35b3fd);
          $('#hoa').prop("disabled", false);
          $('#hoa_tab').show();
          break;
        case '2':
          for (let _0x3cd6c8 = 0x0; _0x3cd6c8 < _0x49e45d.length; _0x3cd6c8++) {
            _0x5b45d5 += "<option value=\"" + _0x49e45d[_0x3cd6c8].value + "\">" + _0x49e45d[_0x3cd6c8].name + '</option>';
          }
          $("#hoa").html(_0x3c5ccc);
          $("#hoa").prop('disabled', false);
          $('#hoa_tab').show();
          break;
        case '3':
          for (let _0x4b1377 = 0x0; _0x4b1377 < _0x3e1bf5.length; _0x4b1377++) {
            _0x5b45d5 += "<option value=\"" + _0x3e1bf5[_0x4b1377].value + "\">" + _0x3e1bf5[_0x4b1377].name + "</option>";
          }
          $("#hoa").prop('disabled', true);
          $('#hoa_tab').hide();
          break;
      }
      $('#sub_category').html(_0x5b45d5);
      toggleDOS();
      getRequirements();
    };
    $('#sub_category').on("change", function () {
      if ($('#category').val() == 0x1) {
        if ($("#sub_category option:selected").text() == "No HOA") {
          $("#hoa").prop("disabled", true);
          $('#hoa_tab').hide();
        } else {
          if ($("#sub_category option:selected").text() == "HOA Non-Member") {
            $("#hoa").html(_0x4ab78b);
          } else if ($("#sub_category option:selected").text() == "Business Owner") {
            $("#hoa").html(_0x123c57);
          } else {
            $('#hoa').html(_0x35b3fd);
          }
          $("#hoa").prop("disabled", false);
          $("#hoa_tab").show();
        }
      } else if ($("#category").val() == 0x2) {
        if ($("#sub_category option:selected").text() == "Regular Applicant" || $("#sub_category option:selected").text() == "School Teachers & Employee" || $("#sub_category option:selected").text() == "Commercial (Regular)") {
          $("#hoa").prop("disabled", true);
          $('#hoa_tab').hide();
        } else {
          $('#hoa').prop('disabled', false);
          $('#hoa_tab').show();
        }
      }
      toggleDOS();
    });
    $("#rules_regulation_tab").on("scroll", function (_0x48df3b) {
      var _0x4c53bd = $(this);
      if (_0x4c53bd.scrollTop() > 0x0 && _0x4c53bd[0x0].scrollHeight - _0x4c53bd.scrollTop() + 0x3 <= _0x4c53bd.outerHeight() + 0x1) {
        if ($("#checkboxCertify").is(":disabled")) {
          $("#checkboxCertify").next('label').removeClass("text-muted");
          $('#checkboxCertify').prop("disabled", false);
          $("#rules_note").css("visibility", "hidden");
        }
      }
    });
    $(document).on("click", ".v_remove_btn", function (_0xdfe17b) {
      _0xdfe17b.preventDefault();
      $(this).parent().parent().remove();
    });
    $("#sticker_request_form").on('submit', function () {
      $("#request_submit_btn").prop('disabled', true);
      $("#request_submit_btn").html("<div class=\"spinner-border spinner-border-sm\" role=\"status\">\n                   </div>\n                   <br>\n                   Submitting Application");
    });
  });