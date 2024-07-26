<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
  protected $primaryKey = 'file_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'document_files';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
