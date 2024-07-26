//** request type **//
let requestTypeGet = "GET";
let requestTypePost = "POST";

//** data type **//
let dataTypeJson = "json";

//** cache **//
let cacheTrue = true;
let cacheFalse = false;

//** processData **//
let processDataTrue = true;
let processDataFalse = false;

//** content type **//
let contentTypeMultipartFormData = "multipart/form-data";
let contentTypeApplicationXWwwFormUrlencoded = "application/x-www-form-urlencoded";
let contentTypeApplicationJson = "application/json";
let contentTypeApplicationXml = "application/xml";
let contentTypeApplicationBase64 = "application/base64";
let contentTypeApplicationOctetStream = "application/octet-stream";
let contentTypeTextPlain = "text/plain";
let contentTypeTextCss = "text/css";
let contentTypeTextHtml = "text/html";
let contentTypeApplicationJavascript = "application/javascript";

let enableLoading = true;
let disableLoading = false;

function callWebServiceTask(showLoading, requestTypeInput, urlInput, dataInput, dataTypeInput, cacheInput, processDataInput, contentTypeInput, callBackSuccess, callBackFail) {
    let hasElementLoading = $("#loadingModal").length > 0;

    if (showLoading && hasElementLoading)
        $("#loadingModal").modal("show");

    setTimeout(
        function() {
            $.ajax({
                type: requestTypeInput,
                url: urlInput,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: dataInput,
                dataType: dataTypeInput,
                cache: cacheInput,
                processData: processDataInput,
                contentType: contentTypeInput,
                success: function(data, textStatus, jqXHR) {
                    if (showLoading && hasElementLoading)
                        $("#loadingModal").modal("hide");

                    callBackSuccess(jqXHR);
                },
                error: function(jqXHR, errStatus, errThrown) {
                    if (showLoading && hasElementLoading)
                        $("#loadingModal").modal("hide");

                    callBackFail(jqXHR);
                }
            });
        }, 1000);
}
