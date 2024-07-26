let docYV1SubDistrictCodeList = [];
let wastewaterTreatmentNameList = [];

$(document).ready(function() {
  renderLovProvince($("#fm_document_yv1").find("[name=province_code]"), [{"province_code":10,"province_name_th":"กรุงเทพมหานคร","province_name_en":"Bangkok"}])

  getLovBuildingType();
  getWastewaterTreatmentName();
});

$(document).on("change", "#cb_doc_attach_1, #cb_doc_attach_2, #cb_doc_attach_3, #cb_doc_attach_4, #cb_doc_attach_5, #cb_doc_attach_6, #cb_doc_attach_7, #cb_doc_attach_8, #cb_doc_attach_9, #cb_doc_attach_10, #cb_doc_attach_11", function(event, data) {
  let layout = $(this).attr("attr-layout-target");
  if(this.checked) {
    $("#" + layout).removeClass("d-none");
  } else {
    $("#" + layout).addClass("d-none");
  }
});

$(document).on("change", "#fm_document_yv1 [name=province_code]", function(event, data) {
  $("#fm_document_yv1").find("[name=district_code]").empty();
  $("#fm_document_yv1").find("[name=sub_district_code]").empty();
  $("#fm_document_yv1").find("[name=zip_code]").val("");

  if (this.value != -1)
    getLovDistrictByProvinceCode($("#fm_document_yv1").find("[name=district_code]"), this.value);
});
$(document).on("change", "#fm_document_yv1 [name=district_code]", function(event, data) {
    $("#fm_document_yv1").find("[name=sub_district_code]").empty();
    $("#fm_document_yv1").find("[name=zip_code]").val("");

    if (this.value != -1)
      getLovSubDistrictByDistrictCode($("#fm_document_yv1").find("[name=sub_district_code]"), this.value);
});
$(document).on("change", "#fm_document_yv1 [name=sub_district_code]", function(event, data) {
    $("#fm_document_yv1").find("[name=zip_code]").val("");

    if (this.value != -1) {
      let indexDropdownSubDistrict = $("#fm_document_yv1").find("[name=sub_district_code] option:selected").attr("attr-index");
      $("#fm_document_yv1").find("[name=zip_code]").val(docYV1SubDistrictCodeList[indexDropdownSubDistrict].zip_code);
    }
});

$(document).on("change", "#fm_document_yv1 [name=wastewater_source_building_type]", function(event, data) {
    $("#fm_document_yv1").find("[name=wastewater_source_building_size]").empty();

    if(this.value != -1)
        getLovBuildingSize(this.value);
});

$(document).on("change", "#fm_document_yv1 [name=wastewater_treatment_type]", function(event, data) {
    renderWastewaterTreatmentName($("#fm_document_yv1").find("[name=wastewater_treatment_name_id]"));
});

function renderLovBuildingType(element, datas) {
  let option = "<option value='-1' disabled selected>เลือกประเภทอาคาร</option>";
  for (let i = 0; i < datas.length; i++) {
      option += "<option value=" + datas[i].building_type_id + ">" + datas[i].building_name + "</option>";
  }
  element.empty().append(option);
}

function renderLovBuildingSize(element, datas) {
  let option = "<option value='-1' disabled selected>เลือกขนาดของอาคาร</option>";
  for (let i = 0; i < datas.length; i++) {
      option += "<option value=" + datas[i].building_size_id + ">" + datas[i].building_size_desc + "</option>";
  }
  element.empty().append(option);
}

function getLovBuildingType() {
    let urlService =  "/service/lov/getLovBuildingType";

    let dataService = {};

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            renderLovBuildingType($("#fm_document_yv1").find("[name=wastewater_source_building_type]"), datas);
        }

    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status != 404) {
            showToast(msg, 'danger', 5000);
        }
    });
}

function getLovBuildingSize(buildingTypeId) {
    let urlService =  "/service/lov/getLovBuildingSize";

    let dataService = {
      "building_type_id": buildingTypeId
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            renderLovBuildingSize($("#fm_document_yv1").find("[name=wastewater_source_building_size]"), datas);
        }

    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status != 404) {
            showToast(msg, 'danger', 5000);
        }
    });
}

function renderWastewaterTreatmentName(element) {
  let wastewaterTreatmentTypeValue = $("#fm_document_yv1").find("[name=wastewater_treatment_type]:checked").val();

  let option = "<option value='-1' disabled selected>เลือกชนิดของระบบบำบัดน้ำเสีย</option>";
  for (let i = 0; i < wastewaterTreatmentNameList.length; i++) {
    if (wastewaterTreatmentTypeValue == wastewaterTreatmentNameList[i].treatment_type) {
      option += "<option value=" + wastewaterTreatmentNameList[i].treatment_id + ">" + wastewaterTreatmentNameList[i].treatment_name + "</option>";
    }
  }
  element.empty().append(option);
}

function getWastewaterTreatmentName() {
    let urlService =  "/service/lov/getWastewaterTreatmentName";

    let dataService = {};

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            wastewaterTreatmentNameList = datas;
            renderWastewaterTreatmentName($("#fm_document_yv1").find("[name=wastewater_treatment_name_id]"));
        }

    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status != 404) {
            showToast(msg, 'danger', 5000);
        }
    });
}
