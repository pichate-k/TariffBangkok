<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Hash;
use DB;
use Image;
use Mail;
use Excel;

use App\Constants;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\DocumentSeqGen;
use App\Models\DocumentYV1;
use App\Models\DocumentYV2;
use App\Models\DocumentRB1;
use App\Models\DocumentPG1;
use App\Models\DocumentPG2;
use App\Models\DocumentNT1;
use App\Models\DocumentNT2;
use App\Models\DocumentLog;
use App\Models\DocumentCommentLog;
use App\Models\DocumentFile;
use App\Models\DocumentWaterQualityLog;

use App\Models\LovDocumentType;
use App\Models\LovDocumentWaterQualityStatus;

use App\Exports\DocumentCompleteReportExport;
use App\Exports\DocumentApproveReportExport;
use App\Exports\DocumentApproveConnectPipeReportExport;

class DocumentController extends Controller
{
  private $constants;
  private $userObj;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->constants = new Constants();
  }

  public function getDocumentInProcessByToken(Request $request)
  {
    try {
      $userTokenObj = $request->user();
      $this->userObj = $userTokenObj;

      $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                    ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                    ->where("user_id", $userTokenObj->user_id)
                                    ->whereIn("doc_status", [10,11,12,13])
                                    ->orWhere(function ($query) {
                                      $query->whereIn("doc_status", [1])
                                            ->where("user_id", $this->userObj->user_id)
                                            ->where("approve_date", ">", Carbon::now()->subDays(15));
                                    })
                                    ->orderBy("created_date", "asc")
                                    ->get();

      if(count($documentLogList) > 0){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function getDocumentCommentLogsByDocNo(Request $request)
  {
    $rules = [
      'doc_no' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_querylist["code"] = 0;
      $this->constants->response_querylist['user_message'] = $validator->messages();
      return response()->json($this->constants->response_querylist, 400);
    } else {
      try {
        $userTokenObj = $request->user();

        $documentCommentLogList = DocumentCommentLog::join("lov_document_status","lov_document_status.doc_status_id", "document_comment_logs.doc_status")
                                        ->join("users","users.user_id", "document_comment_logs.comment_by")
                                        ->where("doc_no", $request->doc_no)
                                        ->orderBy("comment_date", "asc")
                                        ->get();

        if(count($documentCommentLogList) > 0){
          $this->constants->response_querylist["results"] = $documentCommentLogList;
          $this->constants->response_querylist["results_count"] = count($documentCommentLogList);
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
  }

  public function getDocumentHistory(Request $request)
  {
    $rules = [
      'start_date' => 'required',
      'end_date' => 'required',
      'doc_type' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_querylist["code"] = 0;
      $this->constants->response_querylist['user_message'] = $validator->messages();
      return response()->json($this->constants->response_querylist, 400);
    } else {
      try {
        $userTokenObj = $request->user();

        if($userTokenObj->role_id == 1){
          if($request->doc_type == "ALL" && is_null($request->doc_no)){
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->where("user_id", $userTokenObj->user_id)
                                          ->where("created_date", ">=", ($request->start_date." 00:00:00"))
                                          ->where("created_date", "<=", ($request->end_date." 23:59:59"))
                                          ->get();
          } else if($request->doc_type == "ALL" && !is_null($request->doc_no)){
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->where("user_id", $userTokenObj->user_id)
                                          ->where("created_date", ">=", ($request->start_date." 00:00:00"))
                                          ->where("created_date", "<=", ($request->end_date." 23:59:59"))
                                          ->where("doc_no", "like", '%'.$request->doc_no.'%')
                                          ->get();
          } else if(!is_null($request->doc_no)){
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->where("user_id", $userTokenObj->user_id)
                                          ->where("created_date", ">=", $request->start_date)
                                          ->where("created_date", "<=", $request->end_date)
                                          ->where("doc_type", $request->doc_type)
                                          ->where("doc_no", "like", '%'.$request->doc_no.'%')
                                          ->get();
          } else {
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->where("user_id", $userTokenObj->user_id)
                                          ->where("created_date", ">=", $request->start_date)
                                          ->where("created_date", "<=", $request->end_date)
                                          ->where("doc_type", $request->doc_type)
                                          ->get();
          }
        } else {
          if($request->doc_type == "ALL" && is_null($request->doc_no)){
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                          ->where("created_date", ">=", ($request->start_date." 00:00:00"))
                                          ->where("created_date", "<=", ($request->end_date." 23:59:59"))
                                          ->get();
          } else if($request->doc_type == "ALL" && !is_null($request->doc_no)){
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                          ->where("created_date", ">=", ($request->start_date." 00:00:00"))
                                          ->where("created_date", "<=", ($request->end_date." 23:59:59"))
                                          ->where("doc_no", "like", '%'.$request->doc_no.'%')
                                          ->get();
          } else if(!is_null($request->doc_no)){
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                          ->where("created_date", ">=", $request->start_date)
                                          ->where("created_date", "<=", $request->end_date)
                                          ->where("doc_type", $request->doc_type)
                                          ->where("doc_no", "like", '%'.$request->doc_no.'%')
                                          ->get();
          } else {
            $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                          ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                          ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                          ->where("created_date", ">=", $request->start_date)
                                          ->where("created_date", "<=", $request->end_date)
                                          ->where("doc_type", $request->doc_type)
                                          ->get();
          }
        }


        if(count($documentLogList) > 0){
          $this->constants->response_querylist["results"] = $documentLogList;
          $this->constants->response_querylist["results_count"] = count($documentLogList);
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
  }

  public function getDocumentDetailByDocNo(Request $request)
  {
    $rules = [
      'doc_type' => 'required',
      'doc_no' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_query["code"] = 0;
      $this->constants->response_query['user_message'] = $validator->messages();
      return response()->json($this->constants->response_query, 400);
    } else {
      try {
        $userTokenObj = $request->user();

        switch ($request->doc_type) {
          case 'YV1':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_yv_1", "document_yv_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_yv_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_yv_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_yv_1.province_code")
                                   ->leftJoin("lov_building_size", "lov_building_size.building_type_id", "document_yv_1.wastewater_source_building_type")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->with("LovBuildingSize")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_yv_1", "document_yv_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_yv_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_yv_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_yv_1.province_code")
                                   ->leftJoin("lov_building_size", "lov_building_size.building_type_id", "document_yv_1.wastewater_source_building_type")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->with("LovBuildingSize")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
          case 'YV2':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_yv_2", "document_yv_2.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_yv_2.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_yv_2.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_yv_2.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_yv_2", "document_yv_2.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_yv_2.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_yv_2.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_yv_2.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
          case 'RB1':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_rb_1", "document_rb_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_rb_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_rb_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_rb_1.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_rb_1", "document_rb_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_rb_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_rb_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_rb_1.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
          case 'PG1':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_pg_1", "document_pg_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_pg_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_pg_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_pg_1.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_pg_1", "document_pg_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_pg_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_pg_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_pg_1.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
          case 'PG2':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_pg_2", "document_pg_2.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_pg_2.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_pg_2.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_pg_2.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_pg_2", "document_pg_2.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_pg_2.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_pg_2.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_pg_2.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
          case 'NT1':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_nt_1", "document_nt_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_nt_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_nt_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_nt_1.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_nt_1", "document_nt_1.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_nt_1.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_nt_1.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_nt_1.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
          case 'NT2':
            if($userTokenObj->role_id == 1){
              $documentLogObj = DocumentLog::join("document_nt_2", "document_nt_2.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_nt_2.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_nt_2.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_nt_2.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.user_id", $userTokenObj->user_id)
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            } else {
              $documentLogObj = DocumentLog::join("document_nt_2", "document_nt_2.doc_no", "document_logs.doc_no")
                                   ->leftJoin("lov_sub_district", "lov_sub_district.sub_district_code", "document_nt_2.sub_district_code")
                                   ->leftJoin("lov_district", "lov_district.district_code", "document_nt_2.district_code")
                                   ->leftJoin("lov_provinces", "lov_provinces.province_code", "document_nt_2.province_code")
                                   ->with("LovDistrict")
                                   ->with("LovSubDistrict")
                                   ->where("document_logs.doc_no", $request->doc_no)
                                   ->first();
            }
            break;
        }


        if(!is_null($documentLogObj)){
          $this->constants->response_query["data"] = $documentLogObj;
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

  public function getDocumentWaterQualityByToken(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentLogList = DocumentLog::join("document_water_quality_logs", "document_water_quality_logs.doc_no", "document_logs.doc_no")
                                    ->join("lov_document_water_quality_status", "lov_document_water_quality_status.quality_status_id", "document_water_quality_logs.status")
                                    ->where("user_id", $userTokenObj->user_id)
                                    ->whereIn("doc_status", [1,50])
                                    ->where("doc_type", "YV1")
                                    ->selectRaw(" document_logs.doc_no, bod, ph, tss, fog, data_month, lov_document_water_quality_status.quality_status_name, lov_document_water_quality_status.quality_status_id, document_water_quality_logs.created_date ")
                                    ->get();

      if(count($documentLogList) > 0){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function getDocumentNoApproveByToken(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentLogList = DocumentLog::join("document_yv_1", "document_yv_1.doc_no", "document_logs.doc_no")
                                    ->where("user_id", $userTokenObj->user_id)
                                    ->whereIn("doc_status", [1,50])
                                    ->where("doc_type", "YV1")
                                    ->selectRaw(" document_logs.doc_no, address_name, address_code ")
                                    ->get();

      if(count($documentLogList) > 0){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function createDocumentWaterQuality(Request $request)
  {
    $rules = [
      'doc_no' => 'required',
      'data_month' => 'required',
      // 'doc_no' => 'required|unique:document_water_quality_logs,doc_no,'.$request->doc_no.',data_month,data_month,'.$request->data_month,
      // 'data_month' => 'required|unique:document_water_quality_logs,data_month,'.$request->data_month.',doc_no,doc_no,'.$request->doc_no,
      // 'bod'  => 'required',
      // 'ph'  => 'required',
      // 'tss'  => 'required',
      // 'fog'  => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $documentWaterQualityLogObj = DocumentWaterQualityLog::where("doc_no", $request->doc_no)->where("data_month", $request->data_month)->first();

        if(is_null($documentWaterQualityLogObj))
          $documentWaterQualityLogObj = new DocumentWaterQualityLog();

        $documentWaterQualityLogObj->doc_no = $request->doc_no;
        $documentWaterQualityLogObj->data_month = $request->data_month;
        $documentWaterQualityLogObj->bod = $request->bod;
        $documentWaterQualityLogObj->ph = $request->ph;
        $documentWaterQualityLogObj->tss = $request->tss;
        $documentWaterQualityLogObj->fog = $request->fog;
        $documentWaterQualityLogObj->status = 1;
        $documentWaterQualityLogObj->created_date = Carbon::now();
        $documentWaterQualityLogObj->created_by = $userTokenObj->user_id;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_wq_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentWaterQualityLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, file_get_contents($request->doc_attach_1));
          $documentWaterQualityLogObj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_wq_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentWaterQualityLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, file_get_contents($request->doc_attach_2));
          $documentWaterQualityLogObj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_wq_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentWaterQualityLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, file_get_contents($request->doc_attach_3));
          $documentWaterQualityLogObj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_wq_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentWaterQualityLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, file_get_contents($request->doc_attach_4));
          $documentWaterQualityLogObj->doc_attach_4 = $documentFileObj->file_id;
        }

        $documentWaterQualityLogObj->save();


        // -- Send Mail
        $dataMonth = new Carbon($request->data_month);
        $waterQualityYear = $dataMonth->year;
        $waterQualityMonth = $dataMonth->translatedFormat('F');
        $mailDetails = ['mail_type' => "confirm_submit_waterquality",'email' => $userTokenObj->username, 'document_no' => $request->doc_no, 'water_quality_year' => $waterQualityYear, 'water_quality_month' => $waterQualityMonth];
        Mail::to($userTokenObj->username)->send(new \App\Mail\MailController($mailDetails));


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function changeStatusDocumentWaterQualityByAdmin(Request $request)
  {
    $rules = [
      'log_id'  => 'required',
      'status_id'  => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $documentWaterQualityLogObj = DocumentWaterQualityLog::where("log_id", $request->log_id)->first();
        $documentWaterQualityLogObj->status = $request->status_id;

        switch ($request->status_id) {
          case '2':
            $documentWaterQualityLogObj->approve_date = Carbon::now();
            $documentWaterQualityLogObj->approve_by = $userTokenObj->user_id;

            $mailType = "waterquality_submit_approved";
            break;
          case '3':
            $documentWaterQualityLogObj->reject_date = Carbon::now();
            $documentWaterQualityLogObj->reject_by = $userTokenObj->user_id;

            $mailType = "waterquality_submit_reject";
            break;
        }

        $documentWaterQualityLogObj->save();


        // -- Send Mail
        $dataMonth = new Carbon($documentWaterQualityLogObj->data_month);
        $waterQualityYear = $dataMonth->year;
        $waterQualityMonth = $dataMonth->translatedFormat('F');

        $lovDocumentWaterQualityStatus = LovDocumentWaterQualityStatus::where("quality_status_id", $documentWaterQualityLogObj->status)->first();
        $userRequest = User::where("user_id", $documentWaterQualityLogObj->created_by)->first();
        $mailDetails = ['mail_type' => $mailType,'email' => $userRequest->username, 'document_no' => $documentWaterQualityLogObj->doc_no, 'water_quality_year' => $waterQualityYear, 'water_quality_month' => $waterQualityMonth, 'quality_status' => $documentWaterQualityLogObj->status, 'quality_status_desc' => $lovDocumentWaterQualityStatus->quality_status_name];
        Mail::to($userRequest->username)->send(new \App\Mail\MailController($mailDetails));


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = "แก้ไขสถานะรายการตรวจคุณภาพน้ำเรียบร้อยแล้ว";
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function getDocumentWaterQualityInProcessByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentWaterQualityLog = DocumentWaterQualityLog::join("lov_document_water_quality_status", "lov_document_water_quality_status.quality_status_id", "document_water_quality_logs.status")
                                                ->join("user_profile", "user_profile.user_id", "document_water_quality_logs.created_by")
                                                ->whereIn("status", [1])
                                                ->get();

      if(count($documentWaterQualityLog) > 0){
        $this->constants->response_querylist["results"] = $documentWaterQualityLog;
        $this->constants->response_querylist["results_count"] = count($documentWaterQualityLog);
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

  public function getDocumentWaterQualityHistoryByAdmin(Request $request)
  {
    $rules = [
      'start_date' => 'required',
      'end_date' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_querylist["code"] = 0;
      $this->constants->response_querylist['user_message'] = $validator->messages();
      return response()->json($this->constants->response_querylist, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        if(!is_null($request->doc_no)){
          $documentWaterQualityLogList = DocumentWaterQualityLog::join("lov_document_water_quality_status", "lov_document_water_quality_status.quality_status_id", "document_water_quality_logs.status")
                                                    ->join("user_profile", "user_profile.user_id", "document_water_quality_logs.created_by")
                                                    ->where("created_date", ">=", ($request->start_date." 00:00:00"))
                                                    ->where("created_date", "<=", ($request->end_date." 23:59:59"))
                                                    ->where("doc_no", 'like', '%'.$request->doc_no.'%')
                                                    ->get();
        } else {
          $documentWaterQualityLogList = DocumentWaterQualityLog::join("lov_document_water_quality_status", "lov_document_water_quality_status.quality_status_id", "document_water_quality_logs.status")
                                                    ->join("user_profile", "user_profile.user_id", "document_water_quality_logs.created_by")
                                                    ->where("created_date", ">=", ($request->start_date." 00:00:00"))
                                                    ->where("created_date", "<=", ($request->end_date." 23:59:59"))
                                                    ->get();
        }


        if(count($documentWaterQualityLogList) > 0){
          $this->constants->response_querylist["results"] = $documentWaterQualityLogList;
          $this->constants->response_querylist["results_count"] = count($documentWaterQualityLogList);
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
  }

  public function createDocumentYV1(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      // 'treatment_process_name'  => 'required',
      'wastewater_treatment_type'  => 'required',
      'wastewater_treatment_name_id'  => 'required',
      'treatment_capacity_per_day'  => 'required',
      // 'treatment_control_by'  => 'required',
      // 'treatment_work_per_day'  => 'required',
      // 'water_use_per_month'  => 'required',
      'water_treatment_per_month'  => 'required',
      'bod_treatment_per_month'  => 'required',
      'water_treatment_to'  => 'required',
      'wastewater_source_building_type'  => 'required',
      'wastewater_source_building_size'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      'doc_attach_10'  => 'required_with:cb_doc_attach_10',
      'doc_attach_11'  => 'required_with:cb_doc_attach_11'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "YV1")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "YV1";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentYV1Obj = new DocumentYV1();
        $documentYV1Obj->doc_no = $documentLogObj->doc_no;

        $documentYV1Obj->address_owner = $request->address_owner;
        $documentYV1Obj->address_name = $request->address_name;
        $documentYV1Obj->address_code = $request->address_code;
        $documentYV1Obj->address = $request->address;
        $documentYV1Obj->moo = $request->moo;
        $documentYV1Obj->soi = $request->soi;
        $documentYV1Obj->road = $request->road;
        $documentYV1Obj->province_code = $request->province_code;
        $documentYV1Obj->district_code = $request->district_code;
        $documentYV1Obj->sub_district_code = $request->sub_district_code;
        $documentYV1Obj->zip_code = $request->zip_code;
        $documentYV1Obj->telephone = $request->telephone;
        $documentYV1Obj->mobile_phone = $request->mobile_phone;
        $documentYV1Obj->fax = $request->fax;
        $documentYV1Obj->email = $request->email;
        $documentYV1Obj->latitude = $request->latitude;
        $documentYV1Obj->longitude = $request->longitude;
        $documentYV1Obj->business_type = $request->business_type;

        $documentYV1Obj->treatment_process_name = $request->treatment_process_name;
        $documentYV1Obj->wastewater_treatment_type = $request->wastewater_treatment_type;
        $documentYV1Obj->wastewater_treatment_name_id = $request->wastewater_treatment_name_id;
        $documentYV1Obj->treatment_capacity_per_day = $request->treatment_capacity_per_day;
        $documentYV1Obj->treatment_control_by = $request->treatment_control_by;
        $documentYV1Obj->treatment_control_remark = $request->treatment_control_remark;
        $documentYV1Obj->treatment_work_per_day = $request->treatment_work_per_day;
        $documentYV1Obj->treatment_work_remark = $request->treatment_work_remark;
        $documentYV1Obj->water_use_per_month = $request->water_use_per_month;
        $documentYV1Obj->water_treatment_per_month = $request->water_treatment_per_month;
        $documentYV1Obj->bod_treatment_per_month = $request->bod_treatment_per_month;
        $documentYV1Obj->water_treatment_to = $request->water_treatment_to;
        $documentYV1Obj->water_treatment_to_remark = $request->water_treatment_to_remark;
        $documentYV1Obj->wastewater_source_building_type = $request->wastewater_source_building_type;
        $documentYV1Obj->wastewater_source_building_size = $request->wastewater_source_building_size;
        $documentYV1Obj->wastewater_source_remark = $request->wastewater_source_remark;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_10 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_11')){
          $fileName11 = "doc_attach_11_".time().".".$request->doc_attach_11->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName11, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName11, $this->addWatermark2File($request->doc_attach_11, $request->doc_attach_11->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_11 = $documentFileObj->file_id;
        }

        $documentYV1Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentYV1(Request $request)
  {
    $rules = [
      'doc_yv1_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      // 'treatment_process_name'  => 'required',
      'wastewater_treatment_type'  => 'required',
      'wastewater_treatment_name_id'  => 'required',
      'treatment_capacity_per_day'  => 'required',
      // 'treatment_control_by'  => 'required',
      // 'treatment_work_per_day'  => 'required',
      // 'water_use_per_month'  => 'required',
      'water_treatment_per_month'  => 'required',
      'bod_treatment_per_month'  => 'required',
      'water_treatment_to'  => 'required',
      'wastewater_source_building_type'  => 'required',
      'wastewater_source_building_size'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      // 'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      // 'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      // 'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      // 'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      // 'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      // 'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      // 'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      // 'doc_attach_10'  => 'required_with:cb_doc_attach_10',
      // 'doc_attach_11'  => 'required_with:cb_doc_attach_11'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentYV1Obj = DocumentYV1::where("doc_yv1_id", $request->doc_yv1_id)->first();
        $documentYV1Obj->address_owner = $request->address_owner;
        $documentYV1Obj->address_name = $request->address_name;
        $documentYV1Obj->address_code = $request->address_code;
        $documentYV1Obj->address = $request->address;
        $documentYV1Obj->moo = $request->moo;
        $documentYV1Obj->soi = $request->soi;
        $documentYV1Obj->road = $request->road;
        $documentYV1Obj->province_code = $request->province_code;
        $documentYV1Obj->district_code = $request->district_code;
        $documentYV1Obj->sub_district_code = $request->sub_district_code;
        $documentYV1Obj->zip_code = $request->zip_code;
        $documentYV1Obj->telephone = $request->telephone;
        $documentYV1Obj->mobile_phone = $request->mobile_phone;
        $documentYV1Obj->fax = $request->fax;
        $documentYV1Obj->email = $request->email;
        $documentYV1Obj->latitude = $request->latitude;
        $documentYV1Obj->longitude = $request->longitude;
        $documentYV1Obj->business_type = $request->business_type;

        $documentYV1Obj->treatment_process_name = $request->treatment_process_name;
        $documentYV1Obj->wastewater_treatment_type = $request->wastewater_treatment_type;
        $documentYV1Obj->wastewater_treatment_name_id = $request->wastewater_treatment_name_id;
        $documentYV1Obj->treatment_capacity_per_day = $request->treatment_capacity_per_day;
        $documentYV1Obj->treatment_control_by = $request->treatment_control_by;
        $documentYV1Obj->treatment_control_remark = $request->treatment_control_remark;
        $documentYV1Obj->treatment_work_per_day = $request->treatment_work_per_day;
        $documentYV1Obj->treatment_work_remark = $request->treatment_work_remark;
        $documentYV1Obj->water_use_per_month = $request->water_use_per_month;
        $documentYV1Obj->water_treatment_per_month = $request->water_treatment_per_month;
        $documentYV1Obj->bod_treatment_per_month = $request->bod_treatment_per_month;
        $documentYV1Obj->water_treatment_to = $request->water_treatment_to;
        $documentYV1Obj->water_treatment_to_remark = $request->water_treatment_to_remark;
        $documentYV1Obj->wastewater_source_building_type = $request->wastewater_source_building_type;
        $documentYV1Obj->wastewater_source_building_size = $request->wastewater_source_building_size;
        $documentYV1Obj->wastewater_source_remark = $request->wastewater_source_remark;

        $documentLogObj = DocumentLog::where("doc_no", $documentYV1Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_1)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_2)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_3)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_4)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_5)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_6)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_7)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_8)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_8 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_8); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_8 = null;
          }

          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_9)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_9 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_9); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_9 = null;
          }

          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_10)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_10 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_10); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_10 = null;
          }

          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_10 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_11')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV1Obj->doc_attach_11)->first(); // Delete file log
          if($documentYV1Obj->doc_attach_11 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV1Obj->doc_attach_11); // Delete file
            $documentFileObj->delete();
            $documentYV1Obj->doc_attach_11 = null;
          }

          $fileName11 = "doc_attach_11_".time().".".$request->doc_attach_11->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName11, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName11, $this->addWatermark2File($request->doc_attach_11, $request->doc_attach_11->getClientOriginalExtension()));
          $documentYV1Obj->doc_attach_11 = $documentFileObj->file_id;
        }


        $documentYV1Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentYV2(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'cancel_reason'  => 'required',
      'cancel_date'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      'doc_attach_10'  => 'required_with:cb_doc_attach_10'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "YV2")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "YV2";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentYV2Obj = new DocumentYV2();
        $documentYV2Obj->doc_no = $documentLogObj->doc_no;

        $documentYV2Obj->address_owner = $request->address_owner;
        $documentYV2Obj->address_name = $request->address_name;
        $documentYV2Obj->address_code = $request->address_code;
        $documentYV2Obj->address = $request->address;
        $documentYV2Obj->moo = $request->moo;
        $documentYV2Obj->soi = $request->soi;
        $documentYV2Obj->road = $request->road;
        $documentYV2Obj->province_code = $request->province_code;
        $documentYV2Obj->district_code = $request->district_code;
        $documentYV2Obj->sub_district_code = $request->sub_district_code;
        $documentYV2Obj->zip_code = $request->zip_code;
        $documentYV2Obj->telephone = $request->telephone;
        $documentYV2Obj->mobile_phone = $request->mobile_phone;
        $documentYV2Obj->fax = $request->fax;
        $documentYV2Obj->email = $request->email;
        $documentYV2Obj->latitude = $request->latitude;
        $documentYV2Obj->longitude = $request->longitude;
        $documentYV2Obj->business_type = $request->business_type;

        $documentYV2Obj->cancel_reason = $request->cancel_reason;
        $documentYV2Obj->cancel_date = $request->cancel_date;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_10 = $documentFileObj->file_id;
        }

        $documentYV2Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentYV2(Request $request)
  {
    $rules = [
      'doc_yv2_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'cancel_reason'  => 'required',
      'cancel_date'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      // 'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      // 'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      // 'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      // 'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      // 'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      // 'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      // 'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      // 'doc_attach_10'  => 'required_with:cb_doc_attach_10'
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentYV2Obj = DocumentYV2::where("doc_yv2_id", $request->doc_yv2_id)->first();
        $documentYV2Obj->address_owner = $request->address_owner;
        $documentYV2Obj->address_name = $request->address_name;
        $documentYV2Obj->address_code = $request->address_code;
        $documentYV2Obj->address = $request->address;
        $documentYV2Obj->moo = $request->moo;
        $documentYV2Obj->soi = $request->soi;
        $documentYV2Obj->road = $request->road;
        $documentYV2Obj->province_code = $request->province_code;
        $documentYV2Obj->district_code = $request->district_code;
        $documentYV2Obj->sub_district_code = $request->sub_district_code;
        $documentYV2Obj->zip_code = $request->zip_code;
        $documentYV2Obj->telephone = $request->telephone;
        $documentYV2Obj->mobile_phone = $request->mobile_phone;
        $documentYV2Obj->fax = $request->fax;
        $documentYV2Obj->email = $request->email;
        $documentYV2Obj->latitude = $request->latitude;
        $documentYV2Obj->longitude = $request->longitude;
        $documentYV2Obj->business_type = $request->business_type;

        $documentYV2Obj->cancel_reason = $request->cancel_reason;
        $documentYV2Obj->cancel_date = $request->cancel_date;

        $documentLogObj = DocumentLog::where("doc_no", $documentYV2Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_1)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_2)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_3)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_4)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_5)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_6)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_7)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $documentFileObj = DocumentFile::where("file_id", $documentYV2Obj->doc_attach_8)->first(); // Delete file log
          if($documentYV2Obj->doc_attach_8 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentYV2Obj->doc_attach_8); // Delete file
            $documentFileObj->delete();
            $documentYV2Obj->doc_attach_8 = null;
          }

          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentYV2Obj->doc_attach_8 = $documentFileObj->file_id;
        }

        $documentYV2Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentRB1(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      // 'land_owner_name'  => 'required',
      // 'land_number'  => 'required',
      // 'land_description'  => 'required',
      // 'land_building_type'  => 'required',
      // 'connect_pipe'  => 'required',
      // 'address_connect_pipe'  => 'required',
      'pool_engineer_name'  => 'required',
      'pool_approve_date'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      'doc_attach_10'  => 'required_with:cb_doc_attach_10',
      'doc_attach_11'  => 'required_with:cb_doc_attach_11',
      'doc_attach_12'  => 'required_with:cb_doc_attach_12',
      'doc_attach_13'  => 'required_with:cb_doc_attach_13',
      'doc_attach_14'  => 'required_with:cb_doc_attach_14',
      'doc_attach_15'  => 'required_with:cb_doc_attach_15',
      'doc_attach_16'  => 'required_with:cb_doc_attach_16',
      'doc_attach_17'  => 'required_with:cb_doc_attach_17',
    ];


    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "RB1")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "RB1";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentRB1Obj = new DocumentRB1();
        $documentRB1Obj->doc_no = $documentLogObj->doc_no;

        $documentRB1Obj->address_owner = $request->address_owner;
        $documentRB1Obj->address_name = $request->address_name;
        $documentRB1Obj->address_code = $request->address_code;
        $documentRB1Obj->address = $request->address;
        $documentRB1Obj->moo = $request->moo;
        $documentRB1Obj->soi = $request->soi;
        $documentRB1Obj->road = $request->road;
        $documentRB1Obj->province_code = $request->province_code;
        $documentRB1Obj->district_code = $request->district_code;
        $documentRB1Obj->sub_district_code = $request->sub_district_code;
        $documentRB1Obj->zip_code = $request->zip_code;
        $documentRB1Obj->telephone = $request->telephone;
        $documentRB1Obj->mobile_phone = $request->mobile_phone;
        $documentRB1Obj->fax = $request->fax;
        $documentRB1Obj->email = $request->email;
        $documentRB1Obj->latitude = $request->latitude;
        $documentRB1Obj->longitude = $request->longitude;
        $documentRB1Obj->business_type = $request->business_type;

        $documentRB1Obj->land_owner_name = $request->land_owner_name;
        $documentRB1Obj->land_number = $request->land_number;
        $documentRB1Obj->land_description = $request->land_description;
        $documentRB1Obj->land_building_type = $request->land_building_type;
        $documentRB1Obj->connect_pipe = $request->connect_pipe;
        $documentRB1Obj->address_connect_pipe = $request->address_connect_pipe;
        $documentRB1Obj->pool_engineer_name = $request->pool_engineer_name;
        $documentRB1Obj->pool_approve_date = $request->pool_approve_date;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_10 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_11')){
          $fileName11 = "doc_attach_11_".time().".".$request->doc_attach_11->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName11, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName11, $this->addWatermark2File($request->doc_attach_11, $request->doc_attach_11->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_11 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_12')){
          $fileName12 = "doc_attach_12_".time().".".$request->doc_attach_12->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName12, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName12, $this->addWatermark2File($request->doc_attach_12, $request->doc_attach_12->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_12 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_13')){
          $fileName13 = "doc_attach_13_".time().".".$request->doc_attach_13->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName13, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName13, $this->addWatermark2File($request->doc_attach_13, $request->doc_attach_13->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_13 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_14')){
          $fileName14 = "doc_attach_14_".time().".".$request->doc_attach_14->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName14, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName14, $this->addWatermark2File($request->doc_attach_14, $request->doc_attach_14->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_14 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_15')){
          $fileName15 = "doc_attach_15_".time().".".$request->doc_attach_15->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName15, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName15, $this->addWatermark2File($request->doc_attach_15, $request->doc_attach_15->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_15 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_16')){
          $fileName16 = "doc_attach_16_".time().".".$request->doc_attach_16->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName16, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName16, $this->addWatermark2File($request->doc_attach_16, $request->doc_attach_16->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_16 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_17')){
          $fileName17 = "doc_attach_17_".time().".".$request->doc_attach_17->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName17, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName17, $this->addWatermark2File($request->doc_attach_17, $request->doc_attach_17->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_17 = $documentFileObj->file_id;
        }

        $documentRB1Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentRB1(Request $request)
  {
    $rules = [
      'doc_rb1_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      // 'land_owner_name'  => 'required',
      // 'land_number'  => 'required',
      // 'land_description'  => 'required',
      // 'land_building_type'  => 'required',
      // 'connect_pipe'  => 'required',
      // 'address_connect_pipe'  => 'required',
      'pool_engineer_name'  => 'required',
      'pool_approve_date'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      // 'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      // 'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      // 'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      // 'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      // 'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      // 'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      // 'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      // 'doc_attach_10'  => 'required_with:cb_doc_attach_10',
      // 'doc_attach_11'  => 'required_with:cb_doc_attach_11',
      // 'doc_attach_12'  => 'required_with:cb_doc_attach_12',
      // 'doc_attach_13'  => 'required_with:cb_doc_attach_13',
      // 'doc_attach_14'  => 'required_with:cb_doc_attach_14',
      // 'doc_attach_15'  => 'required_with:cb_doc_attach_15',
      // 'doc_attach_16'  => 'required_with:cb_doc_attach_16',
      // 'doc_attach_17'  => 'required_with:cb_doc_attach_17',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentRB1Obj = DocumentRB1::where("doc_rb1_id", $request->doc_rb1_id)->first();
        $documentRB1Obj->address_owner = $request->address_owner;
        $documentRB1Obj->address_name = $request->address_name;
        $documentRB1Obj->address_code = $request->address_code;
        $documentRB1Obj->address = $request->address;
        $documentRB1Obj->moo = $request->moo;
        $documentRB1Obj->soi = $request->soi;
        $documentRB1Obj->road = $request->road;
        $documentRB1Obj->province_code = $request->province_code;
        $documentRB1Obj->district_code = $request->district_code;
        $documentRB1Obj->sub_district_code = $request->sub_district_code;
        $documentRB1Obj->zip_code = $request->zip_code;
        $documentRB1Obj->telephone = $request->telephone;
        $documentRB1Obj->mobile_phone = $request->mobile_phone;
        $documentRB1Obj->fax = $request->fax;
        $documentRB1Obj->email = $request->email;
        $documentRB1Obj->latitude = $request->latitude;
        $documentRB1Obj->longitude = $request->longitude;
        $documentRB1Obj->business_type = $request->business_type;

        $documentRB1Obj->land_owner_name = $request->land_owner_name;
        $documentRB1Obj->land_number = $request->land_number;
        $documentRB1Obj->land_description = $request->land_description;
        $documentRB1Obj->land_building_type = $request->land_building_type;
        $documentRB1Obj->connect_pipe = $request->connect_pipe;
        $documentRB1Obj->address_connect_pipe = $request->address_connect_pipe;
        $documentRB1Obj->pool_engineer_name = $request->pool_engineer_name;
        $documentRB1Obj->pool_approve_date = $request->pool_approve_date;

        $documentLogObj = DocumentLog::where("doc_no", $documentRB1Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_1)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_2)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_3)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_4)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_5)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_6)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_7)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_8)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_8 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_8); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_8 = null;
          }

          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_9)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_9 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_9); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_9 = null;
          }

          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_10)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_10 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_10); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_10 = null;
          }

          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_10 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_11')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_11)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_11 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_11); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_11 = null;
          }

          $fileName11 = "doc_attach_11_".time().".".$request->doc_attach_11->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName11, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName11, $this->addWatermark2File($request->doc_attach_11, $request->doc_attach_11->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_11 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_12')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_12)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_12 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_12); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_12 = null;
          }

          $fileName12 = "doc_attach_12_".time().".".$request->doc_attach_12->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName12, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName12, $this->addWatermark2File($request->doc_attach_12, $request->doc_attach_12->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_12 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_13')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_13)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_13 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_13); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_13 = null;
          }

          $fileName13 = "doc_attach_13_".time().".".$request->doc_attach_13->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName13, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName13, $this->addWatermark2File($request->doc_attach_13, $request->doc_attach_13->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_13 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_14')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_14)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_14 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_14); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_14 = null;
          }

          $fileName14 = "doc_attach_14_".time().".".$request->doc_attach_14->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName14, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName14, $this->addWatermark2File($request->doc_attach_14, $request->doc_attach_14->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_14 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_15')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_15)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_15 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_15); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_15 = null;
          }

          $fileName15 = "doc_attach_15_".time().".".$request->doc_attach_15->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName15, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName15, $this->addWatermark2File($request->doc_attach_15, $request->doc_attach_15->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_15 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_16')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_16)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_16 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_16); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_16 = null;
          }

          $fileName16 = "doc_attach_16_".time().".".$request->doc_attach_16->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName16, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName16, $this->addWatermark2File($request->doc_attach_16, $request->doc_attach_16->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_16 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_17')){
          $documentFileObj = DocumentFile::where("file_id", $documentRB1Obj->doc_attach_17)->first(); // Delete file log
          if($documentRB1Obj->doc_attach_17 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentRB1Obj->doc_attach_17); // Delete file
            $documentFileObj->delete();
            $documentRB1Obj->doc_attach_17 = null;
          }

          $fileName17 = "doc_attach_17_".time().".".$request->doc_attach_17->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName17, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName17, $this->addWatermark2File($request->doc_attach_17, $request->doc_attach_17->getClientOriginalExtension()));
          $documentRB1Obj->doc_attach_17 = $documentFileObj->file_id;
        }

        $documentRB1Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentPG1(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'badan_install_sensor'  => 'required',
      'badan_max_capacity_per_month'  => 'required',
      // 'non_badan_building_type'  => 'required',
      // 'non_badan_calculate_people'  => 'required',
      // 'non_badan_calculate_panel'  => 'required',
      // 'non_badan_calculate_room'  => 'required',
      // 'non_badan_calculate_m2'  => 'required',
      // 'non_badan_calculate_bed'  => 'required',
      // 'non_badan_formular'  => 'required',
      // 'non_badan_water_avg_per_month'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      'doc_attach_10'  => 'required_with:cb_doc_attach_10',
    ];


    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "PG1")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "PG1";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentPG1Obj = new DocumentPG1();
        $documentPG1Obj->doc_no = $documentLogObj->doc_no;

        $documentPG1Obj->address_owner = $request->address_owner;
        $documentPG1Obj->address_name = $request->address_name;
        $documentPG1Obj->address_code = $request->address_code;
        $documentPG1Obj->address = $request->address;
        $documentPG1Obj->moo = $request->moo;
        $documentPG1Obj->soi = $request->soi;
        $documentPG1Obj->road = $request->road;
        $documentPG1Obj->province_code = $request->province_code;
        $documentPG1Obj->district_code = $request->district_code;
        $documentPG1Obj->sub_district_code = $request->sub_district_code;
        $documentPG1Obj->zip_code = $request->zip_code;
        $documentPG1Obj->telephone = $request->telephone;
        $documentPG1Obj->mobile_phone = $request->mobile_phone;
        $documentPG1Obj->fax = $request->fax;
        $documentPG1Obj->email = $request->email;
        $documentPG1Obj->latitude = $request->latitude;
        $documentPG1Obj->longitude = $request->longitude;
        $documentPG1Obj->business_type = $request->business_type;

        $documentPG1Obj->badan_install_sensor = $request->badan_install_sensor;
        $documentPG1Obj->badan_max_capacity_per_month = $request->badan_max_capacity_per_month;
        $documentPG1Obj->non_badan_building_type = $request->non_badan_building_type;
        $documentPG1Obj->non_badan_calculate_people = $request->non_badan_calculate_people;
        $documentPG1Obj->non_badan_calculate_panel = $request->non_badan_calculate_panel;
        $documentPG1Obj->non_badan_calculate_room = $request->non_badan_calculate_room;
        $documentPG1Obj->non_badan_calculate_m2 = $request->non_badan_calculate_m2;
        $documentPG1Obj->non_badan_calculate_bed = $request->non_badan_calculate_bed;
        $documentPG1Obj->non_badan_formular = $request->non_badan_formular;
        $documentPG1Obj->non_badan_water_avg_per_month = $request->non_badan_water_avg_per_month;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_10 = $documentFileObj->file_id;
        }

        $documentPG1Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentPG1(Request $request)
  {
    $rules = [
      'doc_pg1_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'badan_install_sensor'  => 'required',
      'badan_max_capacity_per_month'  => 'required',
      // 'non_badan_building_type'  => 'required',
      // 'non_badan_calculate_people'  => 'required',
      // 'non_badan_calculate_panel'  => 'required',
      // 'non_badan_calculate_room'  => 'required',
      // 'non_badan_calculate_m2'  => 'required',
      // 'non_badan_calculate_bed'  => 'required',
      // 'non_badan_formular'  => 'required',
      // 'non_badan_water_avg_per_month'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      // 'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      // 'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      // 'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      // 'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      // 'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      // 'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      // 'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      // 'doc_attach_10'  => 'required_with:cb_doc_attach_10',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentPG1Obj = DocumentPG1::where("doc_pg1_id", $request->doc_pg1_id)->first();
        $documentPG1Obj->address_owner = $request->address_owner;
        $documentPG1Obj->address_name = $request->address_name;
        $documentPG1Obj->address_code = $request->address_code;
        $documentPG1Obj->address = $request->address;
        $documentPG1Obj->moo = $request->moo;
        $documentPG1Obj->soi = $request->soi;
        $documentPG1Obj->road = $request->road;
        $documentPG1Obj->province_code = $request->province_code;
        $documentPG1Obj->district_code = $request->district_code;
        $documentPG1Obj->sub_district_code = $request->sub_district_code;
        $documentPG1Obj->zip_code = $request->zip_code;
        $documentPG1Obj->telephone = $request->telephone;
        $documentPG1Obj->mobile_phone = $request->mobile_phone;
        $documentPG1Obj->fax = $request->fax;
        $documentPG1Obj->email = $request->email;
        $documentPG1Obj->latitude = $request->latitude;
        $documentPG1Obj->longitude = $request->longitude;
        $documentPG1Obj->business_type = $request->business_type;

        $documentPG1Obj->badan_install_sensor = $request->badan_install_sensor;
        $documentPG1Obj->badan_max_capacity_per_month = $request->badan_max_capacity_per_month;
        $documentPG1Obj->non_badan_building_type = $request->non_badan_building_type;
        $documentPG1Obj->non_badan_calculate_people = $request->non_badan_calculate_people;
        $documentPG1Obj->non_badan_calculate_panel = $request->non_badan_calculate_panel;
        $documentPG1Obj->non_badan_calculate_room = $request->non_badan_calculate_room;
        $documentPG1Obj->non_badan_calculate_m2 = $request->non_badan_calculate_m2;
        $documentPG1Obj->non_badan_calculate_bed = $request->non_badan_calculate_bed;
        $documentPG1Obj->non_badan_formular = $request->non_badan_formular;
        $documentPG1Obj->non_badan_water_avg_per_month = $request->non_badan_water_avg_per_month;

        $documentLogObj = DocumentLog::where("doc_no", $documentPG1Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_1)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_2)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_3)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_4)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_5)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_6)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_7)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_8)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_8 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_8); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_8 = null;
          }

          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_9)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_9 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_9); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_9 = null;
          }

          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_10)->first(); // Delete file log
          if($documentPG1Obj->doc_attach_10 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG1Obj->doc_attach_10); // Delete file
            $documentFileObj->delete();
            $documentPG1Obj->doc_attach_10 = null;
          }

          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentPG1Obj->doc_attach_10 = $documentFileObj->file_id;
        }

        $documentPG1Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentPG2(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
    ];


    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "PG2")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "PG2";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentPG2Obj = new DocumentPG2();
        $documentPG2Obj->doc_no = $documentLogObj->doc_no;

        $documentPG2Obj->address_owner = $request->address_owner;
        $documentPG2Obj->address_name = $request->address_name;
        $documentPG2Obj->address_code = $request->address_code;
        $documentPG2Obj->address = $request->address;
        $documentPG2Obj->moo = $request->moo;
        $documentPG2Obj->soi = $request->soi;
        $documentPG2Obj->road = $request->road;
        $documentPG2Obj->province_code = $request->province_code;
        $documentPG2Obj->district_code = $request->district_code;
        $documentPG2Obj->sub_district_code = $request->sub_district_code;
        $documentPG2Obj->zip_code = $request->zip_code;
        $documentPG2Obj->telephone = $request->telephone;
        $documentPG2Obj->mobile_phone = $request->mobile_phone;
        $documentPG2Obj->fax = $request->fax;
        $documentPG2Obj->email = $request->email;
        $documentPG2Obj->latitude = $request->latitude;
        $documentPG2Obj->longitude = $request->longitude;
        $documentPG2Obj->business_type = $request->business_type;

        $documentPG2Obj->badan_test_date = $request->badan_test_date;
        $documentPG2Obj->badan_total_pool = $request->badan_total_pool;
        $documentPG2Obj->badan_test_checkpoint_1_before = $request->badan_test_checkpoint_1_before;
        $documentPG2Obj->badan_test_checkpoint_1_after = $request->badan_test_checkpoint_1_after;
        $documentPG2Obj->badan_test_checkpoint_2_before = $request->badan_test_checkpoint_2_before;
        $documentPG2Obj->badan_test_checkpoint_2_after = $request->badan_test_checkpoint_2_after;
        $documentPG2Obj->badan_test_checkpoint_3_before = $request->badan_test_checkpoint_3_before;
        $documentPG2Obj->badan_test_checkpoint_3_after = $request->badan_test_checkpoint_3_after;
        $documentPG2Obj->badan_test_checkpoint_4_before = $request->badan_test_checkpoint_4_before;
        $documentPG2Obj->badan_test_checkpoint_4_after = $request->badan_test_checkpoint_4_after;
        $documentPG2Obj->badan_test_checkpoint_5_before = $request->badan_test_checkpoint_5_before;
        $documentPG2Obj->badan_test_checkpoint_5_after = $request->badan_test_checkpoint_5_after;
        $documentPG2Obj->badan_test_checkpoint_6_before = $request->badan_test_checkpoint_6_before;
        $documentPG2Obj->badan_test_checkpoint_6_after = $request->badan_test_checkpoint_6_after;
        $documentPG2Obj->badan_water_capacity_per_month = $request->badan_water_capacity_per_month;
        $documentPG2Obj->non_badan_source = $request->non_badan_source;
        $documentPG2Obj->non_badan_test_checkpoint_1_before = $request->non_badan_test_checkpoint_1_before;
        $documentPG2Obj->non_badan_test_checkpoint_1_after = $request->non_badan_test_checkpoint_1_after;
        $documentPG2Obj->non_badan_test_checkpoint_2_before = $request->non_badan_test_checkpoint_2_before;
        $documentPG2Obj->non_badan_test_checkpoint_2_after = $request->non_badan_test_checkpoint_2_after;
        $documentPG2Obj->non_badan_test_checkpoint_3_before = $request->non_badan_test_checkpoint_3_before;
        $documentPG2Obj->non_badan_test_checkpoint_3_after = $request->non_badan_test_checkpoint_3_after;
        $documentPG2Obj->non_badan_test_checkpoint_4_before = $request->non_badan_test_checkpoint_4_before;
        $documentPG2Obj->non_badan_test_checkpoint_4_after = $request->non_badan_test_checkpoint_4_after;
        $documentPG2Obj->non_badan_test_checkpoint_5_before = $request->non_badan_test_checkpoint_5_before;
        $documentPG2Obj->non_badan_test_checkpoint_5_after = $request->non_badan_test_checkpoint_5_after;
        $documentPG2Obj->non_badan_test_checkpoint_6_before = $request->non_badan_test_checkpoint_6_before;
        $documentPG2Obj->non_badan_test_checkpoint_6_after = $request->non_badan_test_checkpoint_6_after;
        $documentPG2Obj->non_badan_water_capacity_per_month = $request->non_badan_water_capacity_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_1_per_month = $request->wastewater_test_checkpoint_1_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_2_per_month = $request->wastewater_test_checkpoint_2_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_3_per_month = $request->wastewater_test_checkpoint_3_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_4_per_month = $request->wastewater_test_checkpoint_4_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_5_per_month = $request->wastewater_test_checkpoint_5_per_month;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_7 = $documentFileObj->file_id;
        }

        $documentPG2Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentPG2(Request $request)
  {
    $rules = [
      'doc_pg2_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentPG2Obj = DocumentPG2::where("doc_pg2_id", $request->doc_pg2_id)->first();
        $documentPG2Obj->address_owner = $request->address_owner;
        $documentPG2Obj->address_name = $request->address_name;
        $documentPG2Obj->address_code = $request->address_code;
        $documentPG2Obj->address = $request->address;
        $documentPG2Obj->moo = $request->moo;
        $documentPG2Obj->soi = $request->soi;
        $documentPG2Obj->road = $request->road;
        $documentPG2Obj->province_code = $request->province_code;
        $documentPG2Obj->district_code = $request->district_code;
        $documentPG2Obj->sub_district_code = $request->sub_district_code;
        $documentPG2Obj->zip_code = $request->zip_code;
        $documentPG2Obj->telephone = $request->telephone;
        $documentPG2Obj->mobile_phone = $request->mobile_phone;
        $documentPG2Obj->fax = $request->fax;
        $documentPG2Obj->email = $request->email;
        $documentPG2Obj->latitude = $request->latitude;
        $documentPG2Obj->longitude = $request->longitude;
        $documentPG2Obj->business_type = $request->business_type;

        $documentPG2Obj->badan_test_date = $request->badan_test_date;
        $documentPG2Obj->badan_total_pool = $request->badan_total_pool;
        $documentPG2Obj->badan_test_checkpoint_1_before = $request->badan_test_checkpoint_1_before;
        $documentPG2Obj->badan_test_checkpoint_1_after = $request->badan_test_checkpoint_1_after;
        $documentPG2Obj->badan_test_checkpoint_2_before = $request->badan_test_checkpoint_2_before;
        $documentPG2Obj->badan_test_checkpoint_2_after = $request->badan_test_checkpoint_2_after;
        $documentPG2Obj->badan_test_checkpoint_3_before = $request->badan_test_checkpoint_3_before;
        $documentPG2Obj->badan_test_checkpoint_3_after = $request->badan_test_checkpoint_3_after;
        $documentPG2Obj->badan_test_checkpoint_4_before = $request->badan_test_checkpoint_4_before;
        $documentPG2Obj->badan_test_checkpoint_4_after = $request->badan_test_checkpoint_4_after;
        $documentPG2Obj->badan_test_checkpoint_5_before = $request->badan_test_checkpoint_5_before;
        $documentPG2Obj->badan_test_checkpoint_5_after = $request->badan_test_checkpoint_5_after;
        $documentPG2Obj->badan_test_checkpoint_6_before = $request->badan_test_checkpoint_6_before;
        $documentPG2Obj->badan_test_checkpoint_6_after = $request->badan_test_checkpoint_6_after;
        $documentPG2Obj->badan_water_capacity_per_month = $request->badan_water_capacity_per_month;
        $documentPG2Obj->non_badan_source = $request->non_badan_source;
        $documentPG2Obj->non_badan_test_checkpoint_1_before = $request->non_badan_test_checkpoint_1_before;
        $documentPG2Obj->non_badan_test_checkpoint_1_after = $request->non_badan_test_checkpoint_1_after;
        $documentPG2Obj->non_badan_test_checkpoint_2_before = $request->non_badan_test_checkpoint_2_before;
        $documentPG2Obj->non_badan_test_checkpoint_2_after = $request->non_badan_test_checkpoint_2_after;
        $documentPG2Obj->non_badan_test_checkpoint_3_before = $request->non_badan_test_checkpoint_3_before;
        $documentPG2Obj->non_badan_test_checkpoint_3_after = $request->non_badan_test_checkpoint_3_after;
        $documentPG2Obj->non_badan_test_checkpoint_4_before = $request->non_badan_test_checkpoint_4_before;
        $documentPG2Obj->non_badan_test_checkpoint_4_after = $request->non_badan_test_checkpoint_4_after;
        $documentPG2Obj->non_badan_test_checkpoint_5_before = $request->non_badan_test_checkpoint_5_before;
        $documentPG2Obj->non_badan_test_checkpoint_5_after = $request->non_badan_test_checkpoint_5_after;
        $documentPG2Obj->non_badan_test_checkpoint_6_before = $request->non_badan_test_checkpoint_6_before;
        $documentPG2Obj->non_badan_test_checkpoint_6_after = $request->non_badan_test_checkpoint_6_after;
        $documentPG2Obj->non_badan_water_capacity_per_month = $request->non_badan_water_capacity_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_1_per_month = $request->wastewater_test_checkpoint_1_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_2_per_month = $request->wastewater_test_checkpoint_2_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_3_per_month = $request->wastewater_test_checkpoint_3_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_4_per_month = $request->wastewater_test_checkpoint_4_per_month;
        $documentPG2Obj->wastewater_test_checkpoint_5_per_month = $request->wastewater_test_checkpoint_5_per_month;

        $documentLogObj = DocumentLog::where("doc_no", $documentPG2Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG1Obj->doc_attach_1)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG2Obj->doc_attach_2)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG2Obj->doc_attach_3)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG2Obj->doc_attach_4)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG2Obj->doc_attach_5)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG2Obj->doc_attach_6)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentPG2Obj->doc_attach_7)->first(); // Delete file log
          if($documentPG2Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentPG2Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentPG2Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentPG2Obj->doc_attach_7 = $documentFileObj->file_id;
        }

        $documentPG2Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentNT1(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      'doc_attach_10'  => 'required_with:cb_doc_attach_10',
      'doc_attach_11'  => 'required_with:cb_doc_attach_11',
    ];


    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "NT1")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "NT1";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentNT1Obj = new DocumentNT1();
        $documentNT1Obj->doc_no = $documentLogObj->doc_no;

        $documentNT1Obj->address_owner = $request->address_owner;
        $documentNT1Obj->address_name = $request->address_name;
        $documentNT1Obj->address_code = $request->address_code;
        $documentNT1Obj->address = $request->address;
        $documentNT1Obj->moo = $request->moo;
        $documentNT1Obj->soi = $request->soi;
        $documentNT1Obj->road = $request->road;
        $documentNT1Obj->province_code = $request->province_code;
        $documentNT1Obj->district_code = $request->district_code;
        $documentNT1Obj->sub_district_code = $request->sub_district_code;
        $documentNT1Obj->zip_code = $request->zip_code;
        $documentNT1Obj->telephone = $request->telephone;
        $documentNT1Obj->mobile_phone = $request->mobile_phone;
        $documentNT1Obj->fax = $request->fax;
        $documentNT1Obj->email = $request->email;
        $documentNT1Obj->latitude = $request->latitude;
        $documentNT1Obj->longitude = $request->longitude;
        $documentNT1Obj->business_type = $request->business_type;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_10 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_11')){
          $fileName11 = "doc_attach_11_".time().".".$request->doc_attach_11->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName11, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName11, $this->addWatermark2File($request->doc_attach_11, $request->doc_attach_11->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_11 = $documentFileObj->file_id;
        }

        $documentNT1Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentNT1(Request $request)
  {
    $rules = [
      'doc_nt1_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      // 'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      // 'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      // 'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      // 'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      // 'doc_attach_7'  => 'required_with:cb_doc_attach_7',
      // 'doc_attach_8'  => 'required_with:cb_doc_attach_8',
      // 'doc_attach_9'  => 'required_with:cb_doc_attach_9',
      // 'doc_attach_10'  => 'required_with:cb_doc_attach_10',
      // 'doc_attach_11'  => 'required_with:cb_doc_attach_11',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentNT1Obj = DocumentNT1::where("doc_nt1_id", $request->doc_nt1_id)->first();
        $documentNT1Obj->address_owner = $request->address_owner;
        $documentNT1Obj->address_name = $request->address_name;
        $documentNT1Obj->address_code = $request->address_code;
        $documentNT1Obj->address = $request->address;
        $documentNT1Obj->moo = $request->moo;
        $documentNT1Obj->soi = $request->soi;
        $documentNT1Obj->road = $request->road;
        $documentNT1Obj->province_code = $request->province_code;
        $documentNT1Obj->district_code = $request->district_code;
        $documentNT1Obj->sub_district_code = $request->sub_district_code;
        $documentNT1Obj->zip_code = $request->zip_code;
        $documentNT1Obj->telephone = $request->telephone;
        $documentNT1Obj->mobile_phone = $request->mobile_phone;
        $documentNT1Obj->fax = $request->fax;
        $documentNT1Obj->email = $request->email;
        $documentNT1Obj->latitude = $request->latitude;
        $documentNT1Obj->longitude = $request->longitude;
        $documentNT1Obj->business_type = $request->business_type;

        $documentLogObj = DocumentLog::where("doc_no", $documentNT1Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_1)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_2)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_3)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_4)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_5)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_6)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_7)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_7 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_8')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_8)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_8 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_8); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_8 = null;
          }

          $fileName8 = "doc_attach_8_".time().".".$request->doc_attach_8->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName8, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName8, $this->addWatermark2File($request->doc_attach_8, $request->doc_attach_8->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_8 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_9')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_9)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_9 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_9); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_9 = null;
          }

          $fileName9 = "doc_attach_9_".time().".".$request->doc_attach_9->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName9, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName9, $this->addWatermark2File($request->doc_attach_9, $request->doc_attach_9->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_9 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_10')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_10)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_10 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_10); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_10 = null;
          }

          $fileName10 = "doc_attach_10_".time().".".$request->doc_attach_10->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName10, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName10, $this->addWatermark2File($request->doc_attach_10, $request->doc_attach_10->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_10 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_11')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT1Obj->doc_attach_11)->first(); // Delete file log
          if($documentNT1Obj->doc_attach_11 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT1Obj->doc_attach_11); // Delete file
            $documentFileObj->delete();
            $documentNT1Obj->doc_attach_11 = null;
          }

          $fileName11 = "doc_attach_11_".time().".".$request->doc_attach_11->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName11, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName11, $this->addWatermark2File($request->doc_attach_11, $request->doc_attach_11->getClientOriginalExtension()));
          $documentNT1Obj->doc_attach_11 = $documentFileObj->file_id;
        }

        $documentNT1Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentNT2(Request $request)
  {
    $rules = [
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'wastewater_total_checkpoint'  => 'required',
      'wastewater_total_per_month'  => 'required',
      'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      'doc_attach_2'  => 'required_with:cb_doc_attach_2',
      'doc_attach_3'  => 'required_with:cb_doc_attach_3',
      'doc_attach_4'  => 'required_with:cb_doc_attach_4',
      'doc_attach_5'  => 'required_with:cb_doc_attach_5',
      'doc_attach_6'  => 'required_with:cb_doc_attach_6',
      'doc_attach_7'  => 'required_with:cb_doc_attach_7',
    ];


    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documenySeqGenObj = DocumentSeqGen::where("doc_no", "NT2")->first();
        $documenySeqGenObj->running++;
        $documenySeqGenObj->last_doc_no = $documenySeqGenObj->prefix.$documenySeqGenObj->running.$documenySeqGenObj->suffix;
        $documenySeqGenObj->last_gen_datetime = Carbon::now();
        $documenySeqGenObj->save();

        $documentLogObj = new DocumentLog();
        $documentLogObj->doc_no = $documenySeqGenObj->last_doc_no;
        $documentLogObj->doc_type = "NT2";
        $documentLogObj->doc_status = 10;
        $documentLogObj->doc_expiry_date = Carbon::now()->addYear(5);
        $documentLogObj->user_id = $userTokenObj->user_id;
        $documentLogObj->created_date = Carbon::now();
        $documentLogObj->created_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $documentNT2Obj = new DocumentNT2();
        $documentNT2Obj->doc_no = $documentLogObj->doc_no;

        $documentNT2Obj->address_owner = $request->address_owner;
        $documentNT2Obj->address_name = $request->address_name;
        $documentNT2Obj->address_code = $request->address_code;
        $documentNT2Obj->address = $request->address;
        $documentNT2Obj->moo = $request->moo;
        $documentNT2Obj->soi = $request->soi;
        $documentNT2Obj->road = $request->road;
        $documentNT2Obj->province_code = $request->province_code;
        $documentNT2Obj->district_code = $request->district_code;
        $documentNT2Obj->sub_district_code = $request->sub_district_code;
        $documentNT2Obj->zip_code = $request->zip_code;
        $documentNT2Obj->telephone = $request->telephone;
        $documentNT2Obj->mobile_phone = $request->mobile_phone;
        $documentNT2Obj->fax = $request->fax;
        $documentNT2Obj->email = $request->email;
        $documentNT2Obj->latitude = $request->latitude;
        $documentNT2Obj->longitude = $request->longitude;
        $documentNT2Obj->business_type = $request->business_type;

        $documentNT2Obj->wastewater_total_checkpoint = $request->wastewater_total_checkpoint;
        $documentNT2Obj->wastewater_test_checkpoint_1_per_month = $request->wastewater_test_checkpoint_1_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_2_per_month = $request->wastewater_test_checkpoint_2_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_3_per_month = $request->wastewater_test_checkpoint_3_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_4_per_month = $request->wastewater_test_checkpoint_4_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_5_per_month = $request->wastewater_test_checkpoint_5_per_month;
        $documentNT2Obj->wastewater_total_per_month = $request->wastewater_total_per_month;

        if($request->hasFile('doc_attach_1')){
          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_7 = $documentFileObj->file_id;
        }

        $documentNT2Obj->save();

        DB::commit();


        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);


        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateDocumentNT2(Request $request)
  {
    $rules = [
      'doc_nt2_id' => 'required',
      'address_owner'  => 'required',
      'address_name'  => 'required',
      'address'  => 'required',
      'province_code'  => 'required',
      'district_code'  => 'required',
      'sub_district_code'  => 'required',
      'zip_code'  => 'required',
      // 'business_type'  => 'required',
      'wastewater_total_checkpoint'  => 'required',
      'wastewater_total_per_month'  => 'required',
      // 'doc_attach_1'  => 'required_with:cb_doc_attach_1',
      // 'doc_attach_2'  => 'required_with:cb_doc_attach_2',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        $documentNT2Obj = DocumentNT2::where("doc_nt2_id", $request->doc_nt2_id)->first();
        $documentNT2Obj->address_owner = $request->address_owner;
        $documentNT2Obj->address_name = $request->address_name;
        $documentNT2Obj->address_code = $request->address_code;
        $documentNT2Obj->address = $request->address;
        $documentNT2Obj->moo = $request->moo;
        $documentNT2Obj->soi = $request->soi;
        $documentNT2Obj->road = $request->road;
        $documentNT2Obj->province_code = $request->province_code;
        $documentNT2Obj->district_code = $request->district_code;
        $documentNT2Obj->sub_district_code = $request->sub_district_code;
        $documentNT2Obj->zip_code = $request->zip_code;
        $documentNT2Obj->telephone = $request->telephone;
        $documentNT2Obj->mobile_phone = $request->mobile_phone;
        $documentNT2Obj->fax = $request->fax;
        $documentNT2Obj->email = $request->email;
        $documentNT2Obj->latitude = $request->latitude;
        $documentNT2Obj->longitude = $request->longitude;
        $documentNT2Obj->business_type = $request->business_type;

        $documentNT2Obj->wastewater_total_checkpoint = $request->wastewater_total_checkpoint;
        $documentNT2Obj->wastewater_test_checkpoint_1_per_month = $request->wastewater_test_checkpoint_1_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_2_per_month = $request->wastewater_test_checkpoint_2_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_3_per_month = $request->wastewater_test_checkpoint_3_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_4_per_month = $request->wastewater_test_checkpoint_4_per_month;
        $documentNT2Obj->wastewater_test_checkpoint_5_per_month = $request->wastewater_test_checkpoint_5_per_month;
        $documentNT2Obj->wastewater_total_per_month = $request->wastewater_total_per_month;


        $documentLogObj = DocumentLog::where("doc_no", $documentNT2Obj->doc_no)->where("user_id", $userTokenObj->user_id)->first();
        $documentLogObj->doc_status = 10;
        $documentLogObj->updated_date = Carbon::now();
        $documentLogObj->updated_by = $userTokenObj->user_id;
        $documentLogObj->save();


        if($request->hasFile('doc_attach_1')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_1)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_1 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_1); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_1 = null;
          }

          $fileName1 = "doc_attach_1_".time().".".$request->doc_attach_1->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName1, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName1, $this->addWatermark2File($request->doc_attach_1, $request->doc_attach_1->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_1 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_2')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_2)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_2 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_2); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_2 = null;
          }

          $fileName2 = "doc_attach_2_".time().".".$request->doc_attach_2->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName2, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName2, $this->addWatermark2File($request->doc_attach_2, $request->doc_attach_2->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_2 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_3')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_3)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_3 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_3); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_3 = null;
          }

          $fileName3 = "doc_attach_3_".time().".".$request->doc_attach_3->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName3, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName3, $this->addWatermark2File($request->doc_attach_3, $request->doc_attach_3->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_3 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_4')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_4)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_4 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_4); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_4 = null;
          }

          $fileName4 = "doc_attach_4_".time().".".$request->doc_attach_4->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName4, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName4, $this->addWatermark2File($request->doc_attach_4, $request->doc_attach_4->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_4 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_5')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_5)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_5 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_5); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_5 = null;
          }

          $fileName5 = "doc_attach_5_".time().".".$request->doc_attach_5->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName5, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName5, $this->addWatermark2File($request->doc_attach_5, $request->doc_attach_5->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_5 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_6')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_6)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_6 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_6); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_6 = null;
          }

          $fileName6 = "doc_attach_6_".time().".".$request->doc_attach_6->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName6, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName6, $this->addWatermark2File($request->doc_attach_6, $request->doc_attach_6->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_6 = $documentFileObj->file_id;
        }
        if($request->hasFile('doc_attach_7')){
          $documentFileObj = DocumentFile::where("file_id", $documentNT2Obj->doc_attach_7)->first(); // Delete file log
          if($documentNT2Obj->doc_attach_7 !== null && Storage::disk('document_files')->exists($documentFileObj->file_name)){
            Storage::disk('document_files')->delete($documentNT2Obj->doc_attach_7); // Delete file
            $documentFileObj->delete();
            $documentNT2Obj->doc_attach_7 = null;
          }

          $fileName7 = "doc_attach_7_".time().".".$request->doc_attach_7->getClientOriginalExtension();
          $documentFileObj = $this->saveDocumentFile($documentLogObj->doc_no, $fileName7, $userTokenObj->user_id);

          Storage::disk('document_files')->put($fileName7, $this->addWatermark2File($request->doc_attach_7, $request->doc_attach_7->getClientOriginalExtension()));
          $documentNT2Obj->doc_attach_7 = $documentFileObj->file_id;
        }

        $documentNT2Obj->save();

        DB::commit();

        // -- Send Mail
        $this->sendMailConfirmDocumentCreated($userTokenObj->username, $documentLogObj->doc_type, $documentLogObj->doc_no);

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function cancelDocument(Request $request)
  {
    $rules = [
      'doc_no' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_delete["code"] = 0;
       $this->constants->response_delete['user_message'] = $validator->messages();
       return response()->json($this->constants->response_delete, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $documentLogObj = DocumentLog::where("user_id", $userTokenObj->user_id)->where("doc_no", $request->doc_no)->first();
        $documentLogObj->doc_status = 0;
        $documentLogObj->cancel_date = Carbon::now();
        $documentLogObj->cancel_by = $userTokenObj->user_id;
        $documentLogObj->save();

        $lovDocumentTypeObj = LovDocumentType::where("doc_type_code", $documentLogObj->doc_type)->first();
        $mailDetails = ['mail_type' => "document_cancel",'email' => $userTokenObj->username, 'document_type' => $lovDocumentTypeObj->doc_type_name_long, 'document_no' => $request->doc_no, 'document_status' => $documentLogObj->doc_status];
        Mail::to($userTokenObj->username)->send(new \App\Mail\MailController($mailDetails));


        $this->constants->response_delete["removed_records"] = 1;
        $this->constants->response_delete["user_message"] = "ยกเลิกการยื่นแบบเรียบร้อยแล้ว!";
        return response()->json($this->constants->response_delete, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_delete["code"] = 0;
        $this->constants->response_delete["developer_message"] = $e->getMessage();
        $this->constants->response_delete["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_delete, 500);
      }
    }
  }

  public function viewDocumentFileByFileId(Request $request, $fid)
  {
    $userTokenObj = $request->user();

    if($userTokenObj->role_id != 1){
      $documentFileObj = DocumentFile::where("file_id", base64_decode($fid))->first();
    } else {
      $documentFileObj = DocumentFile::where("file_id", base64_decode($fid))->where("user_id", $userTokenObj->user_id)->first();
    }

    if(!is_null($documentFileObj) && Storage::disk('document_files')->exists($documentFileObj->file_name)){
      return response()->file(Storage::disk('document_files')->path($documentFileObj->file_name));
    } else {
      return "<script>alert('ไม่พบภาพ');</script>";
    }
  }

  public function saveDocumentFile($docNo, $fileName, $userId)
  {
    $documentFileObj = new DocumentFile();
    $documentFileObj->doc_no = $docNo;
    $documentFileObj->file_name = $fileName;
    $documentFileObj->upload_date = Carbon::now();
    $documentFileObj->user_id = $userId;
    $documentFileObj->save();
    return $documentFileObj;
  }

  public function addWatermark2File($file, $extension) {
    // if ($extension == "png" || $extension == "jpg" || $extension == "jpeg"){
    //   $img = Image::make(file_get_contents($file));
    //   $img->insert(public_path('imgs/watermark_logo2.png'), 'bottom-right', 10, 10);
    //   $img->encode('png');
    //   return $img;
    // } else {
    //   return file_get_contents($file);
    // }

    return file_get_contents($file);
  }

  public function sendMailConfirmDocumentCreated($username, $docType, $docNo){
    $lovDocumentTypeObj = LovDocumentType::where("doc_type_code", $docType)->first();
    $mailDetails = ['mail_type' => "confirm_document_created",'email' => $username, 'document_type' => $lovDocumentTypeObj->doc_type_name_long, 'document_no' => $docNo];
    Mail::to($username)->send(new \App\Mail\MailController($mailDetails));
  }

  // ------------------------ Admin -----------------------------
  public function getDocumentInProcessByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                    ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                    ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                    ->whereIn("doc_status", [10,11,12,13,20])
                                    ->orderBy("created_date", "asc")
                                    ->get();

      if(count($documentLogList) > 0 && $userTokenObj->role_id != 1){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function getDocumentAwaitApproveByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                    ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                    ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                    ->whereIn("doc_status", [1])
                                    ->whereIn("doc_type", ["YV1","YV2","RB1","NT1","NT2","PG1","PG2"])
                                    ->orderBy("created_date", "asc")
                                    ->get();

      if(count($documentLogList) > 0 && $userTokenObj->role_id != 1){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function getDocumentApprovedByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                    ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                    ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                    ->whereIn("doc_status", [50])
                                    ->where("doc_type", "YV1")
                                    ->orderBy("created_date", "asc")
                                    ->get();

      if(count($documentLogList) > 0 && $userTokenObj->role_id != 1){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function getDocumentApprovedConnectPipeByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentLogList = DocumentLog::join("lov_document_type","lov_document_type.doc_type_code", "document_logs.doc_type")
                                    ->join("lov_document_status","lov_document_status.doc_status_id", "document_logs.doc_status")
                                    ->join("user_profile","user_profile.user_id", "document_logs.user_id")
                                    ->whereIn("doc_status", [1])
                                    ->where("doc_type", "RB1")
                                    ->orderBy("created_date", "asc")
                                    ->get();

      if(count($documentLogList) > 0 && $userTokenObj->role_id != 1){
        $this->constants->response_querylist["results"] = $documentLogList;
        $this->constants->response_querylist["results_count"] = count($documentLogList);
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

  public function getDocumentOverviewByAdmin(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $documentAwaitingVerify = DocumentLog::whereIn("doc_status", [10,11,12])->count();
      $documentAwaitingApprove = DocumentLog::whereIn("doc_status", [1])->count();
      $documentApproved = DocumentLog::whereIn("doc_status", [50])->count();
      $allUser = User::where("role_id", [1])->count();

      $documentYV1Last6Month = DB::select("
      select m.data_month, ifnull(total_created_doc, 0) as total_created_doc, ifnull(total_completed_doc, 0) as total_completed_doc, ifnull(total_approve_doc, 0) as total_approve_doc
      from (
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -5 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -4 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -3 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -2 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 0 MONTH), '%Y-%m') as data_month)
      ) m

      left join (select DATE_FORMAT(created_date, '%Y-%m') as data_month, count(doc_no) as total_created_doc  from document_logs where doc_type = 'YV1' group by data_month) tcd
      on m.data_month=tcd.data_month

      left join (select DATE_FORMAT(completed_date, '%Y-%m') as data_month, count(doc_no) as total_completed_doc  from document_logs where doc_type = 'YV1' and doc_status = 1 group by data_month) tcp
      on m.data_month=tcp.data_month

      left join (select DATE_FORMAT(approve_date, '%Y-%m') as data_month, count(doc_no) as total_approve_doc  from document_logs where doc_type = 'YV1' and doc_status = 50 group by data_month) tad
      on m.data_month=tad.data_month ");

      $documentRB1Last6Month = DB::select("
      select m.data_month, ifnull(total_created_doc, 0) as total_created_doc, ifnull(total_completed_doc, 0) as total_completed_doc, ifnull(total_approve_doc, 0) as total_approve_doc
      from (
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -5 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -4 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -3 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -2 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH), '%Y-%m') as data_month) UNION
      (select DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 0 MONTH), '%Y-%m') as data_month)
      ) m

      left join (select DATE_FORMAT(created_date, '%Y-%m') as data_month, count(doc_no) as total_created_doc  from document_logs where doc_type = 'RB1' group by data_month) tcd
      on m.data_month=tcd.data_month

      left join (select DATE_FORMAT(completed_date, '%Y-%m') as data_month, count(doc_no) as total_completed_doc  from document_logs where doc_type = 'RB1' and doc_status = 1 group by data_month) tcp
      on m.data_month=tcp.data_month

      left join (select DATE_FORMAT(approve_date, '%Y-%m') as data_month, count(doc_no) as total_approve_doc  from document_logs where doc_type = 'RB1' and doc_status = 50 group by data_month) tad
      on m.data_month=tad.data_month ");

      $this->constants->response_query["data"]["total_document_awaiting_verify"] = $documentAwaitingVerify;
      $this->constants->response_query["data"]["total_document_awaiting_approve"] = $documentAwaitingApprove;
      $this->constants->response_query["data"]["total_document_approved"] = $documentApproved;
      $this->constants->response_query["data"]["total_all_user"] = $allUser;
      $this->constants->response_query["data"]["total_document_yv1_last6month"] = $documentYV1Last6Month;
      $this->constants->response_query["data"]["total_document_rb1_last6month"] = $documentRB1Last6Month;
      return response()->json($this->constants->response_query, 200);
    } catch (\Illuminate\Database\QueryException $e) {
      $this->constants->response_query["code"] = 0;
      $this->constants->response_query["developer_message"] = $e->getMessage();
      $this->constants->response_query["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
      return response()->json($this->constants->response_query, 500);
    }
  }

  public function changeStatusDocumentByAdmin(Request $request)
  {
    $rules = [
      'log_id'  => 'required',
      'status_id'  => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $documentLogObj = DocumentLog::where("log_id", $request->log_id)->first();
        $documentLogObj->doc_status = $request->status_id;
        $documentLogObj->approve_date = Carbon::now();
        $documentLogObj->approve_by = $userTokenObj->user_id;
        $documentLogObj->save();

        if($documentLogObj->doc_status == 50){
          $userRequest = User::where("user_id", $documentLogObj->user_id)->first();
          $lovDocumentTypeObj = LovDocumentType::where("doc_type_code", $documentLogObj->doc_type)->first();

          $mailDetails = ['mail_type' => "document_approved", 'email' => $userRequest->username, 'document_type' => $lovDocumentTypeObj->doc_type_name_long, 'document_no' => $documentLogObj->doc_no, 'document_status' => $documentLogObj->doc_status];
          Mail::to($userRequest->username)->send(new \App\Mail\MailController($mailDetails));
        }

        $this->constants->response_insert["received_records"] = 1;
        $this->constants->response_insert["user_message"] = "เปลี่ยนสถานะอนุมัติการยื่นแบบเรียบร้อยแล้ว";
        return response()->json($this->constants->response_insert, 201);
      } catch (\Illuminate\Database\QueryException $e) {
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function createDocumentCommentLogByAdmin(Request $request)
  {
    $rules = [
      'doc_no'  => 'required',
      'doc_status'  => 'required',
      'file_comment_1'  => 'required_if:doc_status,11',
      'deadline_submit_doc'  => 'required_if:doc_status,11',
      'text_comment'  => 'required_if:doc_status,12',
    ];


    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        DB::beginTransaction();

        $userTokenObj = $request->user();

        if($userTokenObj->role_id != 1){

          $documentLogObj = DocumentLog::where("doc_no", $request->doc_no)->first();
          $documentLogObj->doc_status = $request->doc_status;

          $userRequest = User::where("user_id", $documentLogObj->user_id)->first();
          $lovDocumentTypeObj = LovDocumentType::where("doc_type_code", $documentLogObj->doc_type)->first();

          if($request->doc_status == 1){
            $documentLogObj->approve_date = Carbon::now();
            $documentLogObj->approve_by = $userTokenObj->user_id;

            $mailDetails = ['mail_type' => "document_completed",'email' => $userRequest->username, 'document_type' => $lovDocumentTypeObj->doc_type_name_long, 'document_no' => $request->doc_no, 'document_status' => $request->doc_status, 'document_status_desc' => "แบบถูกต้อง"];
            Mail::to($userRequest->username)->send(new \App\Mail\MailController($mailDetails));
          } else {
            $deadlineSubmitDoc = Carbon::parse($request->deadline_submit_doc);

            $mailDetails = ['mail_type' => "document_reject",'email' => $userRequest->username, 'document_type' => $lovDocumentTypeObj->doc_type_name_long, 'document_no' => $request->doc_no, 'document_status' => $request->doc_status, 'document_status_desc' => "มีข้อบกพร่อง", 'deadline_submit_doc' => ($deadlineSubmitDoc->locale("th")->isoFormat('DD MMM ').($deadlineSubmitDoc->locale("th")->isoFormat('YYYY')+543))];
            Mail::to($userRequest->username)->send(new \App\Mail\MailController($mailDetails));
          }
          $documentLogObj->save();

          $documentCommentLogObj = new DocumentCommentLog();
          $documentCommentLogObj->doc_no = $request->doc_no;
          $documentCommentLogObj->doc_status = $request->doc_status;
          $documentCommentLogObj->file_comment_1 = $request->file_comment_1;
          $documentCommentLogObj->file_comment_2 = $request->file_comment_2;
          $documentCommentLogObj->file_comment_3 = $request->file_comment_3;
          $documentCommentLogObj->file_comment_4 = $request->file_comment_4;
          $documentCommentLogObj->file_comment_5 = $request->file_comment_5;
          $documentCommentLogObj->deadline_submit_doc = $request->deadline_submit_doc;
          $documentCommentLogObj->text_comment = $request->text_comment;

          $documentCommentLogObj->comment_date = Carbon::now();
          $documentCommentLogObj->comment_by = $userTokenObj->user_id;
          $documentCommentLogObj->save();

          DB::commit();

          $this->constants->response_insert["received_records"] = 1;
          $this->constants->response_insert["user_message"] = $this->constants->MessageInsertDataSuccess;
          return response()->json($this->constants->response_insert, 201);
        } else {
          DB::rollBack();
          $this->constants->response_insert["code"] = 0;
          $this->constants->response_insert["developer_message"] = $e->getMessage();
          $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
          return response()->json($this->constants->response_insert, 500);
        }
      } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        $this->constants->response_insert["code"] = 0;
        $this->constants->response_insert["developer_message"] = $e->getMessage();
        $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
        return response()->json($this->constants->response_insert, 500);
      }
    }
  }

  public function updateStatusDocumentReading(Request $request)
  {
    $rules = [
      'doc_no' => 'required',
      'is_reading' => 'required',
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

        if($request->is_reading == 1){
          $documentLogObj->document_reading_date = Carbon::now();
          $documentLogObj->document_reading_by = $userTokenObj->user_id;
        } else {
          $documentLogObj->document_reading_date = null;
          $documentLogObj->document_reading_by = null;
        }

        $documentLogObj->save();

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

  public function checkDeadlineSubmitDocument(Request $request)
  {
    try {
      $documentLogList = DocumentLog::join("document_comment_logs", "document_comment_logs.doc_no", "document_logs.doc_no")
                                    ->whereIn("document_logs.doc_status", [11, 12])
                                    ->where("deadline_submit_doc", "<",  Carbon::now()->format("Y-m-d"))
                                    ->get();

      for ($i=0; $i < count($documentLogList); $i++) {
        $documentLogObj = DocumentLog::where("doc_no", $documentLogList[$i]->doc_no)->first();
        $documentLogObj->doc_status = 3;
        $documentLogObj->save();
      }

      $this->constants->response_insert["received_records"] = count($documentLogList);
      $this->constants->response_insert["user_message"] = $this->constants->MessageUpdateDataSuccess;
      return response()->json($this->constants->response_insert, 201);
    } catch (\Illuminate\Database\QueryException $e) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert["developer_message"] = $e->getMessage();
      $this->constants->response_insert["user_message"] = $this->constants->MessageSystemErrorContactAdmin;
      return response()->json($this->constants->response_insert, 500);
    }
  }

  public function reportUserDocumentCompleteExcel(Request $request) {
    return Excel::download(new DocumentCompleteReportExport(), 'รายงานผู้ได้รับการตรวจสอบเอกสารถูกต้อง.xlsx');
  }

  public function reportUserDocumentApproveExcel(Request $request) {
    return Excel::download(new DocumentApproveReportExport(), 'รายชื่อผู้ได้รับการยกเว้นค่าธรรมเนียมบำบัดน้ำเสีย.xlsx');
  }

  public function reportUserDocumentApproveConnectPipeExcel(Request $request) {
    return Excel::download(new DocumentApproveConnectPipeReportExport(), 'รายงานการขอรับบริการบำบัดน้ำเสีย.xlsx');
  }

}
