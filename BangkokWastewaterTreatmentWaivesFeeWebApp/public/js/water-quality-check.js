$(document).ready(function() {
  moment.locale('th');

  $('#tb_document_water_quality').DataTable({
    paging: false,
    ordering: false,
    searching: true,
    info: true,
    oLanguage: {
            sSearch: " ",
            sSearchPlaceholder: "ค้นหา"
    },
    "data": [],
    "columns": [{
        "className": "text-center text-nowrap",
        "width": "5%",
        "render": function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
    },{
        "data": "doc_no",
        "className": "text-center text-nowrap",
    },{
        "data": "data_month",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.data_month);
          return ((date.isValid()) ? date.format('MMMM') : "-");
        }
    },{
        "data": "data_month",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.data_month);
          return ((date.isValid()) ? (parseInt(date.format('YYYY')) + 543) : "-");
        }
    },{
        "data": "doc_attach_1",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          return "<a class='btn btn-link p-0' href='#' role='button' attr-file-id='" + btoa(row.doc_attach_1) + "' id='btn_view_file'>เอกสารแนบ 1</a>";
        }
    },{
        "data": "created_date",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.created_date);
          return ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543) + " " + date.format('HH:mm:ss')) : "-");
        }
    },{
        "data": "name",
        "className": "text-center text-nowrap",
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          return "<button type='button' class='btn btn-success btn-sm text-nowrap' id='btn_approve_water_quality'>อนุมัติผล</button>";
        }
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          return "<button type='button' class='btn btn-danger btn-sm text-nowrap' id='btn_reject_water_quality'>ไม่อนุมัติผล</button>";
        }
    }]
  });

  getDocumentWaterQualityInProcessByAdmin();
});

$(document).on("click", "#btn_view_file", function(event, data) {
  let fid = $(this).attr("attr-file-id");
  var openpopup = window.open("/service/doc/file/" + fid,"filewindow","width=1100,height=700,scrollbars=yes");
  openpopup.oncontextmenu = function() { return false; }
});

$(document).on("click", "#btn_approve_water_quality", function(event, data) {
  let dataTable = $('#tb_document_water_quality').DataTable().row($(this).parents('tr')).data();
  changeStatusDocumentWaterQualityByAdmin(dataTable.log_id, 2);
});

$(document).on("click", "#btn_reject_water_quality", function(event, data) {
  let dataTable = $('#tb_document_water_quality').DataTable().row($(this).parents('tr')).data();
  changeStatusDocumentWaterQualityByAdmin(dataTable.log_id, 3);
});

function getDocumentWaterQualityInProcessByAdmin() {
    let urlService = "/service/doc/getDocumentWaterQualityInProcessByAdmin";

    let dataService = {};

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_document_water_quality').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_document_water_quality').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'info', 5000);
    });
}

function changeStatusDocumentWaterQualityByAdmin(logId, statusId) {
    let urlService = "/service/doc/changeStatusDocumentWaterQualityByAdmin";

    let dataService = {
      "log_id": logId,
      "status_id": statusId
    };

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 201) {
          let msg = success.responseJSON.user_message;

          getDocumentWaterQualityInProcessByAdmin();
          showToast(msg, 'success', 3000);
        }
    }, function(fail) {
      console.log(fail.responseJSON);

      let msg = fail.responseJSON.user_message;
      showToast(msg, 'danger', 5000);
    });
}
