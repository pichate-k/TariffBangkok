<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSeqGen extends Model
{
  protected $primaryKey = 'seq_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_seq_gen';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
