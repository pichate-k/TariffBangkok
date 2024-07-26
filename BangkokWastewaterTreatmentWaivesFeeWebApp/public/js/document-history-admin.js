$(document).ready(function() {

  $(".startdatetimepicker").datetimepicker({
        icons: {
            next: 'fa-solid fa-right-long',
            previous: 'fa-solid fa-left-long'
        },
        sideBySide: true,
        locale: 'th',
        format: 'YYYY-MM-DD',
        defaultDate: moment().subtract(7,'d').format('YYYY-MM-DD'),
    });
  $(".enddatetimepicker").datetimepicker({
        icons: {
            next: 'fa-solid fa-right-long',
            previous: 'fa-solid fa-left-long'
        },
        sideBySide: true,
        locale: 'th',
        format: 'YYYY-MM-DD',
        defaultDate: moment().format('YYYY-MM-DD'),
    });

  $('#tb_document_list').DataTable({
    paging: false,
    ordering: true,
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
          if([0,2,3].includes(row.doc_status)){
            return "<b class='text-danger'>" + row.doc_status_name + "</b>";
          } else if([1].includes(row.doc_status)){
            return "<b class='text-success'>" + row.doc_status_name + "</b>";
          } else {
            return "<b>" + row.doc_status_name + "</b>";
          }
        }
    },{
        "className": "text-center",
        "render": function(data, type, row, meta) {
          return "<button type='button' class='btn btn-blue-bg btn-sm text-nowrap' id='btn_view_doc'>ดูรายละเอียด</button>";
        }
    }]
  });

  $("#btn_document_search").click();
});

$(document).on("click", "#btn_document_search", function(event, data) {
  let startDate = $("#fm_document_search").find("[name=start_date]").val();
  let endDate = $("#fm_document_search").find("[name=end_date]").val();
  let docType = $("#fm_document_search").find("[name=doc_type]").val();
  let docNo = $("#fm_document_search").find("[name=doc_no]").val();
  getDocumentHistory(startDate, endDate, docType, docNo);
});

function getDocumentHistory(startDate, endDate, docType, docNo) {
    let urlService = "/service/doc/getDocumentHistory";

    let dataService = {
      "start_date": startDate,
      "end_date": endDate,
      "doc_type": docType,
      "doc_no": docNo
    };

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
