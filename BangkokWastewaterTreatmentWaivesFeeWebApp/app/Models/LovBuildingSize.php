<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovBuildingSize extends Model
{
  protected $primaryKey = 'building_size_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_building_size';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
