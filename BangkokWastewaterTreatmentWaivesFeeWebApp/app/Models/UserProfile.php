<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
  protected $primaryKey = 'profile_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_profile';

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

}
