<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovDistrict extends Model
{
  protected $primaryKey = 'district_code';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_district';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
