<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Model\Entity\EntityInterface;
use App\Model\EntityRepositoryInterface;
use Laminas\Db\ResultSet\ResultSet;

interface TerminRepositoryInterface extends EntityRepositoryInterface
{
    public function findTerminById(int $id): null|TerminEntityInterface|EntityInterface;

    public function fetchTermin(array $params = [], array $groupBy = [], string $order = ''): ResultSet;

    public function fetchMitvon(array $params = [], string $order = ''): ResultSet;

    public function fetchKategorie(array $params = [], string $order = ''): ResultSet;

    public function fetchBetreff(array $params = [], string $order = ''): ResultSet;

    public function fetchLink(array $params = [], string $order = ''): ResultSet;

    public function fetchLinkTitel(array $params = [], string $order = ''): ResultSet;

    public function fetchImage(array $params = [], string $order = ''): ResultSet;
}
