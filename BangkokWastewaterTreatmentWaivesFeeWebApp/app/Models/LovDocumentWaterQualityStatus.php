<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovDocumentWaterQualityStatus extends Model
{
  protected $primaryKey = 'quality_status_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_document_water_quality_status';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
