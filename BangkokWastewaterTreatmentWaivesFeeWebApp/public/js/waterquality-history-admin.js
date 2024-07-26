$(document).ready(function() {
  moment.locale('th');

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
          "data": "quality_status_name",
          "className": "text-center text-nowrap",
          "render": function(data, type, row) {
            if(row.quality_status_id == 1){
              return "<span class='badge p-2 w-100 text-bg-warning'>"+ row.quality_status_name + "</span>";
            } else if(row.quality_status_id == 2){
              return "<span class='badge p-2 w-100 text-bg-success'>"+ row.quality_status_name + "</span>";
            } else if(row.quality_status_id == 3){
              return "<span class='badge p-2 w-100 text-bg-danger'>"+ row.quality_status_name + "</span>";
            }
          }
      }]
    });

  $("#btn_document_water_quality_search").click();
});

$(document).on("click", "#btn_document_water_quality_search", function(event, data) {
  let startDate = $("#fm_document_water_quality_search").find("[name=start_date]").val();
  let endDate = $("#fm_document_water_quality_search").find("[name=end_date]").val();
  let docNo = $("#fm_document_water_quality_search").find("[name=doc_no]").val();
  getDocumentWaterQualityHistoryByAdmin(startDate, endDate, docNo);
});

$(document).on("click", "#btn_view_file", function(event, data) {
  let fid = $(this).attr("attr-file-id");
  var openpopup = window.open("/service/doc/file/" + fid,"filewindow","width=1100,height=700,scrollbars=yes");
  openpopup.oncontextmenu = function() { return false; }
});

function getDocumentWaterQualityHistoryByAdmin(startDate, endDate, docNo) {
    let urlService = "/service/doc/getDocumentWaterQualityHistoryByAdmin";

    let dataService = {
      "start_date": startDate,
      "end_date": endDate,
      "doc_no": docNo
    };

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
