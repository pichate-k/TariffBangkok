<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Constants;
use App\Models\LovProvince;
use App\Models\LovDistrict;
use App\Models\LovSubDistrict;
use App\Models\LovBuildingType;
use App\Models\LovBuildingSize;
use App\Models\LovWastewaterTreatmentName;

class LovController extends Controller
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

  public function getLovProvince(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $lovProvinceList = LovProvince::all();

      if(count($lovProvinceList) > 0){
        $this->constants->response_querylist["results"] = $lovProvinceList;
        $this->constants->response_querylist["results_count"] = count($lovProvinceList);
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

  public function getLovDistrictByProvinceCode(Request $request)
  {
    $rules = [
      'province_code' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $lovDistrictList = LovDistrict::where("province_code", $request->province_code)->get();

        if(count($lovDistrictList) > 0){
          $this->constants->response_querylist["results"] = $lovDistrictList;
          $this->constants->response_querylist["results_count"] = count($lovDistrictList);
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

  public function getLovSubDistrictByDistrictCode(Request $request)
  {
    $rules = [
      'district_code' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $lovSubDistrictList = LovSubDistrict::where("district_code", $request->district_code)->get();

        if(count($lovSubDistrictList) > 0){
          $this->constants->response_querylist["results"] = $lovSubDistrictList;
          $this->constants->response_querylist["results_count"] = count($lovSubDistrictList);
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

  public function getLovBuildingType(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $lovBuildingType = LovBuildingType::all();

      if(count($lovBuildingType) > 0){
        $this->constants->response_querylist["results"] = $lovBuildingType;
        $this->constants->response_querylist["results_count"] = count($lovBuildingType);
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

  public function getLovBuildingSize(Request $request)
  {
    $rules = [
      'building_type_id' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules, $this->constants->validation_messages);

    if ($validator->fails()) {
      $this->constants->response_insert["code"] = 0;
      $this->constants->response_insert['user_message'] = $validator->messages();
      return response()->json($this->constants->response_insert, 400);
    } else {

      try {
        $userTokenObj = $request->user();

        $lovBuildingSize = LovBuildingSize::where("building_type_id", $request->building_type_id)->get();

        if(count($lovBuildingSize) > 0){
          $this->constants->response_querylist["results"] = $lovBuildingSize;
          $this->constants->response_querylist["results_count"] = count($lovBuildingSize);
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

  public function getWastewaterTreatmentName(Request $request)
  {
    try {
      $userTokenObj = $request->user();

      $lovWastewaterTreatmentName = LovWastewaterTreatmentName::all();

      if(count($lovWastewaterTreatmentName) > 0){
        $this->constants->response_querylist["results"] = $lovWastewaterTreatmentName;
        $this->constants->response_querylist["results_count"] = count($lovWastewaterTreatmentName);
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
