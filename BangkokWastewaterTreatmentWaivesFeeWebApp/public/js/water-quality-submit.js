let dataDocumentApproveList = [];

$(document).ready(function() {

  moment.locale('th');
  renderWaterQualityMonthYear();

  $('#tb_water_quality_submit').DataTable({
    paging: false,
    ordering: true,
    searching: true,
    info: true,
    oLanguage: {
            sSearch: " ",
            sSearchPlaceholder: "ค้นหารายงาน"
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
        "data": "created_date",
        "className": "text-center text-nowrap",
        "render": function(data, type, row) {
          let date = moment(row.created_date);
          return ((date.isValid()) ? (date.format('DD/MM/') + (parseInt(date.format('YYYY')) + 543) + " " + date.format('HH:mm:ss')) : "-");
        }
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

  getDocumentWaterQualityByToken();
  getDocumentNoApproveByToken();
});

$(document).on("click", "#btn_save_waterqulity", function(event, data) {
    let formDocumentWaterQuality = new FormData($("#fn_waterqulity_submit")[0]);
    let docNo = $("#fn_waterqulity_submit").find("[name=doc_no]").val();

    if(docNo == "-1"){
      alert("กรุณาเลือกเลขที่แบบ");
    } else {
      createDocumentWaterquality(formDocumentWaterQuality);
    }
});

$(document).on("hidden.bs.modal", "#modalFormWaterQualiy", function(event, data) {
    $("#fn_waterqulity_submit").trigger("reset");
    renderWaterQualityMonthYear();
});

$(document).on("change", "#fn_waterqulity_submit [name=doc_no]", function(event, data) {
    $("#fn_waterqulity_submit").find("[name=address_name]").val("");
    $("#fn_waterqulity_submit").find("[name=address_code]").val("");

    if (this.value != -1) {
      let indexDropdown = $("#fn_waterqulity_submit").find("[name=doc_no] option:selected").attr("attr-index");
      $("#fn_waterqulity_submit").find("[name=address_name]").val(dataDocumentApproveList[indexDropdown].address_name);
      $("#fn_waterqulity_submit").find("[name=address_code]").val(dataDocumentApproveList[indexDropdown].address_code);
    }
});

function getDocumentWaterQualityByToken() {
    let urlService = "/service/doc/getDocumentWaterQualityByToken";

    let dataService = {};

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            let t = $('#tb_water_quality_submit').DataTable();
            t.clear().draw();
            t.rows.add(datas).draw();
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      $('#tb_water_quality_submit').DataTable().clear().draw();

      let msg = fail.responseJSON.user_message;
      // showToast(msg, 'danger', 5000);
    });
}

function renderWaterQualityMonthYear(){
  let currentMonth = moment().format('M');

  if(currentMonth == 1) {
    $("#fn_waterqulity_submit").find("[name=data_year]").val(parseInt(moment().format('YYYY')) + 543 - 1);
  } else if (currentMonth > 6){
    $("#fn_waterqulity_submit").find("[name=data_year]").val(parseInt(moment().format('YYYY')) + 543);
  } else {
    $("#fn_waterqulity_submit").find("[name=data_year]").val(parseInt(moment().format('YYYY')) + 543);
  }

  renderDropdownMonth(currentMonth);
}

function renderDropdownMonth(currentMonth) {
  let option = "<option value='-1'>กรุณาเลือกเดือนรายงานผล</option>";
  let monthList = [];

  if(currentMonth == 1) {
    monthList.push({ "month": moment().subtract(1, 'years').format('YYYY-07-01'), "month_desc": moment(moment().subtract(1, 'years').format('YYYY-07-01')).format('MMMM') });
    monthList.push({ "month": moment().subtract(1, 'years').format('YYYY-08-01'), "month_desc": moment(moment().subtract(1, 'years').format('YYYY-08-01')).format('MMMM') });
    monthList.push({ "month": moment().subtract(1, 'years').format('YYYY-09-01'), "month_desc": moment(moment().subtract(1, 'years').format('YYYY-09-01')).format('MMMM') });
    monthList.push({ "month": moment().subtract(1, 'years').format('YYYY-10-01'), "month_desc": moment(moment().subtract(1, 'years').format('YYYY-10-01')).format('MMMM') });
    monthList.push({ "month": moment().subtract(1, 'years').format('YYYY-11-01'), "month_desc": moment(moment().subtract(1, 'years').format('YYYY-11-01')).format('MMMM') });
    monthList.push({ "month": moment().subtract(1, 'years').format('YYYY-12-01'), "month_desc": moment(moment().subtract(1, 'years').format('YYYY-12-01')).format('MMMM') });
  } else if(currentMonth == 7) {
    monthList.push({ "month": moment().format('YYYY-01-01'), "month_desc": moment(moment().format('YYYY-01-01')).format('MMMM') });
    monthList.push({ "month": moment().format('YYYY-02-01'), "month_desc": moment(moment().format('YYYY-02-01')).format('MMMM') });
    monthList.push({ "month": moment().format('YYYY-03-01'), "month_desc": moment(moment().format('YYYY-03-01')).format('MMMM') });
    monthList.push({ "month": moment().format('YYYY-04-01'), "month_desc": moment(moment().format('YYYY-04-01')).format('MMMM') });
    monthList.push({ "month": moment().format('YYYY-05-01'), "month_desc": moment(moment().format('YYYY-05-01')).format('MMMM') });
    monthList.push({ "month": moment().format('YYYY-06-01'), "month_desc": moment(moment().format('YYYY-06-01')).format('MMMM') });
  } else {
    if(currentMonth > 7) {
      if (currentMonth >= 7)
        monthList.push({ "month": moment().format('YYYY-07-01'), "month_desc": moment(moment().format('YYYY-07-01')).format('MMMM') });
      if (currentMonth >= 8)
        monthList.push({ "month": moment().format('YYYY-08-01'), "month_desc": moment(moment().format('YYYY-08-01')).format('MMMM') });
      if (currentMonth >= 9)
        monthList.push({ "month": moment().format('YYYY-09-01'), "month_desc": moment(moment().format('YYYY-09-01')).format('MMMM') });
      if (currentMonth >= 10)
        monthList.push({ "month": moment().format('YYYY-10-01'), "month_desc": moment(moment().format('YYYY-10-01')).format('MMMM') });
      if (currentMonth >= 11)
        monthList.push({ "month": moment().format('YYYY-11-01'), "month_desc": moment(moment().format('YYYY-11-01')).format('MMMM') });
      if (currentMonth >= 12)
        monthList.push({ "month": moment().format('YYYY-12-01'), "month_desc": moment(moment().format('YYYY-12-01')).format('MMMM') });
    } else {
      if (currentMonth >= 1)
        monthList.push({ "month": moment().format('YYYY-01-01'), "month_desc": moment(moment().format('YYYY-01-01')).format('MMMM') });
      if (currentMonth >= 2)
        monthList.push({ "month": moment().format('YYYY-02-01'), "month_desc": moment(moment().format('YYYY-02-01')).format('MMMM') });
      if (currentMonth >= 3)
        monthList.push({ "month": moment().format('YYYY-03-01'), "month_desc": moment(moment().format('YYYY-03-01')).format('MMMM') });
      if (currentMonth >= 4)
        monthList.push({ "month": moment().format('YYYY-04-01'), "month_desc": moment(moment().format('YYYY-04-01')).format('MMMM') });
      if (currentMonth >= 5)
        monthList.push({ "month": moment().format('YYYY-05-01'), "month_desc": moment(moment().format('YYYY-05-01')).format('MMMM') });
      if (currentMonth >= 6)
        monthList.push({ "month": moment().format('YYYY-06-01'), "month_desc": moment(moment().format('YYYY-06-01')).format('MMMM') });
    }
  }

  for (var i = 0; i < monthList.length; i++) {
    option += "<option value='" + monthList[i].month + "'>" + monthList[i].month_desc + "</option>";
  }

  $("#fn_waterqulity_submit").find("[name=data_month]").empty().append(option);
}

function getDocumentNoApproveByToken() {
    let urlService = "/service/doc/getDocumentNoApproveByToken";

    let dataService = {};

    callWebServiceTask(disableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataTrue, contentTypeApplicationXWwwFormUrlencoded, function(success) {
        console.log(JSON.stringify(success.responseJSON));

        if (success.status == 200) {
            let datas = success.responseJSON.results;

            dataDocumentApproveList = datas;

            let option = "<option value='-1'>กรุณาเลือกเลขที่แบบ</option>";
            for (var i = 0; i < datas.length; i++) {
              option += "<option value='" + datas[i].doc_no + "' attr-index='" + i + "'>" + datas[i].doc_no + "</option>";
            }

            $("#fn_waterqulity_submit").find("[name=doc_no]").empty().append(option);
        }

    }, function(fail) {
      console.log(fail.responseJSON);

      let msg = fail.responseJSON.user_message;
      // showToast(msg, 'danger', 5000);
    });
}

function createDocumentWaterquality(input) {
    let urlService = "/service/doc/createDocumentWaterQuality";

    let dataService = input;

    callWebServiceTask(enableLoading, requestTypePost, urlService, dataService, dataTypeJson, cacheFalse, processDataFalse, false, function(success) {
        console.log(success.responseJSON);

        if (success.status == 201) {
            let msg = success.responseJSON.user_message;
            showToast(msg, 'success', 3000);

            getDocumentWaterQualityByToken();

            $("#modalFormWaterQualiy").modal("hide");

            clearValidationUI();
        }
    }, function(fail) {
        console.log(fail.responseJSON);

        let msg = fail.responseJSON.user_message;
        if (fail.status == 400) {
            clearValidationUI();
            for (var text in msg) {
              addValidatetionUI($("#fn_waterqulity_submit").find("[name=" + text + "]"), msg[text]);
            }
        } else if (fail.status == 500) {
            showToast(msg, 'danger', 5000);
        }
    });
}
