<?php

namespace Leantime\Core\Middleware;

use Closure;
use Leantime\Core\Events\DispatchesEvents;
use Leantime\Core\Http\IncomingRequest;
use Leantime\Domain\Auth\Services\Auth as AuthService;
use Leantime\Domain\Projects\Services\Projects as ProjectsService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Auth
{
    use DispatchesEvents;

    /**
     * Public actions
     */
    private array $publicActions = [
        'auth.login',
        'auth.resetPw',
        'auth.userInvite',
        'install',
        'install.update',
        'errors.error404',
        'errors.error500',
        'api.i18n',
        'api.static-asset',
        'calendar.ical',
        'oidc.login',
        'oidc.callback',
        'cron.run',
    ];

    public function __construct(
        private AuthService $authService,
        private ProjectsService $projectsService,
    ) {
        $this->publicActions = self::dispatchFilter('publicActions', $this->publicActions, ['bootloader' => $this]);
    }

    /**
     * Redirect with origin
     *
     * @return Response|RedirectResponse
     *
     * @throws BindingResolutionException
     */
    public function redirectWithOrigin(string $route, string $origin, IncomingRequest $request): false|RedirectResponse
    {
        $destination = BASE_URL.'/'.ltrim(str_replace('.', '/', $route), '/');
        $queryParams = ! empty($origin) && $origin !== '/' ? '?'.http_build_query(['redirect' => $origin]) : '';

        if ($request->getCurrentRoute() == $route) {
            return false;
        }

        return new RedirectResponse($destination.$queryParams);
    }

    /**
     * Handle the request
     */
    public function handle(IncomingRequest $request, Closure $next): Response
    {

        if (in_array($request->getCurrentRoute(), $this->publicActions)) {
            return $next($request);
        }

        if (! $this->authService->loggedIn()) {
            return $this->redirectWithOrigin('auth.login', $request->getRequestUri(), $request) ?: $next($request);
        }

        // Check if trying to access twoFA code page, or if trying to access any other action without verifying the code.
        if (session('userdata.twoFAEnabled') && ! session('userdata.twoFAVerified')) {
            return $this->redirectWithOrigin('twoFA.verify', $_GET['redirect'] ?? '', $request) ?: $next($request);
        }

        self::dispatchEvent('logged_in', ['application' => $this]);

        return $next($request);
    }
}
