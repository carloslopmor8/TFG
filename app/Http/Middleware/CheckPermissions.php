<?php

namespace App\Http\Middleware;

use App\Models\Proyecto;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();

        $assignedProjectIds = Proyecto::whereHas('usuarios', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->pluck('id');

        $createdProjectIds = Proyecto::where('user_id', $user->id)->pluck('id');

        $allAccessibleProjectIds = $assignedProjectIds->merge($createdProjectIds);

        $requestedProjectId = $request->route('project_id');

        Log::info($request);

        if (!$allAccessibleProjectIds->contains($requestedProjectId)) {
            // If the user does not have access to the requested project, you can redirect them or return a response.
            // For example, you can throw a 403 Forbidden HTTP exception:
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
