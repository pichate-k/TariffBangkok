let queryStringDocNo = new URLSearchParams(window.location.search).get('doc_no');

$(document).ready(function() {
  getDocumentDetailByDocNo();
});

$(document).on("click", "#btn_update_document_pg1", function(event, data) {
    let formDocumentPG1 = new FormData($("#fm_document_pg1")[0]);
    updateDocumentPG1(formDocumentPG1);
});

function getDocumentDetailByDocNo() {
    let urlService = "/service/doc/getDocumentDetailByDocNo";

    let dataService = {
      "doc_type": "PG1",
      "doc_no": queryStringDocNo
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let data = success.responseJSON.data;

            renderLovDistrict($("#fm_document_pg1").find("[name=district_code]"), data.lov_district);
            renderLovSubDistrict($("#fm_document_pg1").find("[name=sub_district_code]"), data.lov_sub_district);

            $("#fm_document_view").find("[name=document_log_id]").val(data.log_id);
            $("#fm_document_view").find("[name=doc_status]").val(data.doc_status);
            $("#fm_document_view").find("[name=doc_expiry_date]").val(data.doc_expiry_date);

            $("#fm_document_pg1").find("[name=doc_pg1_id]").val(data.doc_pg1_id);
            $("#fm_document_pg1").find("[name=doc_no]").val(data.doc_no);
            $("#fm_document_pg1").find("[name=address_owner]").val(data.address_owner);
            $("#fm_document_pg1").find("[name=address_name]").val(data.address_name);
            $("#fm_document_pg1").find("[name=address_code]").val(data.address_code);
            $("#fm_document_pg1").find("[name=address]").val(data.address);
            $("#fm_document_pg1").find("[name=moo]").val(data.moo);
            $("#fm_document_pg1").find("[name=soi]").val(data.soi);
            $("#fm_document_pg1").find("[name=road]").val(data.road);
            $("#fm_document_pg1").find("[name=province_code]").val(data.province_code);
            $("#fm_document_pg1").find("[name=district_code]").val(data.district_code);
            $("#fm_document_pg1").find("[name=sub_district_code]").val(data.sub_district_code);
            $("#fm_document_pg1").find("[name=zip_code]").val(data.zip_code);
            $("#fm_document_pg1").find("[name=telephone]").val(data.telephone);
            $("#fm_document_pg1").find("[name=mobile_phone]").val(data.mobile_phone);
            $("#fm_document_pg1").find("[name=fax]").val(data.fax);
            $("#fm_document_pg1").find("[name=email]").val(data.email);
            $("#fm_document_pg1").find("[name=latitude]").val(data.latitude);
            $("#fm_document_pg1").find("[name=longitude]").val(data.longitude);
            $("#fm_document_pg1").find("[name=business_type]").val(data.business_type);

            $("#fm_document_pg1").find("[name=badan_install_sensor][value='" + data.badan_install_sensor + "']").prop("checked", true);
            $("#fm_document_pg1").find("[name=badan_max_capacity_per_month]").val(data.badan_max_capacity_per_month);
            $("#fm_document_pg1").find("[name=non_badan_building_type]").val(data.non_badan_building_type);
            $("#fm_document_pg1").find("[name=non_badan_calculate_people]").val(data.non_badan_calculate_people);
            $("#fm_document_pg1").find("[name=non_badan_calculate_panel]").val(data.non_badan_calculate_panel);
            $("#fm_document_pg1").find("[name=non_badan_calculate_room]").val(data.non_badan_calculate_room);
            $("#fm_document_pg1").find("[name=non_badan_calculate_m2]").val(data.non_badan_calculate_m2);
            $("#fm_document_pg1").find("[name=non_badan_calculate_bed]").val(data.non_badan_calculate_bed);
            $("#fm_document_pg1").find("[name=non_badan_formular]").val(data.non_badan_formular);
            $("#fm_document_pg1").find("[name=non_badan_water_avg_per_month]").val(data.non_badan_water_avg_per_month);

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
        }
    }, function(fail) {
      console.log(fail.responseJSON);

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'danger', 5000);
    });
}

function updateDocumentPG1(input) {
    let urlService = "/service/doc/updateDocumentPG1";

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
              addValidatetionUI($("#fm_document_pg1").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
