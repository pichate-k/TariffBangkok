<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovBuildingType extends Model
{
  protected $primaryKey = 'building_type_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_building_type';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
