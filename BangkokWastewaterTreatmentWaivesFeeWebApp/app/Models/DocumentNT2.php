<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentNT2 extends Model
{
  protected $primaryKey = 'doc_nt2_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_nt_2';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
