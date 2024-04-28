<?php

declare(strict_types=1);

namespace App\Model;

use App\Traits\Aware\LoggerAwareTrait;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\SqlInterface;
use Laminas\Db\Sql\Where;

use function array_filter;
use function count;
use function explode;
use function is_string;
use function str_replace;
use function trim;

class DbRunner implements DbRunnerInterface
{
    use LoggerAwareTrait;

    protected DbAdapter $db;

    public function __construct(DbAdapter $db)
    {
        $this->db = $db;
    }

    public function setDb(DbAdapter $db)
    {
        $this->db = $db;
    }

    public function getDb(): DbAdapter
    {
        return $this->db;
    }

    public function executeSelect(SqlInterface|string $select, bool $logSqlString = false): ?ResultInterface
    {
        $result = $this->executeSql($select, $logSqlString);

        if (! $result->isQueryResult()) {
            return null;
        }

        return $result;
    }

    public function executeCommand(SqlInterface|string $command, bool $logSqlString = false): ResultInterface
    {
        return $this->executeSql($command, $logSqlString);
    }

    public function executeSql(SqlInterface|string $query, bool $logSqlString = false): ResultInterface
    {
        $this->logSql($query, $logSqlString);

        if (is_string($query)) {
            $statement = $this->db->query($query);
        } else {
            $sql = new Sql($this->db);

            $statement = $sql->prepareStatementForSqlObject($query);
        }

        return $statement->execute();
    }

    protected function logSql(SqlInterface|string $query, bool $logSqlString): void
    {
        if (! $logSqlString) {
            return;
        }

        if (is_string($query)) {
            $this->getLogger()->debug($query);
        } else {
            $this->getLogger()->debug($query->getSqlString($this->db->getPlatform()));
        }
    }

    public function whereLikeSearchWithSqlObject(string $suchtext, array $columns): ?Where
    {
        if (empty($suchtext)) {
            return null;
        }

        // init
        $suchtext           = str_replace(['(', ')', '/', '\\'], ' ', $suchtext);
        $suchtextParts      = array_filter(explode(',', trim($suchtext)), 'strlen');
        $suchtextPartsCount = count($suchtextParts);

        if (! $suchtextPartsCount) {
            return null;
        }

        // process
        $i                 = 0;
        $where             = new Where();
        $outerPredicateSet = $where->nest();

        foreach ($suchtextParts as $value) {
            $predicates       = [];
            $suchtextSubparts = array_filter(explode(' ', trim($value)), 'strlen');

            foreach ($suchtextSubparts as $v) {
                $v = trim($v);

                if ('' !== $v) {
                    $suchtextFields = [];

                    foreach ($columns as $col) {
                        $suchtextFields[] = new Like($col['column'], str_replace('[value]', $v, $col['search']));
                    }

                    $predicates[] = new PredicateSet($suchtextFields, PredicateSet::COMBINED_BY_OR);
                }
            }

            $outerPredicateSet
                ->nest()
                ->addPredicates($predicates, PredicateSet::COMBINED_BY_AND)
                ->unnest();

            if (++$i !== $suchtextPartsCount) {
                $outerPredicateSet
                    ->or;
            }
        }

        $outerPredicateSet->where->unnest();

        return $where;
    }
}
