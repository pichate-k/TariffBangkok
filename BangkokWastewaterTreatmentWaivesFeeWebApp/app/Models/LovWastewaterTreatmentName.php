<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovWastewaterTreatmentName extends Model
{
  protected $primaryKey = 'treatment_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_wastewater_treatment_name';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
