$(document).ready(function() {
  $(".datetimepicker").datetimepicker({
        icons: {
            next: 'fa-solid fa-right-long',
            previous: 'fa-solid fa-left-long'
        },
        sideBySide: true,
        locale: 'th',
        format: 'YYYY-MM-DD',
        defaultDate: moment().format('YYYY-MM-DD'),
    });

    getUserProfile($("#fm_user_profile").find("[name=district_code]"), $("#fm_user_profile").find("[name=sub_district_code]"));
});

$(document).on("click", "#btn_update_user_profile", function(event, data) {
    let formUserProfile = new FormData($("#fm_user_profile")[0]);
    updateUserProfile(formUserProfile);
});

function getUserProfile(elementLovDistrict, elementLovSubDistrict) {
    let urlService = "/service/user/getUserProfile";

    let dataService = {};

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let data = success.responseJSON.data;

            renderLovDistrict(elementLovDistrict, data.lov_district);
            renderLovSubDistrict(elementLovSubDistrict, data.lov_sub_district);

            $("#fm_user_profile").find("[name=name_title][value='" + data.name_title + "']").prop("checked", true);
            $("#fm_user_profile").find("[name=name]").val(data.name);
            $("#fm_user_profile").find("[name=user_type][value='" + data.user_type + "']").prop("checked", true);
            $("#fm_user_profile").find("[name=age]").val(data.age);
            $("#fm_user_profile").find("[name=nationality]").val(data.nationality);
            $("#fm_user_profile").find("[name=tax_id]").val(data.tax_id);
            $("#fm_user_profile").find("[name=company_type_id]").val(data.company_type_id);
            $("#fm_user_profile").find("[name=company_register_date]").val(data.company_register_date);
            $("#fm_user_profile").find("[name=company_tax_id]").val(data.company_tax_id);
            $("#fm_user_profile").find("[name=address]").val(data.address);
            $("#fm_user_profile").find("[name=moo]").val(data.moo);
            $("#fm_user_profile").find("[name=soi]").val(data.soi);
            $("#fm_user_profile").find("[name=road]").val(data.road);
            $("#fm_user_profile").find("[name=province_code]").val(data.province_code);
            $("#fm_user_profile").find("[name=district_code]").val(data.district_code);
            $("#fm_user_profile").find("[name=sub_district_code]").val(data.sub_district_code);
            $("#fm_user_profile").find("[name=zip_code]").val(data.zip_code);
            $("#fm_user_profile").find("[name=email]").val(data.email);
            $("#fm_user_profile").find("[name=telephone]").val(data.telephone);
            $("#fm_user_profile").find("[name=mobile_phone]").val(data.mobile_phone);
            $("#fm_user_profile").find("[name=fax]").val(data.fax);

            changeLayoutUserType();

            $("#layout_nouserprofile").addClass("d-none");
            $("#layout_document").removeClass("d-none");
        }
    }, function(fail) {
      console.log(fail.responseJSON);

      if(fail.status == 404){
        $("#layout_nouserprofile").removeClass("d-none");
        $("#layout_document").addClass("d-none");
      } else {
        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
      }
    });
}

function updateUserProfile(input) {
    let urlService = "/service/user/updateUserProfile";

    let dataService = input;

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataFalse, false, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;
            showToast(msg, 'success', 3000);
            clearValidationUI();
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status == 400) {
            for (var text in msg) {
              addValidatetionUI($("#fm_user_profile").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
