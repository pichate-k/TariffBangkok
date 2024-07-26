<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordReset extends Model
{
  protected $primaryKey = 'log_id';
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'password_resets';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
