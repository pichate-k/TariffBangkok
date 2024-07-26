<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLog extends Model
{
  protected $primaryKey = 'log_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_logs';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public function LovDistrict() {
      return $this->hasMany("\App\Models\LovDistrict", "province_code", "province_code");
  }

  public function LovSubDistrict() {
      return $this->hasMany("\App\Models\LovSubDistrict", "district_code", "district_code");
  }

  public function LovBuildingSize() {
      return $this->hasMany("\App\Models\LovBuildingSize", "building_type_id", "building_type_id");
  }

}
