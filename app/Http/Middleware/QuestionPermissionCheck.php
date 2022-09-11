<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;

class QuestionPermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $question = Question::where('id', $request->id)->first();

        if (Auth::user()->admin || $question->user_id == Auth::user()->id) {
            return $next($request);
        }
        return response()->json([
            'message' => 'Only Admins or Asker can access this route!'
        ], 401);
    }
}
