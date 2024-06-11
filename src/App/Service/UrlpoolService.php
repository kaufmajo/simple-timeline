<?php

declare(strict_types=1);

namespace App\Service;

use App\Traits\Aware\LoggerAwareTrait;
use App\Traits\Aware\UrlHelperAwareTrait;
use Mezzio\Session\SessionInterface;

class UrlpoolService
{
    use LoggerAwareTrait;
    use UrlHelperAwareTrait;

    private array $params = [];
    private array $query = [];
    private ?string $fragment = null;

    protected SessionInterface $session;

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function params(array $params = []): static
    {
        $this->params = $params;

        return $this;
    }

    public function query(array $query = []): static
    {
        $this->query = $query;

        return $this;
    }

    public function fragment(int|string|false|null $fragment = null): static
    {
        $this->fragment = $fragment ? 'anchor-' . $fragment : null;

        return $this;
    }

    public function save(): static
    {
        $name = $this->getUrlHelper()->getRouteResult()->getMatchedRouteName();

        $uri = $this->getUrlHelper()->getRequest()->getUri();

        $this->session->set('url', [$name, serialize($uri)]);

        return $this;
    }

    public function get(): string
    {
        $data = $this->session->get('url');

        $uri = unserialize($data[1]);

        parse_str($uri->getQuery(), $query_array);

        return $this->getUrlHelper()->generate($data[0], $this->params, array_merge($query_array, $this->query), $this->fragment);
    }
}
