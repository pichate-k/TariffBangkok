let queryStringDocNo = new URLSearchParams(window.location.search).get('doc_no');

$(document).ready(function() {
  getDocumentDetailByDocNo();
});

$(document).on("click", "#btn_update_document_pg2", function(event, data) {
    let formDocumentPG2 = new FormData($("#fm_document_pg2")[0]);
    updateDocumentPG2(formDocumentPG2);
});

function getDocumentDetailByDocNo() {
    let urlService = "/service/doc/getDocumentDetailByDocNo";

    let dataService = {
      "doc_type": "PG2",
      "doc_no": queryStringDocNo
    };

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let data = success.responseJSON.data;

            renderLovDistrict($("#fm_document_pg2").find("[name=district_code]"), data.lov_district);
            renderLovSubDistrict($("#fm_document_pg2").find("[name=sub_district_code]"), data.lov_sub_district);

            $("#fm_document_view").find("[name=document_log_id]").val(data.log_id);
            $("#fm_document_view").find("[name=doc_status]").val(data.doc_status);
            $("#fm_document_view").find("[name=doc_expiry_date]").val(data.doc_expiry_date);

            $("#fm_document_pg2").find("[name=doc_pg2_id]").val(data.doc_pg2_id);
            $("#fm_document_pg2").find("[name=doc_no]").val(data.doc_no);
            $("#fm_document_pg2").find("[name=address_owner]").val(data.address_owner);
            $("#fm_document_pg2").find("[name=address_name]").val(data.address_name);
            $("#fm_document_pg2").find("[name=address_code]").val(data.address_code);
            $("#fm_document_pg2").find("[name=address]").val(data.address);
            $("#fm_document_pg2").find("[name=moo]").val(data.moo);
            $("#fm_document_pg2").find("[name=soi]").val(data.soi);
            $("#fm_document_pg2").find("[name=road]").val(data.road);
            $("#fm_document_pg2").find("[name=province_code]").val(data.province_code);
            $("#fm_document_pg2").find("[name=district_code]").val(data.district_code);
            $("#fm_document_pg2").find("[name=sub_district_code]").val(data.sub_district_code);
            $("#fm_document_pg2").find("[name=zip_code]").val(data.zip_code);
            $("#fm_document_pg2").find("[name=telephone]").val(data.telephone);
            $("#fm_document_pg2").find("[name=mobile_phone]").val(data.mobile_phone);
            $("#fm_document_pg2").find("[name=fax]").val(data.fax);
            $("#fm_document_pg2").find("[name=email]").val(data.email);
            $("#fm_document_pg2").find("[name=latitude]").val(data.latitude);
            $("#fm_document_pg2").find("[name=longitude]").val(data.longitude);
            $("#fm_document_pg2").find("[name=business_type]").val(data.business_type);

            $("#fm_document_pg2").find("[name=badan_test_date]").val(data.badan_test_date);
            $("#fm_document_pg2").find("[name=badan_total_pool]").val(data.badan_total_pool);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_1_before]").val(data.badan_test_checkpoint_1_before);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_1_after]").val(data.badan_test_checkpoint_1_after);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_2_before]").val(data.badan_test_checkpoint_2_before);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_2_after]").val(data.badan_test_checkpoint_2_after);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_3_before]").val(data.badan_test_checkpoint_3_before);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_3_after]").val(data.badan_test_checkpoint_3_after);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_4_before]").val(data.badan_test_checkpoint_4_before);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_4_after]").val(data.badan_test_checkpoint_4_after);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_5_before]").val(data.badan_test_checkpoint_5_before);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_5_after]").val(data.badan_test_checkpoint_5_after);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_6_before]").val(data.badan_test_checkpoint_6_before);
            $("#fm_document_pg2").find("[name=badan_test_checkpoint_6_after]").val(data.badan_test_checkpoint_6_after);
            $("#fm_document_pg2").find("[name=badan_water_capacity_per_month]").val(data.badan_water_capacity_per_month);
            $("#fm_document_pg2").find("[name=non_badan_source]").val(data.non_badan_source);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_1_before]").val(data.non_badan_test_checkpoint_1_before);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_1_after]").val(data.non_badan_test_checkpoint_1_after);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_2_before]").val(data.non_badan_test_checkpoint_2_before);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_2_after]").val(data.non_badan_test_checkpoint_2_after);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_3_before]").val(data.non_badan_test_checkpoint_3_before);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_3_after]").val(data.non_badan_test_checkpoint_3_after);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_4_before]").val(data.non_badan_test_checkpoint_4_before);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_4_after]").val(data.non_badan_test_checkpoint_4_after);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_5_before]").val(data.non_badan_test_checkpoint_5_before);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_5_after]").val(data.non_badan_test_checkpoint_5_after);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_6_before]").val(data.non_badan_test_checkpoint_6_before);
            $("#fm_document_pg2").find("[name=non_badan_test_checkpoint_6_after]").val(data.non_badan_test_checkpoint_6_after);
            $("#fm_document_pg2").find("[name=non_badan_water_capacity_per_month]").val(data.non_badan_water_capacity_per_month);
            $("#fm_document_pg2").find("[name=wastewater_test_checkpoint_1_per_month]").val(data.wastewater_test_checkpoint_1_per_month);
            $("#fm_document_pg2").find("[name=wastewater_test_checkpoint_2_per_month]").val(data.wastewater_test_checkpoint_2_per_month);
            $("#fm_document_pg2").find("[name=wastewater_test_checkpoint_3_per_month]").val(data.wastewater_test_checkpoint_3_per_month);
            $("#fm_document_pg2").find("[name=wastewater_test_checkpoint_4_per_month]").val(data.wastewater_test_checkpoint_4_per_month);
            $("#fm_document_pg2").find("[name=wastewater_test_checkpoint_5_per_month]").val(data.wastewater_test_checkpoint_5_per_month);

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
        }
    }, function(fail) {
      console.log(fail.responseJSON);

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'danger', 5000);
    });
}

function updateDocumentPG2(input) {
    let urlService = "/service/doc/updateDocumentPG2";

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
              addValidatetionUI($("#fm_document_pg2").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
