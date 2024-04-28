<?php

declare(strict_types=1);

namespace App\Handler\Auth;

use App\Handler\AbstractBaseHandler;
use App\Traits\Aware\HistoryServiceAwareTrait;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfGuardInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;

class DefAppLoginHandler extends AbstractBaseHandler implements RequestHandlerInterface
{
    use HistoryServiceAwareTrait;

    private const REDIRECT_ATTRIBUTE = 'authentication:redirect';

    private PhpSession $adapter;

    public function __construct(PhpSession $adapter)
    {
        $this->adapter = $adapter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface|EmptyResponse
    {
        $guard    = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        $session  = $request->getAttribute('session');
        $redirect = $this->getRedirect($request, $session);

        // Handle submitted credentials
        if ('POST' === $request->getMethod()) {
            return $this->handleLoginAttempt($request, $session, $redirect, $guard);
        }

        // Display initial login form
        $session->set(self::REDIRECT_ATTRIBUTE, $redirect);

        return new HtmlResponse($this->templateRenderer->render(
            'app::def/auth/def-app-login',
            ['__csrf' => $guard->generateToken()]
        ));
    }

    private function getRedirect(ServerRequestInterface $request, SessionInterface $session): string|Uri
    {
        $redirect = $session->get(self::REDIRECT_ATTRIBUTE);

        if (! $redirect) {
            $redirect = new Uri($request->getHeaderLine('Referer'));

            if (in_array($redirect->getPath(), ['', '/app-login'], true)) {
                $redirect = '/manage/home-read';
            }
        }

        return $redirect;
    }

    private function handleLoginAttempt(ServerRequestInterface $request, SessionInterface $session, string|Uri $redirect, CsrfGuardInterface $guard): ResponseInterface
    {
        // init
        $data     = $request->getParsedBody();
        $token    = $data['__csrf'] ?? '';
        $username = $data['username'] ?? '';

        // historyThrottle
        $this->getHistoryService()->insertThrottleRow($username)->throttle($username);

        // csrf validation
        if (! $guard->validateToken($token)) {
            return new EmptyResponse(412); // Precondition failed
        }

        // User session takes precedence over user/pass POST in
        // the auth adapter so we remove the session prior
        // to auth attempt
        $session->unset(UserInterface::class);

        // Login was successful
        if ($this->adapter->authenticate($request)) {
            $session->unset(self::REDIRECT_ATTRIBUTE);
            return new RedirectResponse($redirect);
        }

        // Login failed
        return new HtmlResponse($this->templateRenderer->render(
            'app::def/auth/def-app-login',
            [
                'error'  => 'Invalid credentials; please try again',
                '__csrf' => $guard->generateToken(),
            ]
        ));
    }
}
