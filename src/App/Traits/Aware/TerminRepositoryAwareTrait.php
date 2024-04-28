<?php

declare(strict_types=1);

namespace App\Traits\Aware;

use App\Model\Termin\TerminRepositoryInterface;

trait TerminRepositoryAwareTrait
{
    protected TerminRepositoryInterface $terminRepository;

    public function getTerminRepository(): TerminRepositoryInterface
    {
        return $this->terminRepository;
    }

    public function setTerminRepository(TerminRepositoryInterface $terminRepository)
    {
        $this->terminRepository = $terminRepository;
    }
}
