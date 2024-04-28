<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Enum\TerminStatusEnum;
use App\Model\AbstractRepository;
use App\Model\DbRunnerInterface;
use App\Model\Entity\EntityInterface;
use App\Traits\Aware\ConfigAwareTrait;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql;
use Laminas\Db\Sql\Where;
use Laminas\Hydrator\HydratorInterface;

class TerminRepository extends AbstractRepository implements TerminRepositoryInterface
{
    use ConfigAwareTrait;

    public function __construct(DbRunnerInterface $dbRunner, HydratorInterface $hydrator, TerminEntity $prototype)
    {
        parent::__construct($dbRunner, $hydrator, $prototype);
    }

    public function findEntityById(int $entityId): TerminEntityInterface
    {
        return $this->findTerminById($entityId);
    }

    public function findTerminById(int $id): null|TerminEntityInterface|EntityInterface
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t4' => 'tajo1_termin']);
        $select->where(['t4.termin_id' => $id]);

        $entity = $this->findFirst($select);

        return $this->mapReferences($entity);
    }

    public function mapReferences(?EntityInterface $entity): ?EntityInterface
    {
        return $entity;
    }

    protected function getTerminJoinCondition(array $params = []): Where
    {
        $where = new Where();

        $where
            ->equalTo('t4.datum_id', 't3.datum_id', Sql\ExpressionInterface::TYPE_IDENTIFIER, Sql\ExpressionInterface::TYPE_IDENTIFIER)
            ->equalTo('t4.termin_ist_geloescht', 0);

        // id
        if (isset($params['id']) && !empty($params['id'])) {
            $where->equalTo('t4.termin_id', $params['id']);
        }

        // suchtext
        if (!empty($params['suchtext'])) {
            $whereLikeSearch = $this->dbRunner->whereLikeSearchWithSqlObject($params['suchtext'], [
                ['column' => 't4.termin_mitvon', 'search' => '%[value]%'],
                ['column' => 't4.termin_id', 'search' => '%[value]%'],
                ['column' => 't4.termin_kategorie', 'search' => '%[value]%'],
                ['column' => 't4.termin_betreff', 'search' => '%[value]%'],
                ['column' => 't4.termin_text', 'search' => '%[value]%'],
                ['column' => 't3.datum_datum', 'search' => '[value]%'],
                ['column' => 't3.datum_wochentag_lang_de', 'search' => '[value]%'],
                ['column' => 't3.datum_wochentag_lang_en', 'search' => '[value]%'],
                ['column' => 't3.datum_monat_lang_de', 'search' => '[value]%'],
                ['column' => 't3.datum_monat_lang_en', 'search' => '[value]%'],
                ['column' => 't3.datum_monat', 'search' => '[value]'],
                ['column' => 't3.datum_datum_1_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_2_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_3_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_4_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_5_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_6_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_7_de', 'search' => '[value]%'],
                ['column' => 't3.datum_datum_8_de', 'search' => '[value]%'],
            ]);

            if ($whereLikeSearch) {
                $where->addPredicates($whereLikeSearch);
            }
        }

        // kategorie
        if (!empty($params['kategorie'])) {
            $where->in('t4.termin_kategorie', $params['kategorie']);
        }

        // status
        if (!empty($params['status'])) {
            $where->in('t4.termin_status', $params['status']);
        }

        // ansicht
        if (!empty($params['ansicht'])) {
            $outerPredicateSet = $where->nest();
            $outerPredicateSet->in('t4.termin_ansicht', $params['ansicht']);
            $outerPredicateSet->where->unnest();
        }

        // tagezuvor
        if (!empty($params['tagezuvor'])) {
            $where
                ->nest()
                ->isNull('t4.termin_zeige_tagezuvor')
                ->or
                ->expression('DATEDIFF(`t3`.`datum_datum`, CURRENT_DATE())  <= `t4`.`termin_zeige_tagezuvor`', [])
                ->unnest();
        }

        // drucken
        if (!empty($params['drucken'])) {
            $where
                ->nest()
                ->isNull('t4.termin_aktiviere_drucken')
                ->or
                ->equalTo('t4.termin_aktiviere_drucken', $params['drucken'])
                ->unnest();
        }

        return $where;
    }

    protected function getWhereCondition(array $params = []): Where
    {
        $where = new Where();

        // start
        if (isset($params['start']) && !empty($params['start'])) {
            $where->expression('`t3`.`datum_datum` >= ?', $params['start']);
        }

        // ende
        if (isset($params['ende']) && !empty($params['ende'])) {
            $where->expression('`t3`.`datum_datum` <= ?', $params['ende']);
        }

        // anzeige
        if (isset($params['anzeige']) && $params['anzeige']) {
            $where
                ->isNotNull('t4.termin_id');
        }

        // tage
        if (isset($params['tage']) && !empty($params['tage'])) {
            $where->in('t3.datum_wochentag', $params['tage']);
        }

        return $where;
    }

    public function fetchTermin(array $params = [], array $groupBy = ['t3.datum_id', 't4.termin_id'], string $order = 't3.datum_datum ASC, t4.termin_zeit_start ASC, t4.termin_zeit_ende ASC, t4.termin_ansicht DESC, t4.termin_sortierung ASC'): ResultSet
    {
        $initConfig = $this->getMyInitConfig();

        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([
            'datum_id'    => 'datum_id',
            'datum_datum' => 'datum_datum',
        ]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_id'                      => 'termin_id',
                'termin_status'                  => 'termin_status',
                'termin_datum_start'             => 'termin_datum_start',
                'termin_datum_ende'              => 'termin_datum_ende',
                'termin_zeit_start'              => 'termin_zeit_start',
                'termin_zeit_ende'               => 'termin_zeit_ende',
                'termin_zeit_ganztags'           => 'termin_zeit_ganztags',
                'termin_betreff'                 => 'termin_betreff',
                'termin_text'                    => 'termin_text',
                'termin_kategorie'               => 'termin_kategorie',
                'termin_mitvon'                  => 'termin_mitvon',
                'termin_image'                   => 'termin_image',
                'termin_link'                    => 'termin_link',
                'termin_link_titel'              => 'termin_link_titel',
                'termin_link2'                    => 'termin_link2',
                'termin_link2_titel'              => 'termin_link2_titel',
                'termin_zeige_konflikt'          => 'termin_zeige_konflikt',
                'termin_zeige_einmalig'          => 'termin_zeige_einmalig',
                'termin_zeige_tagezuvor'         => 'termin_zeige_tagezuvor',
                'termin_aktiviere_drucken'       => 'termin_aktiviere_drucken',
                'termin_ansicht'                 => 'termin_ansicht',
                'termin_ist_konfliktrelevant'    => 'termin_ist_konfliktrelevant',
                'termin_notiz'                   => 'termin_notiz',
                'termin_erstellt_am'             => 'termin_erstellt_am',
                'termin_aktualisiert_am'         => 'termin_aktualisiert_am',
                'termin_aktualisiert_am_trigger' => 'termin_aktualisiert_am_trigger',
                '_is_new'                       => new Sql\Expression('IF(? > DATEDIFF(CURDATE(), termin_erstellt_am), 1, 0)', $initConfig['considered_as_new']),
                '_is_updated'                   => new Sql\Expression('IF(? > DATEDIFF(CURDATE(), termin_aktualisiert_am_trigger), 1, 0)', $initConfig['considered_as_updated']),
                '_anzahl_tage'                   => new Sql\Expression('DATEDIFF(termin_datum_ende, termin_datum_start) + 1'),
                '_tag_start_uts'                 => new Sql\Expression('UNIX_TIMESTAMP(DATE_FORMAT(termin_datum_start, "%Y-%m-%d 00:00:00"))'),
                '_tag_ende_uts'                  => new Sql\Expression('UNIX_TIMESTAMP(DATE_FORMAT(termin_datum_ende, "%Y-%m-%d 00:00:00"))'),
                '_konflikt'                      => new Sql\Expression(
                    "(
                        SELECT
                                GROUP_CONCAT(DISTINCT `c1`.`termin_status`, '---', `c1`.`termin_betreff`, '---', `c1`.`termin_mitvon`, '---', `c1`.`termin_id`, '---', `c1`.`termin_datum_start`, '---', `c1`.`termin_datum_ende` ORDER BY `c1`.`termin_betreff` SEPARATOR '+++')
                        FROM
                                `tajo1_termin` AS `c1`
                        WHERE
                                `c1`.`termin_ist_konfliktrelevant` = 1 AND
                                `c1`.`termin_ist_geloescht` = 0 AND
                                `c1`.`termin_id` <> `t4`.`termin_id` AND 
                                (
                                        (TIMESTAMP(`t4`.`termin_datum_start`, `t4`.`termin_zeit_start`) BETWEEN TIMESTAMP(`c1`.`termin_datum_start`,`c1`.`termin_zeit_start`) AND TIMESTAMP(`c1`.`termin_datum_ende`,`c1`.`termin_zeit_ende`)) 
                                        OR
                                        (TIMESTAMP(`t4`.`termin_datum_ende`, `t4`.`termin_zeit_ende`) BETWEEN TIMESTAMP(`c1`.`termin_datum_start`,`c1`.`termin_zeit_start`) AND TIMESTAMP(`c1`.`termin_datum_ende`,`c1`.`termin_zeit_ende`))
                                        OR
                                        (TIMESTAMP(`c1`.`termin_datum_start`, `c1`.`termin_zeit_start`) BETWEEN TIMESTAMP(`t4`.`termin_datum_start`,`t4`.`termin_zeit_start`) AND TIMESTAMP(`t4`.`termin_datum_ende`,`t4`.`termin_zeit_ende`)) 
                                        OR
                                        (TIMESTAMP(`c1`.`termin_datum_ende`, `c1`.`termin_zeit_ende`) BETWEEN TIMESTAMP(`t4`.`termin_datum_start`,`t4`.`termin_zeit_start`) AND TIMESTAMP(`t4`.`termin_datum_ende`,`t4`.`termin_zeit_ende`))
                                )
                    )"
                ),
                '_fehlbuchung'                   => new Sql\Expression(
                    "(
                        SELECT
                                GROUP_CONCAT(DISTINCT `c1`.`termin_status`, '---', `c1`.`termin_betreff`, '---', `c1`.`termin_mitvon`, '---', `c1`.`termin_id`, '---', `c1`.`termin_datum_start`, '---', `c1`.`termin_datum_ende` ORDER BY `c1`.`termin_betreff` SEPARATOR '+++')
                        FROM
                                `tajo1_termin` AS `c1`
                        WHERE
                                `c1`.`termin_ist_konfliktrelevant` = 1 AND
                                `c1`.`termin_ist_geloescht` = 0 AND
                                `c1`.`termin_id` <> `t4`.`termin_id` AND 
                                (
                                        (TIMESTAMP(`t4`.`termin_datum_start`, `t4`.`termin_zeit_start`) BETWEEN TIMESTAMP(`c1`.`termin_datum_start`,`c1`.`termin_zeit_start`) AND TIMESTAMP(`c1`.`termin_datum_ende`,`c1`.`termin_zeit_ende`)) 
                                        OR
                                        (TIMESTAMP(`t4`.`termin_datum_ende`, `t4`.`termin_zeit_ende`) BETWEEN TIMESTAMP(`c1`.`termin_datum_start`,`c1`.`termin_zeit_start`) AND TIMESTAMP(`c1`.`termin_datum_ende`,`c1`.`termin_zeit_ende`))
                                        OR
                                        (TIMESTAMP(`c1`.`termin_datum_start`, `c1`.`termin_zeit_start`) BETWEEN TIMESTAMP(`t4`.`termin_datum_start`,`t4`.`termin_zeit_start`) AND TIMESTAMP(`t4`.`termin_datum_ende`,`t4`.`termin_zeit_ende`)) 
                                        OR
                                        (TIMESTAMP(`c1`.`termin_datum_ende`, `c1`.`termin_zeit_ende`) BETWEEN TIMESTAMP(`t4`.`termin_datum_start`,`t4`.`termin_zeit_start`) AND TIMESTAMP(`t4`.`termin_datum_ende`,`t4`.`termin_zeit_ende`))
                                ) AND 
                                (
                                    `c1`.`termin_status` = " . $this->dbRunner->getDb()->getPlatform()->quoteValue(TerminStatusEnum::WARNUNG->value) . " AND
                                    (
                                        `c1`.`termin_mitvon` IS NULL 
                                        OR `c1`.`termin_mitvon` = '' 
                                        OR `t4`.`termin_mitvon` LIKE CONCAT('%',`c1`.`termin_mitvon`,'%')
                                    )
                                )
                    )"
                ),
            ],
            Sql\Select::JOIN_LEFT
        );

        $select->group($groupBy);
        $select->order($order);
        $select->where($this->getWhereCondition($params));

        // limit
        if (!empty($params['limit'])) {
            $select->limit($params['limit']);
        }

        // echo $select->getSqlString($this->dbRunner->getDb()->platform);
        // die;

        return $this->fetch($select, false);
    }

    public function fetchMitvon(array $params = [], string $order = 'termin_mitvon ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_mitvon' => 'termin_mitvon',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->group('t4.termin_mitvon');
        $select->order($order);
        $select->where($this->getWhereCondition($params));
        $select->where
            ->isNotNull('t4.termin_mitvon')
            ->notEqualTo('t4.termin_mitvon', '');

        //        ddd($select->getSqlString($this->dbRunner->getDb()->platform));

        return $this->fetch($select, false);
    }

    public function fetchKategorie(array $params = [], string $order = 'termin_kategorie ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_kategorie' => 'termin_kategorie',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->group('t4.termin_kategorie');
        $select->order($order);
        $select->where($this->getWhereCondition($params));
        $select->where
            ->isNotNull('t4.termin_kategorie')
            ->notEqualTo('t4.termin_kategorie', '');

        return $this->fetch($select, false);
    }

    public function fetchBetreff(array $params = [], string $order = 'termin_betreff ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_betreff' => 'termin_betreff',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->group('t4.termin_betreff');
        $select->order($order);
        $select->where($this->getWhereCondition($params));
        $select->where
            ->isNotNull('t4.termin_betreff')
            ->notEqualTo('t4.termin_betreff', '');

        return $this->fetch($select, false);
    }

    public function fetchLink(array $params = [], string $order = 'termin_link ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_link' => 'termin_link',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->group('t4.termin_link');
        $select->order($order);
        $select->where($this->getWhereCondition($params));
        $select->where
            ->isNotNull('t4.termin_link')
            ->notEqualTo('t4.termin_link', '');

        return $this->fetch($select, false);
    }

    public function fetchLinkTitel(array $params = [], string $order = 'termin_link_titel ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_link_titel' => 'termin_link_titel',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->group('t4.termin_link_titel');
        $select->order($order);
        $select->where($this->getWhereCondition($params));
        $select->where
            ->isNotNull('t4.termin_link_titel')
            ->notEqualTo('t4.termin_link_titel', '');

        return $this->fetch($select, false);
    }

    public function fetchImage(array $params = [], string $order = 'termin_image ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t3' => 'tajo1_datum']);
        $select->columns([
            new Sql\Expression('
            CONCAT(
                `t4`.`termin_image`, 
                IF(`t5`.`media_anzeige` IS NOT NULL AND `t5`.`media_anzeige` <> "", 
                    CONCAT(" -> ", `t5`.`media_anzeige`), 
                    IF(`t5`.`media_name` IS NOT NULL AND `t5`.`media_name` <> "", 
                        CONCAT(" -> ", `t5`.`media_name`),
                         ""
                    )
                )
            )'),
        ]);
        $select->join(
            ['t4' => new Sql\Expression('(SELECT `t1`.*,`t2`.`datum_id` FROM `tajo1_lnk_datum_termin` AS `t2` LEFT JOIN `tajo1_termin` AS `t1` ON `t1`.`termin_id` = `t2`.`termin_id`)')],
            $this->getTerminJoinCondition($params),
            [
                'termin_image' => 'termin_image',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->join(
            ['t5' => 'tajo1_media'],
            new Sql\Predicate\Expression('CONCAT("/media/", `t5`.`media_id`) = `t4`.`termin_image`'),
            [
                'media_name'    => 'media_name',
                'media_anzeige' => 'media_anzeige',
            ],
            Sql\Select::JOIN_LEFT
        );
        $select->group('t4.termin_image');
        $select->order($order);
        $select->where($this->getWhereCondition($params));
        $select->where
            ->isNotNull('t4.termin_image')
            ->notEqualTo('t4.termin_image', '');

        //        echo $select->getSqlString($this->dbRunner->getDb()->platform);
        //        ddd("STOP");

        return $this->fetch($select, false);
    }
}
