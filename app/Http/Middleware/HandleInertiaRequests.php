<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $feedback = $user ? \App\Models\Feedback::where('user_id', $user->id)->first() : null;

        return [
            ...parent::share($request),
            'system_closure_time' => \App\Models\Setting::where('key', 'system_closure_time')->value('value'),
            'auth' => [
                'user' => $user,
                'has_rated' => (bool)$feedback,
                'can_rate' => $user ? \App\Models\UserScore::where('user_id', $user->id)->exists() : false,
                'feedback' => $feedback ? [
                    'rating' => $feedback->rating,
                    'comment' => $feedback->comment,
                ] : null,
            ],
        ];
    }
}
