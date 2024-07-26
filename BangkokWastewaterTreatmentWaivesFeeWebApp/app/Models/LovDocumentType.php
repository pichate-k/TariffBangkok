<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LovDocumentType extends Model
{
  protected $primaryKey = 'doc_type_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'lov_document_type';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
