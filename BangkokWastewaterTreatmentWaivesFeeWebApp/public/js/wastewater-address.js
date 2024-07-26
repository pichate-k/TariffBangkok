let subDistrictCodeList = [];

$(document).ready(function() {
  $('#tb_user_wastewater_address').DataTable({
    paging: false,
    ordering: false,
    searching: false,
    info: false,
    "data": [],
    "columns": [{
        "data": "inout_start_date",
        "width": "5%",
        "className": "text-center text-nowrap",
    },{
        "data": "inout_start_date",
        "width": "50%",
        "className": "text-center text-nowrap",
    },{
        "data": "inout_start_date",
        "width": "15%",
        "className": "text-center text-nowrap",
    },{
        "data": "inout_start_date",
        "width": "15%",
        "className": "text-center text-nowrap",
    },{
        "data": "inout_start_date",
        "width": "10%",
        "className": "text-center text-nowrap",
    },{
        "data": "inout_start_date",
        "width": "15%",
        "className": "text-center text-nowrap",
    }]
  });

  getLovProvince();
});

$(document).on("change", "#fm_user_profile [name=province_code]", function(event, data) {
  $("#fm_user_profile").find("[name=district_code]").empty();
  $("#fm_user_profile").find("[name=sub_district_code]").empty();
  $("#fm_user_profile").find("[name=zip_code]").val("");

  if (this.value != -1)
    getLovDistrictByProvinceCode(this.value);
});
$(document).on("change", "#fm_user_profile [name=district_code]", function(event, data) {
    $("#fm_user_profile").find("[name=sub_district_code]").empty();
    $("#fm_user_profile").find("[name=zip_code]").val("");

    if (this.value != -1)
      getLovSubDistrictByDistrictCode(this.value);
});
$(document).on("change", "#fm_user_profile [name=sub_district_code]", function(event, data) {
    $("#fm_user_profile").find("[name=zip_code]").val("");

    if (this.value != -1) {
      let indexDropdownSubDistrict = $("#fm_user_profile").find("[name=sub_district_code] option:selected").attr("attr-index");
      $("#fm_user_profile").find("[name=zip_code]").val(subDistrictCodeList[indexDropdownSubDistrict].zip_code);
    }
});

function renderLovProvince(datas) {
    let option = "<option value='-1'>เลือกจังหวัด</option>";
    for (let i = 0; i < datas.length; i++) {
        option += "<option value=" + datas[i].province_code + ">" + datas[i].province_name_th + "</option>";
    }
    $("#fm_user_profile").find("[name=province_code]").empty().append(option);
}

function renderLovDistrict(datas) {
    let option = "<option value='-1'>เลือกอำเภอ</option>";
    for (let i = 0; i < datas.length; i++) {
        option += "<option value=" + datas[i].district_code + ">" + datas[i].district_name_th + "</option>";
    }
    $("#fm_user_profile").find("[name=district_code]").empty().append(option);
}

function renderLovSubDistrict(datas) {
    let option = "<option value='-1'>เลือกตำบล</option>";
    for (let i = 0; i < datas.length; i++) {
        option += "<option value=" + datas[i].sub_district_code + " attr-index='" + i + "'>" + datas[i].sub_district_name_th + "</option>";
    }
    $("#fm_user_profile").find("[name=sub_district_code]").empty().append(option);
}

function getLovProvince() {
    let urlService = "/service/lov/getLovProvince";

    let dataService = {};
    showToast("ไม่พบข้อมูล", 'info', 3000);
    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        // console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            renderLovProvince(datas);
        }

    }, function(fail) {
        // console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg);
    });
}

function getLovDistrictByProvinceCode(provinceCode) {
    let urlService = "/service/lov/getLovDistrictByProvinceCode";

    let dataService = {
        "province_code": provinceCode
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        // console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            renderLovDistrict(datas);
        }

    }, function(fail) {
        // console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg);
    });
}

function getLovSubDistrictByDistrictCode(districtCode) {
    let urlService = "/service/lov/getLovSubDistrictByDistrictCode";

    let dataService = {
        "district_code": districtCode
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        // console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            subDistrictCodeList = datas;
            renderLovSubDistrict(datas);
        }

    }, function(fail) {
        // console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg);
    });
}
