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
          if([10, 11, 12, 13].includes(row.doc_status)){
            return "<b class='text-danger'>" + row.doc_status_name + "</b>";
          } else if([20].includes(row.doc_status)){
            return "<b class='text-warning'>" + row.doc_status_name + "</b>";
          } else {
            return "<b>" + row.doc_status_name + "</b>";
          }
        }
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          return "<button type='button' class='btn btn-blue-bg btn-sm text-nowrap' id='btn_view_doc'>ตรวจสอบการยื่นแบบ</button>";
        }
    }]
  });

  getDocumentInProcessByAdmin();
});

function getDocumentInProcessByAdmin() {
    let urlService = "/service/doc/getDocumentInProcessByAdmin";

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
