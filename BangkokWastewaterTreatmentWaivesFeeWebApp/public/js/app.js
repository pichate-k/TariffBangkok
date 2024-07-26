function showToast(msg, color, delay) {
    let toastId = "toast-" + new Date().getTime();

    $("#layout-toast-container").append(`
      <div id="` + toastId + `"  class="toast position-fixed top-0 end-0 mt-5 m-3 text-bg-` + color + ` border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="` + delay + `">
        <div class="d-flex">
          <div class="toast-body">
            <h5 class="p-2">` + msg + `</h5>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
      `);

    var myToast = bootstrap.Toast.getOrCreateInstance(document.getElementById(toastId));
    myToast.show();
}

function addValidatetionUI(element, msg) {
  if(element.parent().attr("class").includes("input-group")){
    element.addClass("is-invalid").parent().parent().append("<em class='text-danger text-left'>" + msg + "</em>");
  } else {
    element.addClass("is-invalid").parent().append("<em class='text-danger text-left'>" + msg + "</em>");
  }
}
function clearValidationUI() {
    $("em").remove();
    $(".is-invalid").removeClass("is-invalid");
}

function autoCommaWithDecimal(Num, decimal) { //function to add commas to textboxes
    if (isNaN(Num))
        return 0;

    Num = parseFloat(Num).toFixed(decimal);
    Num += '';
    Num = Num.replace(',', '');
    Num = Num.replace(',', '');
    Num = Num.replace(',', '');
    Num = Num.replace(',', '');
    Num = Num.replace(',', '');
    Num = Num.replace(',', '');
    x = Num.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    return x1 + x2;
}

function isNull2Blank(str) {
    return (str == null) ? "" : str;
}
