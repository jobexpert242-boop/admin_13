<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = Auth::user();
        return [
            ...parent::share($request),
            'flash' => [
                'status'  => fn() => $request->session()->get('status'),
                'error'   => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
            ],
            'auth' => function () {
                $user = auth()->user();

                return [
                    'user' => $user,
                    'roles' => $user ? $user->getRoleNames() : [],
                    'permissions' => $user ? $user->getAllPermissions()->pluck('name') : [],
                ];
            },
            'site' => SiteSetting::first(),
        ];
    }
}
