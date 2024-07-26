<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Constants;
use App\Models\User;

class Role
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->constants = new Constants();
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
   public function handle(Request $request, Closure $next, ... $roles)
   {
     if (!Auth::check())
         return redirect('/');

     $user = Auth::user();
     if(in_array("*", $roles) || in_array($user->role_id, $roles)) {
       return $next($request);
     } else {
       if ($request->expectsJson()) {
         $constants = new Constants();
         $constants->response_querylist["code"] = 0;
         $constants->response_querylist["user_message"] = $constants->MessageNotAllowed;
         return response()->json($constants->response_querylist, 403);
       } else {
         return redirect('/');
       }
     }
   }

}
