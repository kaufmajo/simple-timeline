<?php

declare(strict_types=1);

namespace App\Model\Media;

use App\Model\AbstractCommand;
use App\Model\DbRunnerInterface;
use App\Model\Entity\EntityInterface;
use App\Provider\MediaStorageProvider;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use Exception;
use Laminas\Db\Sql;
use Laminas\Diactoros\UploadedFile;
use Laminas\Hydrator\HydratorInterface;
use RuntimeException;

use function rename;

class MediaCommand extends AbstractCommand implements MediaCommandInterface
{
    use MediaRepositoryAwareTrait;

    public function __construct(DbRunnerInterface $dbRunner, HydratorInterface $hydrator)
    {
        parent::__construct($dbRunner, $hydrator);
    }

    public function getEntityData(EntityInterface $entity): array
    {
        return $this->hydrator->extract($entity);
    }

    public function insertMedia(MediaEntityInterface $mediaEntity): int
    {
        // process
        $insert = new Sql\Insert('tajo1_media');
        $insert->values($this->getEntityData($mediaEntity));

        $affectedRows = $this->insert($insert, $generatedValue);

        // set entity id
        $mediaEntity->setEntityId((int) $generatedValue);

        $mediaEntity->setLastEntityAction('insert');

        return $affectedRows;
    }

    public function updateMedia(MediaEntityInterface $mediaEntity): int
    {
        if (! $mediaEntity->getMediaId()) {
            throw new RuntimeException('Cannot update entity; missing identifier');
        }

        // process
        $update = new Sql\Update('tajo1_media');
        $update->where(['media_id = ?' => $mediaEntity->getMediaId()]);
        $update->set($this->getEntityData($mediaEntity));

        $affectedRows = $this->update($update);

        $mediaEntity->setLastEntityAction('update');

        return $affectedRows;
    }

    public function deleteMedia(MediaEntityInterface $mediaEntity): int
    {
        if (! $mediaEntity->getMediaId()) {
            throw new RuntimeException('Cannot delete entity; missing identifier');
        }

        // process
        $delete = new Sql\Delete('tajo1_media');
        $delete->where(['media_id = ?' => $mediaEntity->getMediaId()]);

        return $this->delete($delete);
    }

    public function saveMedia(MediaEntityInterface $mediaEntity): int
    {
        if (! $mediaEntity->getMediaId()) {
            return $this->insertMedia($mediaEntity);
        } else {
            return $this->updateMedia($mediaEntity);
        }
    }

    /**
     * @throws Exception
     */
    public function storeMedia(MediaEntityInterface $mediaEntity, UploadedFile $uploadedFile): void
    {
        // user selects a file
        if (0 === $uploadedFile->getError()) {
            // create a version of the current media
            $this->storeMediaVersion($mediaEntity);

            // set file properties for new media
            $mediaEntity->setMediaName($uploadedFile->getClientFilename());
            $mediaEntity->setMediaGroesse($uploadedFile->getSize());
            $mediaEntity->setMediaMimetype($uploadedFile->getClientMediaType()); // 'application/octet-stream'

            // save new media entity
            $this->saveMedia($mediaEntity);
            // refresh mediaEntity to get new created hash value from DB
            $this->getMediaRepository()->refreshEntity($mediaEntity);

            // finally ... move new file to storage
            $uploadedFile->moveTo(
                MediaStorageProvider::createDirPath($mediaEntity)::getFilePath($mediaEntity)
            );
        } else {
            // save new media entity
            $this->saveMedia($mediaEntity);
        }
    }

    /**
     * @throws Exception
     */
    public function storeMediaVersion(MediaEntityInterface $mediaEntity): void
    {
        $mediaVersionEntity = $this->getMediaVersionEntity($mediaEntity);

        // save version => but only if it's an update = media is already in storage
        if (MediaStorageProvider::isInStorage($mediaEntity)) {
            // save previous media entity
            $this->saveMedia($mediaVersionEntity);
            // refresh mediaEntity to get new created hash value from DB
            $this->getMediaRepository()->refreshEntity($mediaVersionEntity);

            rename(
                MediaStorageProvider::getFilePath($mediaEntity),
                MediaStorageProvider::createDirPath($mediaVersionEntity)::getFilePath($mediaVersionEntity)
            );
        }
    }

    public function getMediaVersionEntity(MediaEntityInterface $mediaEntity): MediaEntityInterface
    {
        // reset properties
        $mediaVersionEntity = new MediaEntity();
        $mediaVersionEntity->setMediaId(null);
        $mediaVersionEntity->setMediaPrivat(1);
        $mediaVersionEntity->setMediaVon('1999-01-01');
        $mediaVersionEntity->setMediaBis('2100-01-01');
        $mediaVersionEntity->setMediaTag(null);
        // assign values from current mediaEntity
        $mediaVersionEntity->setMediaParentId($mediaEntity->getMediaId());
        $mediaVersionEntity->setMediaName($mediaEntity->getMediaName());
        $mediaVersionEntity->setMediaAnzeige($mediaEntity->getMediaAnzeige());
        $mediaVersionEntity->setMediaGroesse($mediaEntity->getMediaGroesse());
        $mediaVersionEntity->setMediaMimetype($mediaEntity->getMediaMimetype());

        return $mediaVersionEntity;
    }
}
