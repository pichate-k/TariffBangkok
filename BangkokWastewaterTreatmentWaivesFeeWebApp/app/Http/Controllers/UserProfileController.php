<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Hash;
use DB;
use Mail;
use App\Constants;
use App\Mail\MailController;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\AccessLog;
use App\Models\DocumentLog;
use App\Models\UserPasswordReset;

class UserProfileController extends Controller
{
  private $constants;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->constants = new Constants();
  }

  public function login(Request $request)
  {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $credentials = $request->only('username', 'password');
    if (Auth::attempt($credentials)) {
      if(Auth::user()->email_verify == 0){
          return back()->withInput()->withErrors($this->constants->MessageEmailNotConfirm);
      } else {
        return redirect("/");
      }
    }

    return back()->withInput()->withErrors($this->constants->MessageAuthenNotSuccess);
  }

  public function register(Request $request)
  {
    $rules = [
      'username' => 'required|email|unique:users',
      'password' => 'required|confirmed',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $message = $validator->messages();
      return back()->withInput()->withErrors($message);
    } else {

      try {
        $userToken = $request->user();

        $userObj = new User();
        $userObj->username = $request->username;
        $userObj->password = bcrypt($request->password);
        $userObj->role_id = 1;
        $userObj->email_verify = 0;
        $userObj->save();

        $mailDetails = [
           'mail_type' => "email_verify",
           'email' => $request->username,
           'link_confirm' => config('app.url')."/email/verify/".base64_encode($request->username)."/".base64_encode(Carbon::now()->addDays(1))
       ];

        Mail::to($request->username)->send(new \App\Mail\MailController($mailDetails));

        $message = $this->constants->MessageRegisterSuccessConfirmEmail;
        return back()->with($this->constants->StatusOK, $message);
      } catch (\Illuminate\Database\QueryException $e) {
        $message = $e->getMessage();
        return back()->with($this->constants->StatusNOK, $message);
      }
    }
  }

  public function emailVerification($username, $datetime)
  {
    try {
      // $linkExpiryDate = base64_decode($datetime);
      // if($linkExpiryDate < Carbon::now()){
      //   return "<h3>ขออภัยลิ้งค์นี้หมดอายุแล้ว</h3>";
      // }

      $userObj = User::where("username", base64_decode($username))->first();

      if(!is_null($userObj)){
        if($userObj->email_verify == 0){
          $userObj->email_verify = 1;
          $userObj->save();

          return "<h2>ยืนยันอีเมลเรียบร้อยแล้ว <a href='".(config('app.url')."/login.htm")."'>กรุณาเข้าสู่ระบบ</a></h2>";
        } else {
          return "<h2>อีเมลนี้ได้รับการยืนยันเรียบร้อยแล้ว</h2>";
        }
      }

      return "<h2>ไม่พบข้อมูลผู้ใช้งาน</h2>";
    } catch (\Illuminate\Database\QueryException $e) {
      return "<h2>ขออภัยลิ้งค์นี้ไม่ถูกต้องหรือหมดอายุแล้ว</h2>";
    }
  }

  public function forgotpassword(Request $request)
  {
    $rules = [
      'username' => 'required|email|exists:users,username',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $message = $validator->messages();
      return back()->withInput()->withErrors($message);
    } else {

      try {
        $userPasswordResetObj = new UserPasswordReset();
        $userPasswordResetObj->username = $request->username;
        $userPasswordResetObj->token = base64_encode(bcrypt(Carbon::now()->getTimeStamp()."BKK".$request->username));
        $userPasswordResetObj->created_at = Carbon::now();
        $userPasswordResetObj->is_active = 1;
        $userPasswordResetObj->save();

        $mailDetails = [
           'mail_type' => "reset_password",
           'email' => $request->username,
           'link_reset' => config('app.url')."/resetpassword.htm/".base64_encode($request->username)."/".$userPasswordResetObj->token
       ];

        Mail::to($request->username)->send(new \App\Mail\MailController($mailDetails));

        $message = $this->constants->MessageResetPasswordSuccessfully;
        return back()->with($this->constants->StatusOK, $message);
      } catch (\Illuminate\Database\QueryException $e) {
        $message = $e->getMessage();
        return back()->with($this->constants->StatusNOK, $message);
      }
    }
  }

  public function resetpassword(Request $request)
  {
    $rules = [
      'username' => 'required|email|exists:users,username',
      'password' => 'required|confirmed',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $message = $validator->messages();
      return back()->withInput()->withErrors($message);
    } else {

      try {
        DB::beginTransaction();

        $userObj = User::where("username", $request->username)->first();
        $userObj->password = bcrypt($request->password);
        $userObj->save();

        // UserPasswordReset::where('username', $request->username)->update(['is_active' => 0]);

        DB::commit();

        $message = $this->constants->MessageChangePasswordSuccessfully;
        return back()->with($this->constants->StatusOK, $message);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $message = $e->getMessage();
        return back()->with($this->constants->StatusNOK, $message);
      }
    }
  }

  public function getUserProfileByToken(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $userProfileObj = UserProfile::leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "user_profile.sub_district_code")
                         ->leftJoin("lov_district", "lov_district.district_code", "user_profile.district_code")
                         ->leftJoin("lov_provinces", "lov_provinces.province_code", "user_profile.province_code")
                         ->with("LovDistrict")
                         ->with("LovSubDistrict")
                         ->where("user_id", $userTokenObj->user_id)
                         ->first();

      if(!is_null($userProfileObj)){
        $this->constants->response_query["data"] = $userProfileObj;
        return response()->json($this->constants->response_query, 200);
      } else {
        $this->constants->response_query["code"] = 0;
        $this->constants->response_query["user_message"] = $this->constants->MessageDataNotFound;
        return response()->json($this->constants->response_query, 404);
      }
    } catch (\Illuminate\Database\QueryException $e) {
      $this->constants->response_query["code"] = 0;
      $this->constants->response_query["developer_message"] = $e->getMessage();
      $this->constants->response_query["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
      return response()->json($this->constants->response_query, 500);
    }
  }

  public function getUserProfileDocumentViewByAdmin(Request $request)
  {
    $rules = [
      'doc_no' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $documentLogObj = DocumentLog::where("doc_no", $request->doc_no)->first();
        $userProfileObj = UserProfile::leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "user_profile.sub_district_code")
                           ->leftJoin("lov_district", "lov_district.district_code", "user_profile.district_code")
                           ->leftJoin("lov_provinces", "lov_provinces.province_code", "user_profile.province_code")
                           ->with("LovDistrict")
                           ->with("LovSubDistrict")
                           ->where("user_id", $documentLogObj->user_id)
                           ->first();

        if(!is_null($userProfileObj) && $userTokenObj->role_id != 1){
          $this->constants->response_query["data"] = $userProfileObj;
          return response()->json($this->constants->response_query, 200);
        } else {
          $this->constants->response_query["code"] = 0;
          $this->constants->response_query["user_message"] = $this->constants->MessageDataNotFound;
          return response()->json($this->constants->response_query, 404);
        }
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_query["code"] = 0;
        $this->constants->response_query["developer_message"] = $e->getMessage();
        $this->constants->response_query["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_query, 500);
      }
    }
  }

  public function getUserProfileByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $userProfileList = User::leftJoin("user_profile", "user_profile.user_id", "users.user_id")
                         ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "user_profile.sub_district_code")
                         ->leftJoin("lov_district", "lov_district.district_code", "user_profile.district_code")
                         ->leftJoin("lov_provinces", "lov_provinces.province_code", "user_profile.province_code")
                         ->where("role_id", 1)
                         ->get();

      if(count($userProfileList) > 0 && $userTokenObj->role_id != 1){
        $this->constants->response_querylist["results"] = $userProfileList;
        $this->constants->response_querylist["results_count"] = count($userProfileList);
        return response()->json($this->constants->response_querylist, 200);
      } else {
        $this->constants->response_querylist["code"] = 0;
        $this->constants->response_querylist["user_message"] = $this->constants->MessageDataNotFound;
        return response()->json($this->constants->response_querylist, 404);
      }
    } catch (\Illuminate\Database\QueryException $e) {
      $this->constants->response_querylist["code"] = 0;
      $this->constants->response_querylist["developer_message"] = $e->getMessage();
      $this->constants->response_querylist["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
      return response()->json($this->constants->response_querylist, 500);
    }
  }

  public function getAccessLogByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $accessLogList = AccessLog::take(1000)->orderBy("timestamp", "desc")->get();

      if(count($accessLogList) > 0 && $userTokenObj->role_id != 1){
        $this->constants->response_querylist["results"] = $accessLogList;
        $this->constants->response_querylist["results_count"] = count($accessLogList);
        return response()->json($this->constants->response_querylist, 200);
      } else {
        $this->constants->response_querylist["code"] = 0;
        $this->constants->response_querylist["user_message"] = $this->constants->MessageDataNotFound;
        return response()->json($this->constants->response_querylist, 404);
      }
    } catch (\Illuminate\Database\QueryException $e) {
      $this->constants->response_querylist["code"] = 0;
      $this->constants->response_querylist["developer_message"] = $e->getMessage();
      $this->constants->response_querylist["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
      return response()->json($this->constants->response_querylist, 500);
    }
  }

  public function updateUserProfileByToken(Request $request)
  {
    $rules = [
      'name_title' => 'required',
      'name' => 'required',
      'user_type' => 'required',
      // 'age' => 'required_if:user_type,1',
      'nationality' => 'required_if:user_type,1',
      'tax_id' => 'required_if:user_type,1',
      'company_type_id' => 'required_if:user_type,2',
      // 'company_register_date' => 'required_if:user_type,2',
      'company_tax_id' => 'required_if:user_type,2',
      'address' => 'required',
      'province_code' => 'required',
      'district_code' => 'required',
      'sub_district_code' => 'required',
      'zip_code' => 'required',
      'email' => 'required',
      'mobile_phone' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $userProfileObj = UserProfile::where("user_id", $userTokenObj->user_id)->first();

        if (is_null($userProfileObj))
          $userProfileObj = new UserProfile();

        $userProfileObj->name_title = $request->name_title;
        $userProfileObj->name = $request->name;
        $userProfileObj->user_type = $request->user_type;

        $userProfileObj->age = $request->age;
        $userProfileObj->nationality = $request->nationality;
        $userProfileObj->tax_id = $request->tax_id;

        $userProfileObj->company_type_id = $request->company_type_id;
        $userProfileObj->company_register_date = $request->company_register_date;
        $userProfileObj->company_tax_id = $request->company_tax_id;

        $userProfileObj->address = $request->address;
        $userProfileObj->moo = $request->moo;
        $userProfileObj->soi = $request->soi;
        $userProfileObj->road = $request->road;
        $userProfileObj->province_code = $request->province_code;
        $userProfileObj->district_code = $request->district_code;
        $userProfileObj->sub_district_code = $request->sub_district_code;
        $userProfileObj->zip_code = $request->zip_code;
        $userProfileObj->email = $request->email;
        $userProfileObj->telephone = $request->telephone;
        $userProfileObj->mobile_phone = $request->mobile_phone;
        $userProfileObj->fax = $request->fax;

        $userProfileObj->user_id = $userTokenObj->user_id;
        $userProfileObj->last_update_date = Carbon::now();
        $userProfileObj->save();

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function changePasswordByToken(Request $request)
  {
    $rules = [
      'old_password' => 'required',
      'new_password' => 'required|same:confirm_password',
      'confirm_password' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        //** Check Authen **//
        if(!Hash::check(request('old_password'), $userTokenObj->password)) {
          $this->constants->response_query["code"] = 0;
          $this->constants->response_query["user_message"] = array("old_password" => [$this->constants->MessageOldPasswordInvalid]);
          return response()->json($this->constants->response_query, 400);
        }

        $userObj = User::where("user_id", $userTokenObj->user_id)->first();
        $userObj->password = bcrypt($request->new_password);
        $userObj->save();


        $mailDetails = [
           'mail_type' => "confirm_resetpassword",
           'email' => $userTokenObj->username
       ];

        Mail::to($userTokenObj->username)->send(new \App\Mail\MailController($mailDetails));


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageChangePasswordSuccessfully;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

}
