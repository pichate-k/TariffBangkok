$(document).on("click", "#btn_change_password", function(event, data) {
  let formUserChangePassword = new FormData($("#fm_user_change_password")[0]);
  changePassword(formUserChangePassword);
});

function changePassword(input) {
    let urlService = "/service/user/changePassword";

    let dataService = input;

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataFalse, false, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;

            clearValidationUI();
            
            showToast(msg, 'success', 3000);

            $("#fm_user_change_password")[0].reset();
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status == 400) {
            clearValidationUI();
            for (var text in msg) {
              addValidatetionUI($("#fm_user_change_password").find("[name=" + text + "]"), msg[text]);
            }
        } else {
            showToast(msg, 'danger', 5000);
        }
    });
}
