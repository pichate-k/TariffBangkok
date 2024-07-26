$(document).ready(function() {

  $('#tb_document_inprocess').DataTable({
    paging: false,
    ordering: false,
    searching: false,
    info: false,
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
          if([11, 12, 13].includes(row.doc_status)) {
            return "<b class='text-danger'>" + row.doc_status_name + "</b>";
          } else if([1].includes(row.doc_status)) {
            return "<b class='text-success'>" + row.doc_status_name + "</b>";
          } else {
            return "<b>" + row.doc_status_name + "</b>";
          }
        }
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          if([11, 12, 13].includes(row.doc_status)){
            return "<a type='button' href='/u/document/edit.htm?doc_type=" + row.doc_type + "&doc_no=" + row.doc_no + "' class='btn btn-warning btn-sm text-nowrap'>แก้ไขการยื่นแบบ</a>";
          } else {
            return "<button type='button' class='btn btn-blue-bg btn-sm text-nowrap' id='btn_view_doc'>ดูรายละเอียด</button>";
          }
        }
    },{
        "className": "text-center",
        "render": function(data, type, row) {
          if([1,50].includes(row.doc_status)){
            return "<button type='button' class='btn btn-danger btn-sm text-nowrap' id='btn_cancel_doc'disabled>ยกเลิกการยื่นแบบ</button>";
          } else {
            return "<button type='button' class='btn btn-danger btn-sm text-nowrap' id='btn_cancel_doc'>ยกเลิกการยื่นแบบ</button>";
          }
        }
    }]
  });

  getDocumentInProcessByToken();
});

$(document).on("click", "#btn_view_doc", function(event, data) {
  let dataTable = $('#tb_document_inprocess').DataTable().row($(this).parents('tr')).data();
  let url = "/u/document/view.htm?doc_type=" + dataTable.doc_type + "&doc_no="+ dataTable.doc_no;
  window.open(url, "mywindow","width=1100,height=700,scrollbars=yes");
});

$(document).on("click", "#btn_cancel_doc", function(event, data) {
  let dataTable = $('#tb_document_inprocess').DataTable().row($(this).parents('tr')).data();
  let c = confirm("ต้องการยกเลิกการยื่นแบบ" + dataTable.doc_type_name + " เลขที่: " + dataTable.doc_no + " หรือไม่");
  if (c == true) {
    cancelDocument(dataTable.doc_no);
  }
});

function getDocumentInProcessByToken() {
    let urlService = "/service/doc/getDocumentInProcessByToken";

    let dataService = {};

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_document_inprocess').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_document_inprocess').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      // showToast(msg, 'danger', 5000);
    });
}

function cancelDocument(docNo) {
    let urlService = "/service/doc/cancelDocument";

    let dataService = {
        "doc_no": docNo,
    };

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(success.responseJSON);
        if (success.status == 201) {
            let msg = success.responseJSON.user_message;
            showToast(msg, 'success', 3000);

            getDocumentInProcessByToken();
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
    });
}
