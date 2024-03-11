<?php

declare(strict_types=1);

namespace App\Storage;

use App\Model\RepositoryInterface;
use Exception;

class RepositoryStorage
{
    /** @var array */
    protected array $repositoryArray = [];

    public function set(string $key, RepositoryInterface $repository)
    {
        if ($repository instanceof RepositoryInterface) {
            $this->repositoryArray[$key] = $repository;
        }
    }

    /**
     * @return RepositoryInterface
     * @throws Exception
     */
    public function get(string $key)
    {
        if (isset($this->repositoryArray[$key])) {
            return $this->repositoryArray[$key];
        }

        throw new Exception('Repository is not in storage: ' . $key);
    }
}
