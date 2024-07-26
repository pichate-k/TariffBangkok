let docYV1SubDistrictCodeList = [];

$(document).ready(function() {
  renderLovProvince($("#fm_document_pg1").find("[name=province_code]"), [{"province_code":10,"province_name_th":"กรุงเทพมหานคร","province_name_en":"Bangkok"}])
});

$(document).on("change", "#cb_doc_attach_1, #cb_doc_attach_2, #cb_doc_attach_3, #cb_doc_attach_4, #cb_doc_attach_5, #cb_doc_attach_6, #cb_doc_attach_7, #cb_doc_attach_8, #cb_doc_attach_9, #cb_doc_attach_10", function(event, data) {
  let layout = $(this).attr("attr-layout-target");
  if(this.checked) {
    $("#" + layout).removeClass("d-none");
  } else {
    $("#" + layout).addClass("d-none");
  }
});

$(document).on("change", "#fm_document_pg1 [name=province_code]", function(event, data) {
  $("#fm_document_pg1").find("[name=district_code]").empty();
  $("#fm_document_pg1").find("[name=sub_district_code]").empty();
  $("#fm_document_pg1").find("[name=zip_code]").val("");

  if (this.value != -1)
    getLovDistrictByProvinceCode($("#fm_document_pg1").find("[name=district_code]"), this.value);
});
$(document).on("change", "#fm_document_pg1 [name=district_code]", function(event, data) {
    $("#fm_document_pg1").find("[name=sub_district_code]").empty();
    $("#fm_document_pg1").find("[name=zip_code]").val("");

    if (this.value != -1)
      getLovSubDistrictByDistrictCode($("#fm_document_pg1").find("[name=sub_district_code]"), this.value);
});
$(document).on("change", "#fm_document_pg1 [name=sub_district_code]", function(event, data) {
    $("#fm_document_pg1").find("[name=zip_code]").val("");

    if (this.value != -1) {
      let indexDropdownSubDistrict = $("#fm_document_pg1").find("[name=sub_district_code] option:selected").attr("attr-index");
      $("#fm_document_pg1").find("[name=zip_code]").val(docYV1SubDistrictCodeList[indexDropdownSubDistrict].zip_code);
    }
});
