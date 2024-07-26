let userProfileSubDistrictCodeList = [];

$(document).ready(function() {
  getLovProvince($("#fm_user_profile").find("[name=province_code]"));
});

$(document).on("change", "#fm_user_profile [name=user_type]", function(event, data) {
  changeLayoutUserType();
});

$(document).on("change", "#fm_user_profile [name=province_code]", function(event, data) {
  $("#fm_user_profile").find("[name=district_code]").empty();
  $("#fm_user_profile").find("[name=sub_district_code]").empty();
  $("#fm_user_profile").find("[name=zip_code]").val("");

  if (this.value != -1)
    getLovDistrictByProvinceCode($("#fm_user_profile").find("[name=district_code]"), this.value);
});

$(document).on("change", "#fm_user_profile [name=district_code]", function(event, data) {
    $("#fm_user_profile").find("[name=sub_district_code]").empty();
    $("#fm_user_profile").find("[name=zip_code]").val("");

    if (this.value != -1)
      getLovSubDistrictByDistrictCode($("#fm_user_profile").find("[name=sub_district_code]"), this.value);
});

$(document).on("change", "#fm_user_profile [name=sub_district_code]", function(event, data) {
    $("#fm_user_profile").find("[name=zip_code]").val("");

    if (this.value != -1) {
      let indexDropdownSubDistrict = $("#fm_user_profile").find("[name=sub_district_code] option:selected").attr("attr-index");
      $("#fm_user_profile").find("[name=zip_code]").val(userProfileSubDistrictCodeList[indexDropdownSubDistrict].zip_code);
    }
});

function renderLovProvince(element, datas) {
    let option = "<option value='-1'>เลือกจังหวัด</option>";
    for (let i = 0; i < datas.length; i++) {
        option += "<option value=" + datas[i].province_code + ">" + datas[i].province_name_th + "</option>";
    }
    element.empty().append(option);
}

function renderLovDistrict(element, datas) {
    let option = "<option value='-1'>เลือกอำเภอ</option>";
    for (let i = 0; i < datas.length; i++) {
        option += "<option value=" + datas[i].district_code + ">" + datas[i].district_name_th + "</option>";
    }
    element.empty().append(option);
}

function renderLovSubDistrict(element, datas) {
    let option = "<option value='-1'>เลือกตำบล</option>";
    for (let i = 0; i < datas.length; i++) {
        option += "<option value=" + datas[i].sub_district_code + " attr-index='" + i + "'>" + datas[i].sub_district_name_th + "</option>";
    }
    element.empty().append(option);
}

function getLovProvince(elementLovProvince) {
    let urlService = "/service/lov/getLovProvince";

    let dataService = {};

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        // console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            renderLovProvince(elementLovProvince, datas);
        }

    }, function(fail) {
        // console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
    });
}

function getLovDistrictByProvinceCode(elementLovDistrict, provinceCode) {
    let urlService = "/service/lov/getLovDistrictByProvinceCode";

    let dataService = {
        "province_code": provinceCode
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        // console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            renderLovDistrict(elementLovDistrict, datas);
        }

    }, function(fail) {
        // console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
    });
}

function getLovSubDistrictByDistrictCode(elementLovSubDistrict, districtCode) {
    let urlService = "/service/lov/getLovSubDistrictByDistrictCode";

    let dataService = {
        "district_code": districtCode
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        // console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            userProfileSubDistrictCodeList = datas;
            docYV1SubDistrictCodeList = datas;
            renderLovSubDistrict(elementLovSubDistrict, datas);
        }

    }, function(fail) {
        // console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
    });
}

function changeLayoutUserType (){
  let userType = $("#fm_user_profile").find("[name=user_type]:checked").val();

  switch (userType) {
    case "1":
        $("#layout_personal").removeClass("d-none");
        $("#layout_company").addClass("d-none");
        $("#label_address_personal").removeClass("d-none");
        $("#label_address_company").addClass("d-none");

        $("#fm_user_profile").find("[name=company_type_id]").val("");
        $("#fm_user_profile").find("[name=company_register_date]").val("");
        $("#fm_user_profile").find("[name=company_tax_id]").val("");
      break;
    case "2":
        $("#layout_personal").addClass("d-none");
        $("#layout_company").removeClass("d-none");
        $("#label_address_personal").addClass("d-none");
        $("#label_address_company").removeClass("d-none");

        $("#fm_user_profile").find("[name=age]").val("");
        $("#fm_user_profile").find("[name=nationality]").val("");
        $("#fm_user_profile").find("[name=tax_id]").val("");
      break;
  }
}
