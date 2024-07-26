$(document).ready(function() {

  $('#tb_user_list').DataTable({
    oLanguage: {
            sSearch: " ",
            sSearchPlaceholder: "ค้นหาผู้ใช้งาน"
    },
    "data": [],
    "columns": [{
        "className": "text-center text-nowrap",
        "render": function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
    },{
        "data": "username",
        "className": "text-center text-nowrap",
    },{
        "data": "name",
        "className": "text-center text-nowrap",
    },{
        "data": "mobile_phone",
        "className": "text-center text-nowrap",
    },{
        "data": "email",
        "className": "text-center text-nowrap",
    },{
        "data": "last_login_dt",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.last_login_dt);
          return ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543) + " " + date.format('HH:mm:ss')) : "-");
        }
    },{
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          return "<button type='button' class='btn btn-blue-bg btn-sm text-nowrap' id='btn_user_detail'>ดูรายละเอียด</button>";
        }
    }]
  });

  getUserProfileByAdmin();
});

$(document).on("click", "#btn_user_detail", function(event, data) {
  let dataTable = $('#tb_user_list').DataTable().row($(this).parents('tr')).data();

  $("#fm_user_profile").find("[name=name_title][value='" + dataTable.name_title + "']").prop("checked", true);
  $("#fm_user_profile").find("[name=name]").val(dataTable.name);
  $("#fm_user_profile").find("[name=user_type][value='" + dataTable.user_type + "']").prop("checked", true);
  $("#fm_user_profile").find("[name=age]").val(dataTable.age);
  $("#fm_user_profile").find("[name=nationality]").val(dataTable.nationality);
  $("#fm_user_profile").find("[name=tax_id]").val(dataTable.tax_id);
  $("#fm_user_profile").find("[name=company_type_id]").val(dataTable.company_type_id);
  $("#fm_user_profile").find("[name=company_register_date]").val(dataTable.company_register_date);
  $("#fm_user_profile").find("[name=company_tax_id]").val(dataTable.company_tax_id);
  $("#fm_user_profile").find("[name=address]").val(dataTable.address);
  $("#fm_user_profile").find("[name=moo]").val(dataTable.moo);
  $("#fm_user_profile").find("[name=soi]").val(dataTable.soi);
  $("#fm_user_profile").find("[name=road]").val(dataTable.road);
  $("#fm_user_profile").find("[name=province_code]").empty().append("<option selected>" + isNull2Blank(dataTable.province_name_th) + "</option");
  $("#fm_user_profile").find("[name=district_code]").empty().append("<option selected>" + isNull2Blank(dataTable.district_name_th) + "</option");
  $("#fm_user_profile").find("[name=sub_district_code]").empty().append("<option selected>" + isNull2Blank(dataTable.sub_district_name_th) + "</option");
  $("#fm_user_profile").find("[name=zip_code]").val(dataTable.zip_code);
  $("#fm_user_profile").find("[name=email]").val(dataTable.email);
  $("#fm_user_profile").find("[name=telephone]").val(dataTable.telephone);
  $("#fm_user_profile").find("[name=mobile_phone]").val(dataTable.mobile_phone);
  $("#fm_user_profile").find("[name=fax]").val(dataTable.fax);

  $("#fm_user_detail").find("[name=username]").val(dataTable.username);

  let lastUpdateDate = moment(dataTable.last_update_date);
  $("#fm_user_detail").find("[name=last_update_date]").val(((lastUpdateDate.isValid()) ? (lastUpdateDate.format('DD/MM/') + (parseInt(lastUpdateDate.format('YYYY')) + 543) + " " + lastUpdateDate.format('HH:mm:ss')) : "-"));
  let lastLoginDate = moment(dataTable.last_login_dt);
  $("#fm_user_detail").find("[name=last_login_dt]").val(((lastLoginDate.isValid()) ? (lastLoginDate.format('DD/MM/') + (parseInt(lastLoginDate.format('YYYY')) + 543) + " " + lastLoginDate.format('HH:mm:ss')) : "-"));

  changeLayoutUserType();

  $("#modalUserDetail").modal("show");
});

function getUserProfileByAdmin() {
    let urlService = "/service/user/getUserProfileByAdmin";

    let dataService = {};

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_user_list').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_user_list').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      // showToast(msg, 'danger', 5000);
    });
}
