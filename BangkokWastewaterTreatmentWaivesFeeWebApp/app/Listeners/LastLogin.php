<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AccessLog;

class LastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
      $loginDateTime = Carbon::now();

      $event->user->update(['last_login_dt' => $loginDateTime]);

      $accessLogObj = new AccessLog();
      $accessLogObj->user_id = $event->user->user_id;
      $accessLogObj->username = $event->user->username;
      $accessLogObj->timestamp = $loginDateTime;
      $accessLogObj->ip_address = request()->getClientIp();
      $accessLogObj->user_agent = request()->userAgent();
      $accessLogObj->save();
    }
}
