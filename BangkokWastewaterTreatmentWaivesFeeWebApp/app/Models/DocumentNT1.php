<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentNT1 extends Model
{
  protected $primaryKey = 'doc_nt1_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_nt_1';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
