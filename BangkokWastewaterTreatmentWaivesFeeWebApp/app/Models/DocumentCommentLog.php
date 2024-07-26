<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCommentLog extends Model
{
  protected $primaryKey = 'log_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_comment_logs';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
