<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\LovController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DocumentController;
use App\Models\DocumentLog;
use App\Models\UserPasswordReset;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// -- Authentication
Route::get('/register.htm', function () { return view('auth.register'); });
Route::get('/forgotpassword.htm', function () { return view('auth.forgotpassword'); });
Route::get('/resetpassword.htm/{username}/{token}', function ($username, $token) {
  $userPasswordResetObj = UserPasswordReset::where("username", base64_decode($username))->where("token", $token)->first();
   if(!is_null( $userPasswordResetObj)){
     if($userPasswordResetObj->is_active == 1) {
       if(Carbon::parse($userPasswordResetObj->created_at)->addMinutes(15) >= Carbon::now()) {
         return view('auth.resetpassword', ["username" => base64_decode($username)]);
       } else {
         return "<h2>ขออภัยลิ้งค์นี้หมดอายุแล้ว</h2>";
       }
     } else {
       return "<h2>ขออภัยลิ้งค์นี้ไม่สามารถใช้งานได้</h2>";
     }
   } else {
     return "<h2>ไม่พบข้อมูลผู้ใช้งาน</h2>";
   }
});
Route::get('/login.htm', function () { return view('auth.login'); });
Route::get('/', function () {
  if(!Auth::check()){
    return redirect('/login.htm');
  } else if(Auth::user()->role_id == 1) {
    return redirect('/u/index.htm');
  } else {
    return redirect('/a/dashboard.htm');
  }
});

// -- User
Route::get('/u/index.htm', function () { return view('pages.home'); })->middleware('role:1');
Route::get('/u/document/status.htm', function () { return view('pages.documentstatus'); })->middleware('role:1');
Route::get('/u/document/history.htm', function () { return view('pages.documenthistory'); })->middleware('role:1');
Route::get('/u/user/profile.htm', function () { return view('pages.userprofile'); })->middleware('role:1');
Route::get('/u/user/changepassword.htm', function () { return view('pages.userchangepassword'); })->middleware('role:1');
Route::get('/u/user/wastewater/address.htm', function () { return view('pages.wastewateraddress'); })->middleware('role:1');
Route::get('/u/waterquality/submit.htm', function () { return view('pages.waterqualitysubmit'); })->middleware('role:1');

Route::get('/u/document/view.htm', function (Request $request) {
  // if(Auth::user()->role_id == 1){
    return view('pages.document.view');
  // } else {
  //   $documentLogObj = DocumentLog::where("doc_no", $request->doc_no)->first();
  //   if(is_null($documentLogObj->document_reading_date)){
  //     return view('pages.document.view');
  //   } else {
  //     return "<script>alert('การยื่นแบบนี้มีเจ้าหน้าที่ท่านอื่นเปิดใช้งานอยู่');</script>";
  //   }
  // }
 })->middleware('role:1,2,3,4,5');
Route::get('/u/document/edit.htm', function (Request $request) {
  switch ($request->doc_type) {
    case 'YV1':
        return redirect('/u/document/yv1.htm?doc_no='.$request->doc_no);
      break;
    case 'YV2':
        return redirect('/u/document/yv2.htm?doc_no='.$request->doc_no);
      break;
    case 'RB1':
        return redirect('/u/document/rb1.htm?doc_no='.$request->doc_no);
      break;
    case 'PG1':
        return redirect('/u/document/pg1.htm?doc_no='.$request->doc_no);
      break;
    case 'PG2':
        return redirect('/u/document/pg2.htm?doc_no='.$request->doc_no);
      break;
    case 'NT1':
        return redirect('/u/document/nt1.htm?doc_no='.$request->doc_no);
      break;
    case 'NT2':
        return redirect('/u/document/nt2.htm?doc_no='.$request->doc_no);
      break;
  }
})->middleware('role:1');
Route::get('/u/document/yv1.htm', function () { return view('pages.document.yv1'); })->middleware('role:1');
Route::get('/u/document/yv2.htm', function () { return view('pages.document.yv2'); })->middleware('role:1');
Route::get('/u/document/rb1.htm', function () { return view('pages.document.rb1'); })->middleware('role:1');
Route::get('/u/document/pg1.htm', function () { return view('pages.document.pg1'); })->middleware('role:1');
Route::get('/u/document/pg2.htm', function () { return view('pages.document.pg2'); })->middleware('role:1');
Route::get('/u/document/nt1.htm', function () { return view('pages.document.nt1'); })->middleware('role:1');
Route::get('/u/document/nt2.htm', function () { return view('pages.document.nt2'); })->middleware('role:1');


// -- Admin
Route::get('/a/dashboard.htm', function () { return view('pages.admin.dashboard'); })->middleware('role:2,3,4,5');
Route::get('/a/document/check.htm', function () { return view('pages.admin.documentcheck'); })->middleware('role:2,3,4,5');
Route::get('/a/document/history.htm', function () { return view('pages.admin.documenthistory'); })->middleware('role:2,3,4,5');
Route::get('/a/document/awaitapprove.htm', function () { return view('pages.admin.documentawaitapprove'); })->middleware('role:2,3,4,5');
Route::get('/a/report/documentcompleted.htm', function () { return view('pages.admin.reportdocumentcompleted'); })->middleware('role:2,3,4,5');
Route::get('/a/report/documentapproved.htm', function () { return view('pages.admin.reportdocumentapproved'); })->middleware('role:2,3,4,5');
Route::get('/a/report/documentconnectpipe.htm', function () { return view('pages.admin.reportdocumentconnectpipe'); })->middleware('role:2,3,4,5');
Route::get('/a/waterquality/check.htm', function () { return view('pages.admin.waterqualitycheck'); })->middleware('role:2,3,4,5');
Route::get('/a/waterquality/history.htm', function () { return view('pages.admin.waterqualityhistory'); })->middleware('role:2,3,4,5');
Route::get('/a/user/list.htm', function () { return view('pages.admin.userlist'); })->middleware('role:2,3,4,5');
Route::get('/a/user/access.htm', function () { return view('pages.admin.useraccess'); })->middleware('role:2,3,4,5');





// ------------ Service User -------------
// -- Lov
Route::post('/service/lov/getLovProvince', [LovController::class, 'getLovProvince'])->middleware('role:1,2,3,4,5');
Route::post('/service/lov/getLovDistrictByProvinceCode', [LovController::class, 'getLovDistrictByProvinceCode'])->middleware('role:1,2,3,4,5');
Route::post('/service/lov/getLovSubDistrictByDistrictCode', [LovController::class, 'getLovSubDistrictByDistrictCode'])->middleware('role:1,2,3,4,5');
Route::post('/service/lov/getLovBuildingType', [LovController::class, 'getLovBuildingType'])->middleware('role:1,2,3,4,5');
Route::post('/service/lov/getLovBuildingSize', [LovController::class, 'getLovBuildingSize'])->middleware('role:1,2,3,4,5');
Route::post('/service/lov/getWastewaterTreatmentName', [LovController::class, 'getWastewaterTreatmentName'])->middleware('role:1,2,3,4,5');
// -- Email
Route::get('/email/verify/{username}/{datetime}', [UserProfileController::class, 'emailVerification']);
// -- Authentication
Route::post('/service/user/register', [UserProfileController::class, 'register']);
Route::post('/service/user/login', [UserProfileController::class, 'login']);
Route::post('/service/user/forgotpassword', [UserProfileController::class, 'forgotpassword']);
Route::post('/service/user/resetpassword', [UserProfileController::class, 'resetpassword']);
// -- User
Route::post('/service/user/register', [UserProfileController::class, 'register']);
Route::post('/service/user/getUserProfile', [UserProfileController::class, 'getUserProfileByToken'])->middleware('role:1,2,3,4,5');
Route::post('/service/user/updateUserProfile', [UserProfileController::class, 'updateUserProfileByToken'])->middleware('role:1,2,3,4,5');
Route::post('/service/user/changePassword', [UserProfileController::class, 'changePasswordByToken'])->middleware('role:1,2,3,4,5');
// -- Document
Route::get('/service/doc/file/{fid}', [DocumentController::class, 'viewDocumentFileByFileId'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/getDocumentInProcessByToken', [DocumentController::class, 'getDocumentInProcessByToken'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/getDocumentDetailByDocNo', [DocumentController::class, 'getDocumentDetailByDocNo'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/getDocumentNoApproveByToken', [DocumentController::class, 'getDocumentNoApproveByToken'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/cancelDocument', [DocumentController::class, 'cancelDocument'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/getDocumentHistory', [DocumentController::class, 'getDocumentHistory'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentYV1', [DocumentController::class, 'createDocumentYV1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentYV1', [DocumentController::class, 'updateDocumentYV1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentYV2', [DocumentController::class, 'createDocumentYV2'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentYV2', [DocumentController::class, 'updateDocumentYV2'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentRB1', [DocumentController::class, 'createDocumentRB1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentRB1', [DocumentController::class, 'updateDocumentRB1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentPG1', [DocumentController::class, 'createDocumentPG1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentPG1', [DocumentController::class, 'updateDocumentPG1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentPG2', [DocumentController::class, 'createDocumentPG2'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentPG2', [DocumentController::class, 'updateDocumentPG2'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentNT1', [DocumentController::class, 'createDocumentNT1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentNT1', [DocumentController::class, 'updateDocumentNT1'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentNT2', [DocumentController::class, 'createDocumentNT2'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/updateDocumentNT2', [DocumentController::class, 'updateDocumentNT2'])->middleware('role:1,2,3,4,5');
// -- Document Comment
Route::post('/service/doc/getDocumentCommentLogsByDocNo', [DocumentController::class, 'getDocumentCommentLogsByDocNo'])->middleware('role:1,2,3,4,5');
// -- Document Water Quality
Route::post('/service/doc/getDocumentWaterQualityByToken', [DocumentController::class, 'getDocumentWaterQualityByToken'])->middleware('role:1,2,3,4,5');
Route::post('/service/doc/createDocumentWaterQuality', [DocumentController::class, 'createDocumentWaterQuality'])->middleware('role:1,2,3,4,5');



// ------------ Service Admin -------------
// -- Document
Route::post('/service/doc/getDocumentOverviewByAdmin', [DocumentController::class, 'getDocumentOverviewByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/getDocumentInProcessByAdmin', [DocumentController::class, 'getDocumentInProcessByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/getDocumentAwaitApproveByAdmin', [DocumentController::class, 'getDocumentAwaitApproveByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/getDocumentApprovedByAdmin', [DocumentController::class, 'getDocumentApprovedByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/getDocumentApprovedConnectPipeByAdmin', [DocumentController::class, 'getDocumentApprovedConnectPipeByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/createDocumentCommentLogByAdmin', [DocumentController::class, 'createDocumentCommentLogByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/changeStatusDocumentByAdmin', [DocumentController::class, 'changeStatusDocumentByAdmin'])->middleware('role:2,3,4,5');
Route::get('/service/doc/reportUserDocumentCompleteExcel', [DocumentController::class, 'reportUserDocumentCompleteExcel'])->middleware('role:2,3,4,5');
Route::get('/service/doc/reportUserDocumentApproveExcel', [DocumentController::class, 'reportUserDocumentApproveExcel'])->middleware('role:2,3,4,5');
Route::get('/service/doc/reportUserDocumentApproveConnectPipeExcel', [DocumentController::class, 'reportUserDocumentApproveConnectPipeExcel'])->middleware('role:2,3,4,5');
// -- Document Water Quality
Route::post('/service/doc/getDocumentWaterQualityInProcessByAdmin', [DocumentController::class, 'getDocumentWaterQualityInProcessByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/changeStatusDocumentWaterQualityByAdmin', [DocumentController::class, 'changeStatusDocumentWaterQualityByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/doc/getDocumentWaterQualityHistoryByAdmin', [DocumentController::class, 'getDocumentWaterQualityHistoryByAdmin'])->middleware('role:2,3,4,5');
// -- Open and Close Document
Route::post('/service/doc/updateStatusDocumentReading', [DocumentController::class, 'updateStatusDocumentReading'])->middleware('role:2,3,4,5');
// -- User
Route::post('/service/user/getUserProfileDocumentViewByAdmin', [UserProfileController::class, 'getUserProfileDocumentViewByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/user/getUserProfileByAdmin', [UserProfileController::class, 'getUserProfileByAdmin'])->middleware('role:2,3,4,5');
Route::post('/service/user/getAccessLogByAdmin', [UserProfileController::class, 'getAccessLogByAdmin'])->middleware('role:2,3,4,5');
// -- Jobs
Route::get('/task/doc/checkDeadlineSubmitDocument', [DocumentController::class, 'checkDeadlineSubmitDocument']);
