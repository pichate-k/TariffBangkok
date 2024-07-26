$(document).ready(function() {

  $('#tb_document_list').DataTable({
    paging: false,
    ordering: false,
    searching: true,
    info: true,
    oLanguage: {
            sSearch: " ",
            sSearchPlaceholder: "ค้นหาแบบ"
    },
    "data": [],
    "columns": [{
        "className": "text-center text-nowrap",
        "render": function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
    },{
        "data": "doc_no",
        "className": "text-center text-nowrap",
    },{
        "data": "doc_type_name",
        "className": "text-center text-nowrap",
    },{
        "data": "name",
        "className": "text-center text-nowrap",
    },{
        "data": "created_date",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.created_date);
          return ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543) + " " + date.format('HH:mm:ss')) : "-");
        }
    },{
        "data": "doc_status_name",
        "className": "text-center text-nowrap",
        "render": function(data, type, row, meta) {
          return "<b class='text-success'>" + row.doc_status_name + "</b>";
        }
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          return "<button type='button' class='btn btn-blue-bg btn-sm text-nowrap' id='btn_view_doc'>ดูรายละเอียด</button>";
        }
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          return "<button type='button' class='btn btn-success btn-sm text-nowrap' id='btn_document_approve'>อนุมัติ</button>";
        }
    }]
  });

  getDocumentAwaitApproveByAdmin();
});

$(document).on("click", "#btn_document_approve", function(event, data) {
  let dataTable = $('#tb_document_list').DataTable().row($(this).parents('tr')).data();
  changeStatusDocumentByAdmin(dataTable.log_id, 50);
});

function getDocumentAwaitApproveByAdmin() {
    let urlService = "/service/doc/getDocumentAwaitApproveByAdmin";

    let dataService = {};

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_document_list').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_document_list').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'info', 5000);
    });
}

function changeStatusDocumentByAdmin(logId, statusId) {
    let urlService = "/service/doc/changeStatusDocumentByAdmin";

    let dataService = {
      "log_id": logId,
      "status_id": statusId
    };

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 201) {
          let msg = success.responseJSON.user_message;

          getDocumentAwaitApproveByAdmin();
          showToast(msg, 'success', 3000);
        }
    }, function(fail) {
      console.log(fail.responseJSON);

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'danger', 5000);
    });
}
