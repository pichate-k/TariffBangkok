<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovProvince extends Model
{
  protected $primaryKey = 'province_code';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_provinces';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
