<?php

declare(strict_types=1);

namespace App\Service;

use App\Traits\Aware\LoggerAwareTrait;
use Mezzio\Session\SessionInterface;

use function ksort;
use function time;
use function var_dump;

use const SORT_NUMERIC;

class UrlpoolService
{
    use LoggerAwareTrait;

    protected SessionInterface $session;

    protected int $level = 1;

    protected bool $keep = true;

    protected string $rootNamespace = '';

    protected string $namespace = '';

    protected string $url = '';

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function keep(): static
    {
        $this->keep = false;

        return $this;
    }

    public function level(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getRootNamespace(): string
    {
        return $this->rootNamespace;
    }

    public function setRootNamespace(string $namespace): static
    {
        $this->rootNamespace = $namespace;

        return $this;
    }

    public function namespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    private function resetLevel(): static
    {
        $this->level = 1;

        return $this;
    }

    private function resetKeep(): static
    {
        $this->keep = true;

        return $this;
    }

    private function resetNamespace(): static
    {
        $this->namespace = $this->rootNamespace;

        return $this;
    }

    private function resetUrl(): static
    {
        $this->url = '';

        return $this;
    }

    private function getNamespace(): string
    {
        return '' === $this->namespace && $this->rootNamespace ? $this->rootNamespace : $this->namespace;
    }

    private function getLevel(): int
    {
        return $this->level;
    }

    private function getUrlstring(): string
    {
        return $this->url;
    }

    public function saveUrl(): void
    {
        // init
        $timestamp_new = time();

        // process
        if (! empty($this->session->get('urls'))) {
            $urls = $this->session->get('urls');

            foreach ($urls as $timestamp_old => $value) {
                if ($value['namespace'] === $this->getNamespace() && $value['level'] === $this->getLevel()) {
                    unset($urls[$timestamp_old]);

                    break;
                }
            }
        }

        $urls[$timestamp_new]['namespace'] = $this->getNamespace();
        $urls[$timestamp_new]['level']     = $this->getLevel();
        $urls[$timestamp_new]['url']       = $this->getUrlstring();

        $this->session->set('urls', $urls);
    }

    public function getUrl(): string
    {
        // init
        $timestamp = $return = null;

        // process
        if (! empty($this->session->get('urls'))) {
            $urls = $this->session->get('urls');

            ksort($urls, SORT_NUMERIC);

            foreach ($urls as $key => $url) {
                $timestamp = $key;

                if ($url['namespace'] === $this->getNamespace() && $url['level'] === $this->getLevel()) {
                    break;
                }
            }

            if ($timestamp) {
                $return = $urls[$timestamp]['url'];

                if ($this->keep) {
                    unset($urls[$timestamp]);
                    $this->session->set('urls', $urls);
                }
            }
        }

        // ...
        $this->resetKeep()->resetLevel()->resetNamespace()->resetUrl();

        // ...
        return $return ?: '/';
    }

    public function getUrlWithAnchor(int|string $anchor): string
    {
        return $this->getUrl() . '#' . self::getAnchorString($anchor);
    }

    public static function getAnchorString(int|string $anchor): string
    {
        return "anchor-" . $anchor;
    }

    public function dump(): static
    {
        var_dump($this->session->get('urls'));

        return $this;
    }

    public function die(): never
    {
        die("___SCRIPT_STOPPED_BY_METHOD___");
    }
}
