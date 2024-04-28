<?php

declare(strict_types=1);

namespace App\Storage;

use App\Model\CommandInterface;
use Exception;

class CommandStorage
{
    /** @var array */
    protected array $commandArray = [];

    public function set(string $key, CommandInterface $command)
    {
        if ($command instanceof CommandInterface) {
            $this->commandArray[$key] = $command;
        }
    }

    /**
     * @return CommandInterface
     * @throws Exception
     */
    public function get(string $key)
    {
        if (isset($this->commandArray[$key])) {
            return $this->commandArray[$key];
        }

        throw new Exception('Command is not in storage: ' . $key);
    }
}
