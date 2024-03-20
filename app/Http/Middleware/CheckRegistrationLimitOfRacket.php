<?php

namespace App\Http\Middleware;

use App\Models\Racket;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRegistrationLimitOfRacket
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
        try {
            // 一般ユーザーで利用していた場合に回数制限
            if (Auth::guard('user')->check()) {
                // ログインしているユーザーのIDを取得する例
                $userId = Auth::guard('user')->id();

                // リクエストがcreateアクションであるかを確認
                if ($request->route()->getActionMethod() == 'store') {
                    // 今日の日付で登録された回数を取得
                    $registrationCount = Racket::where('posting_user_id', $userId)
                        ->whereDate('created_at', Carbon::today())
                        ->count();

                    $limitCount = 10;
                    // 制限を超えていないかをチェック
                    if ($registrationCount >= $limitCount) {
                        return response()->json(
                            [
                                'errors' => [
                                    'limit' => [
                                        "1日に登録できる回数({$limitCount}回)を超えました。1日経ってから再度登録してください"
                                    ]
                                ]
                            ],
                            403
                        );
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());

            throw $e;
        }

        return $next($request);
    }
}
