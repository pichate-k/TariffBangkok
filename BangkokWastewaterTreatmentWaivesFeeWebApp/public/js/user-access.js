$(document).ready(function() {

  $('#tb_user_access_list').DataTable({
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
        "className": "text-nowrap",
    },{
        "data": "ip_address",
        "className": "text-center text-nowrap",
    },{
        "data": "user_agent",
        "width": "50%",
    },{
        "data": "timestamp",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.timestamp);
          return ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543) + " " + date.format('HH:mm:ss')) : "-");
        }
    }]
  });

  getAccessLogByAdmin();
});

function getAccessLogByAdmin() {
    let urlService = "/service/user/getAccessLogByAdmin";

    let dataService = {};

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_user_access_list').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_user_access_list').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      // showToast(msg, 'danger', 5000);
    });
}
