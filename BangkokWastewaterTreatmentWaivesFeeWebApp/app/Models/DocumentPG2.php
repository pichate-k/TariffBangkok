<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentPG2 extends Model
{
  protected $primaryKey = 'doc_pg2_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_pg_2';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
