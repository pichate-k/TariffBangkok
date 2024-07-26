let queryStringDocNo = new URLSearchParams(window.location.search).get('doc_no');

$(document).ready(function() {
  getDocumentDetailByDocNo();
});

$(document).on("click", "#btn_update_document_yv1", function(event, data) {
    let formDocumentYV1 = new FormData($("#fm_document_yv1")[0]);
    updateDocumentYV1(formDocumentYV1);
});

function getDocumentDetailByDocNo() {
    let urlService = "/service/doc/getDocumentDetailByDocNo";

    let dataService = {
      "doc_type": "YV1",
      "doc_no": queryStringDocNo
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let data = success.responseJSON.data;

            renderLovDistrict($("#fm_document_yv1").find("[name=district_code]"), data.lov_district);
            renderLovSubDistrict($("#fm_document_yv1").find("[name=sub_district_code]"), data.lov_sub_district);
            renderLovBuildingSize($("#fm_document_yv1").find("[name=wastewater_source_building_size]"), data.lov_building_size);

            $("#fm_document_yv1").find("[name=wastewater_treatment_type][value='" + data.wastewater_treatment_type + "']").prop("checked", true);

            $("#fm_document_view").find("[name=document_log_id]").val(data.log_id);
            $("#fm_document_view").find("[name=doc_status]").val(data.doc_status);
            $("#fm_document_view").find("[name=doc_expiry_date]").val(data.doc_expiry_date);

            $("#fm_document_yv1").find("[name=doc_yv1_id]").val(data.doc_yv1_id);
            $("#fm_document_yv1").find("[name=doc_no]").val(data.doc_no);
            $("#fm_document_yv1").find("[name=address_owner]").val(data.address_owner);
            $("#fm_document_yv1").find("[name=address_name]").val(data.address_name);
            $("#fm_document_yv1").find("[name=address_code]").val(data.address_code);
            $("#fm_document_yv1").find("[name=address]").val(data.address);
            $("#fm_document_yv1").find("[name=moo]").val(data.moo);
            $("#fm_document_yv1").find("[name=soi]").val(data.soi);
            $("#fm_document_yv1").find("[name=road]").val(data.road);
            $("#fm_document_yv1").find("[name=province_code]").val(data.province_code);
            $("#fm_document_yv1").find("[name=district_code]").val(data.district_code);
            $("#fm_document_yv1").find("[name=sub_district_code]").val(data.sub_district_code);
            $("#fm_document_yv1").find("[name=zip_code]").val(data.zip_code);
            $("#fm_document_yv1").find("[name=telephone]").val(data.telephone);
            $("#fm_document_yv1").find("[name=mobile_phone]").val(data.mobile_phone);
            $("#fm_document_yv1").find("[name=fax]").val(data.fax);
            $("#fm_document_yv1").find("[name=email]").val(data.email);
            $("#fm_document_yv1").find("[name=latitude]").val(data.latitude);
            $("#fm_document_yv1").find("[name=longitude]").val(data.longitude);
            $("#fm_document_yv1").find("[name=business_type]").val(data.business_type);
            $("#fm_document_yv1").find("[name=treatment_process_name]").val(data.treatment_process_name);
            $("#fm_document_yv1").find("[name=treatment_capacity_per_day]").val(data.treatment_capacity_per_day);
            $("#fm_document_yv1").find("[name=treatment_control_remark]").val(data.treatment_control_remark);
            $("#fm_document_yv1").find("[name=treatment_work_remark]").val(data.treatment_work_remark);
            $("#fm_document_yv1").find("[name=water_use_per_month]").val(data.water_use_per_month);
            $("#fm_document_yv1").find("[name=water_treatment_per_month]").val(data.water_treatment_per_month);
            $("#fm_document_yv1").find("[name=bod_treatment_per_month]").val(data.bod_treatment_per_month);
            $("#fm_document_yv1").find("[name=water_treatment_to_remark]").val(data.water_treatment_to_remark);
            $("#fm_document_yv1").find("[name=wastewater_source_building_type]").val(data.wastewater_source_building_type);
            $("#fm_document_yv1").find("[name=wastewater_source_building_size]").val(data.wastewater_source_building_size);
            $("#fm_document_yv1").find("[name=wastewater_source_remark]").val(data.wastewater_source_remark);

            renderWastewaterTreatmentName($("#fm_document_yv1").find("[name=wastewater_treatment_name_id]"));
            $("#fm_document_yv1").find("[name=wastewater_treatment_name_id]").val(data.wastewater_treatment_name_id);
            $("#fm_document_yv1").find("[name=treatment_work_per_day][value='" + data.treatment_work_per_day + "']").prop("checked", true);
            $("#fm_document_yv1").find("[name=water_treatment_to][value='" + data.water_treatment_to + "']").prop("checked", true);

            if(data.doc_attach_1 !== null){
              // $("#cb_doc_attach_1").prop("checked", true);
              $("#txt_doc_attach_1").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_1) + "','filewindow1','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_2 !== null){
              // $("#cb_doc_attach_2").prop("checked", true);
              $("#txt_doc_attach_2").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_2) + "','filewindow2','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_3 !== null){
              // $("#cb_doc_attach_3").prop("checked", true);
              $("#txt_doc_attach_3").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_3) + "','filewindow3','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_4 !== null){
              // $("#cb_doc_attach_4").prop("checked", true);
              $("#txt_doc_attach_4").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_4) + "','filewindow4','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_5 !== null){
              // $("#cb_doc_attach_5").prop("checked", true);
              $("#txt_doc_attach_5").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_5) + "','filewindow5','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_6 !== null){
              // $("#cb_doc_attach_6").prop("checked", true);
              $("#txt_doc_attach_6").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_6) + "','filewindow6','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_7 !== null){
              // $("#cb_doc_attach_7").prop("checked", true);
              $("#txt_doc_attach_7").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_7) + "','filewindow7','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_8 !== null){
              // $("#cb_doc_attach_8").prop("checked", true);
              $("#txt_doc_attach_8").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_8) + "','filewindow8','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_9 !== null){
              // $("#cb_doc_attach_9").prop("checked", true);
              $("#txt_doc_attach_9").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_9) + "','filewindow9','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_10 !== null){
              // $("#cb_doc_attach_10").prop("checked", true);
              $("#txt_doc_attach_10").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_10) + "','filewindow10','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
            if(data.doc_attach_11 !== null){
              // $("#cb_doc_attach_11").prop("checked", true);
              $("#txt_doc_attach_11").removeClass("d-none").attr("onclick", "var openpopup = window.open('/service/doc/file/" + btoa(data.doc_attach_11) + "','filewindow11','width=1100,height=700,scrollbars=yes'); openpopup.oncontextmenu = function() { return false; } ");
            }
        }
    }, function(fail) {
      console.log(fail.responseJSON);

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'danger', 5000);
    });
}

function updateDocumentYV1(input) {
    let urlService = "/service/doc/updateDocumentYV1";

    let dataService = input;

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataFalse, false, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;
            showToast(msg, 'success', 3000);
            clearValidationUI();
            window.location.href = "/u/document/status.htm";
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status == 400) {
            clearValidationUI();
            for (var text in msg) {
              addValidatetionUI($("#fm_document_yv1").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
