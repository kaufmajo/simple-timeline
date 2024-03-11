<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\AbstractRepository;
use App\Model\DbRunnerInterface;
use App\Model\Entity\EntityInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql;
use Laminas\Hydrator\HydratorInterface;

class MediaRepository extends AbstractRepository implements MediaRepositoryInterface
{
    public function __construct(DbRunnerInterface $dbRunner, HydratorInterface $hydrator, MediaEntity $prototype)
    {
        parent::__construct($dbRunner, $hydrator, $prototype);
    }

    public function refreshEntity(MediaEntityInterface &$mediaEntity)
    {
        $mediaEntity = $this->findEntityById($mediaEntity->getEntityId());
    }

    public function findEntityById(int $entityId): ?MediaEntityInterface
    {
        return $this->findMediaById($entityId);
    }

    public function findMediaById(int $id): null|MediaEntityInterface|EntityInterface
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t1' => 'tajo1_media']);

        // where
        $select->where
            ->equalTo('t1.media_id', $id);

        // execute
        $entity = $this->findFirst($select);

        return $this->mapReferences($entity);
    }

    public function mapReferences(?EntityInterface $entity): ?EntityInterface
    {
        return $entity;
    }

    public function fetchMedia(array $params = [], string $order = 'media_aktualisiert_am DESC, media_id DESC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t1' => 'tajo1_media']);
        $select->columns([
            'media_id'              => 'media_id',
            'media_parent_id'       => 'media_parent_id',
            'media_name'            => 'media_name',
            'media_groesse'         => 'media_groesse',
            'media_mimetype'        => 'media_mimetype',
            'media_von'             => 'media_von',
            'media_bis'             => 'media_bis',
            'media_anzeige'         => 'media_anzeige',
            'media_hash'            => 'media_hash',
            'media_tag'             => 'media_tag',
            'media_privat'          => 'media_privat',
            'media_erstellt_am'     => 'media_erstellt_am',
            'media_aktualisiert_am' => 'media_aktualisiert_am',
            '_gueltig'              => new Sql\Expression('IF(CURDATE() BETWEEN `t1`.`media_von` AND `t1`.`media_bis`, 1, 0)'),
            '_hatVersion'           => new Sql\Expression('(SELECT COUNT(`c1`.`media_id`) FROM `tajo1_media` AS `c1` WHERE `c1`.`media_parent_id` = `t1`.`media_id`)'),
        ]);
        $select->join(
            ['t2' => 'tajo1_termin'],
            new Sql\Predicate\Expression('REGEXP_REPLACE(t2.termin_link,"/media/([0-9]+)","\\\\1") = t1.media_id OR REGEXP_REPLACE(t2.termin_image,"/media/([0-9]+)","\\\\1") = t1.media_id'),
            [
                'termin_id',
            ],
            Sql\Join::JOIN_LEFT
        );
        $select->group('t1.media_id');
        $select->order($order);

        // params
        if (isset($params['id']) && ! empty($params['id'])) {
            $select->where
                ->equalTo('t1.media_id', $params['id']);
        }

        // parent
        if (isset($params['parent']) && ! empty($params['parent'])) {
            $select->where
                ->equalTo('t1.media_parent_id', $params['parent']);
        } else {
            $select->where
                ->isNull('t1.media_parent_id');
        }

        // von
        if (isset($params['von']) && ! empty($params['von'])) {
            $select->where
                ->nest()
                ->expression('`t1`.`media_bis` >= ?', $params['von'])
                ->or
                ->expression('`t1`.`media_von` >= ?', $params['von'])
                ->unnest();
        }

        // bis
        if (isset($params['bis']) && ! empty($params['bis'])) {
            $select->where
                ->nest()
                ->expression('`t1`.`media_bis` <= ?', $params['bis'])
                ->or
                ->expression('`t1`.`media_von` <= ?', $params['bis'])
                ->unnest();
        }

        // suchtext
        if (isset($params['suchtext']) && ! empty($params['suchtext'])) {
            $whereLikeSearch = $this->dbRunner->whereLikeSearchWithSqlObject($params['suchtext'], [
                ['column' => 't1.media_id', 'search' => '%[value]%'],
                ['column' => 't1.media_name', 'search' => '%[value]%'],
                ['column' => 't1.media_anzeige', 'search' => '%[value]%'],
                ['column' => 't1.media_tag', 'search' => '%[value]%'],
            ]);

            if ($whereLikeSearch) {
                $select->where->addPredicates($whereLikeSearch);
            }
        }

        // tag
        if (isset($params['tag']) && ! empty($params['tag'])) {
            $select->where
                ->equalTo('t1.media_tag', $params['tag']);
        }

        // privat
        if (isset($params['privat'])) {
            $select->where
                ->equalTo('t1.media_privat', $params['privat']);
        }

        // limit
        if (isset($params['limit']) && ! empty($params['limit'])) {
            $select->limit($params['limit']);
        }

        return $this->fetch($select);
    }

    public function fetchTag(array $params = [], string $order = 'media_tag ASC'): ResultSet
    {
        $sql    = new Sql\Sql($this->dbRunner->getDb());
        $select = $sql->select(['t1' => 'tajo1_media']);
        $select->columns([
            'media_tag' => 'media_tag',
        ]);
        $select->group('t1.media_tag');
        $select->order($order);
        $select->where
            ->isNull('t1.media_parent_id');

        // params
        // label
        if (isset($params['tag']) && ! empty($params['tag'])) {
            $select->where
                ->equalTo('t1.media_tag', $params['tag']);
        }

        return $this->fetch($select);
    }
}
