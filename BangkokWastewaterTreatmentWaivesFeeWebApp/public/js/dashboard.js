let chartYV1Last6Month;
let chartRB1Last6Month;

$(document).ready(function() {
  moment.locale('th');

  setDataChartYV1Last6Month([],[], [], []);
  setDataChartRB1Last6Month([],[], [], []);

  getDocumentOverviewByAdmin();

  // Bar chart
  new Chart(document.getElementById("chart_max_waive_fee_district"), {
    type: 'bar',
    data: {
      labels: [],
      datasets: [{
          label: "จำนวนขอยกเว้น",
          data: [],
          fill: true,
          borderRadius: 13,
          borderColor: '#fcb69f',
          backgroundColor: '#fcb69f'
        }]
    },
  });
});

function getDocumentOverviewByAdmin() {
    let urlService = "/service/doc/getDocumentOverviewByAdmin";

    let dataService = {};

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.data;

            $("#lb_total_document_awaiting_verify").text(autoCommaWithDecimal(datas.total_document_awaiting_verify));
            $("#lb_total_document_awaiting_approve").text(autoCommaWithDecimal(datas.total_document_awaiting_approve));
            $("#lb_total_document_approved").text(autoCommaWithDecimal(datas.total_document_approved));
            $("#lb_total_all_user").text(autoCommaWithDecimal(datas.total_all_user));

            // YV1 = Waive Fee
            let labelChartYV1Last6Month = [];
            let dataDocumentCreatedList = [];
            let dataDocumentCompletedList = [];
            let dataDocumentApprovedList = [];
            for (var i = 0; i < datas.total_document_yv1_last6month.length; i++) {
              labelChartYV1Last6Month.push(moment(datas.total_document_yv1_last6month[i].data_month).format("MMM YYYY"));
              dataDocumentCreatedList.push(datas.total_document_yv1_last6month[i].total_created_doc);
              dataDocumentCompletedList.push(datas.total_document_yv1_last6month[i].total_completed_doc);
              dataDocumentApprovedList.push(datas.total_document_yv1_last6month[i].total_approve_doc);
            }
            setDataChartYV1Last6Month(labelChartYV1Last6Month, dataDocumentCreatedList, dataDocumentCompletedList, dataDocumentApprovedList);

            // RB1 = Connect Pipe
            let labelChartRB1Last6Month = [];
            dataDocumentCreatedList = [];
            dataDocumentCompletedList = [];
            dataDocumentApprovedList = [];
            for (var i = 0; i < datas.total_document_rb1_last6month.length; i++) {
              labelChartRB1Last6Month.push(moment(datas.total_document_rb1_last6month[i].data_month).format("MMM YYYY"));
              dataDocumentCreatedList.push(datas.total_document_rb1_last6month[i].total_created_doc);
              dataDocumentCompletedList.push(datas.total_document_rb1_last6month[i].total_completed_doc);
              dataDocumentApprovedList.push(datas.total_document_rb1_last6month[i].total_approve_doc);
            }
            setDataChartRB1Last6Month(labelChartRB1Last6Month, dataDocumentCreatedList, dataDocumentCompletedList, dataDocumentApprovedList);
        }

    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        showToast(msg, 'danger', 5000);
    });
}

function setDataChartYV1Last6Month(labelList, dataDocumentCreatedList, dataDocumentCompletedList, dataDocumentApprovedList) {
  if (chartYV1Last6Month != undefined) {
    chartYV1Last6Month.destroy();
  }

  chartYV1Last6Month = new Chart(document.getElementById("chart_last_6m_waive_fee"), {
    type: 'bar',
    data: {
      labels: labelList,
      datasets: [{
          label: "จำนวนยื่นแบบ",
          data: dataDocumentCreatedList,
          fill: true,
          borderRadius: 10,
          borderColor: '#7C96AB',
          backgroundColor: '#7C96AB'
        },{
          label: "จำนวนการยื่นแบบถูกต้อง",
          data: dataDocumentCompletedList,
          fill: true,
          borderRadius: 10,
          borderColor: '#B7B7B7',
          backgroundColor: '#B7B7B7'
        },{
          label: "จำนวนการยื่นแบบได้รับอนุมัติ",
          data: dataDocumentApprovedList,
          fill: true,
          borderRadius: 10,
          borderColor: '#BFCCB5',
          backgroundColor: '#BFCCB5'
        }]
    },
  });
}

function setDataChartRB1Last6Month(labelList, dataDocumentCreatedList, dataDocumentCompletedList, dataDocumentApprovedList) {
  if (chartRB1Last6Month != undefined) {
    chartRB1Last6Month.destroy();
  }

  chartRB1Last6Month = new Chart(document.getElementById("chart_last_6m_connect_pipe"), {
    type: 'bar',
    data: {
      labels: labelList,
      datasets: [{
          label: "จำนวนยื่นแบบ",
          data: dataDocumentCreatedList,
          fill: true,
          borderRadius: 10,
          borderColor: '#7C96AB',
          backgroundColor: '#7C96AB'
        },{
          label: "จำนวนการยื่นแบบถูกต้อง",
          data: dataDocumentCompletedList,
          fill: true,
          borderRadius: 10,
          borderColor: '#B7B7B7',
          backgroundColor: '#B7B7B7'
        },{
          label: "จำนวนการยื่นแบบได้รับอนุมัติ",
          data: dataDocumentApprovedList,
          fill: true,
          borderRadius: 10,
          borderColor: '#BFCCB5',
          backgroundColor: '#BFCCB5'
        }]
    },
  });
}
