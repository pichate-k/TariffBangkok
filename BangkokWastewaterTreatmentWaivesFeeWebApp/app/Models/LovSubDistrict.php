<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovSubDistrict extends Model
{
  protected $primaryKey = 'sub_district_code';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_sub_district';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
