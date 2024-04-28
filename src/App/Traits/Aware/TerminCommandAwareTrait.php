<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Model\Termin\TerminCommandInterface;

trait TerminCommandAwareTrait
{
    protected TerminCommandInterface $terminCommand;

    /**
     * @return TerminCommandInterface
     */
    public function getTerminCommand(): TerminCommandInterface
    {
        return $this->terminCommand;
    }

    public function setTerminCommand(TerminCommandInterface $terminCommand): void
    {
        $this->terminCommand = $terminCommand;
    }
}
