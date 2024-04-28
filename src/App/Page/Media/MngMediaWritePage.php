<?php

declare(strict_types=1);

namespace App\Page\Media;

use App\Enum;
use App\Model\Media\MediaEntity;
use App\Page\AbstractBasePage;
use App\Service\HelperService;
use App\Service\UrlpoolService;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaCommandAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use App\Traits\Entity\MediaEntityTrait;
use Exception;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Form\Form;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge_recursive;

class MngMediaWritePage extends AbstractBasePage
{
    use FormStorageAwareTrait;
    use MediaCommandAwareTrait;
    use MediaEntityTrait;
    use MediaRepositoryAwareTrait;

    public function insertAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // script will stop when ...
        HelperService::isPostMaxSizeReached();

        // param
        $mediaIdParam = (int) $request->getAttribute('p1');

        // init
        $myInitConfig    = $this->getMyInitConfig();
        $mediaCommand    = $this->getMediaCommand();
        $mediaRepository = $this->getMediaRepository();
        $mediaEntity     = $this->getMediaEntityById($mediaIdParam, Enum\ReturnEnum::NEW_ENTITY, new MediaEntity());

        // datalist data
        $tagData = $mediaRepository->fetchTag()->toArray();

        //form
        $mediaForm = $this->getMediaForm();

        // view
        $viewData = [
            'myInitConfig' => $myInitConfig,
            'mediaForm'    => $mediaForm,
            'mediaEntity'  => $mediaEntity,
            'redirectUrl'  => $request->getAttribute(UrlpoolService::class)->keep()->get(),
            'datalist'     => ['tag' => $tagData],
        ];

        if ('POST' !== $request->getMethod()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/media/mng-media-write-insert', $viewData));
        }

        $mediaForm->setData(array_merge_recursive($request->getParsedBody(), $request->getUploadedFiles()));

        // process
        if (! $mediaForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/media/mng-media-write-insert', $viewData));
        }

        $formData = $mediaForm->getData();

        $mediaEntity->exchangeArray($formData);

        $mediaCommand->storeMedia($mediaEntity, $formData['media_datei']);

        $this->flashMessages($request)->flash('info', 'default');

        return new RedirectResponse($request->getAttribute(UrlpoolService::class)->keep()->getUrlWithAnchor($mediaEntity->getMediaId()));
    }

    /**
     * @throws Exception
     */
    public function updateAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // script will stop when ...
        HelperService::isPostMaxSizeReached();

        // param
        $mediaIdParam = (int) $request->getAttribute('p1');

        // init
        $myInitConfig    = $this->getMyInitConfig();
        $mediaCommand    = $this->getMediaCommand();
        $mediaRepository = $this->getMediaRepository();

        // datalist data
        $tagData = $mediaRepository->fetchTag()->toArray();

        // mediaEntity
        $mediaEntity = $this->getMediaEntityById($mediaIdParam);

        //form
        $mediaForm = $this->getMediaForm();
        $mediaForm->setData($mediaEntity->getArrayCopy());
        $mediaForm->getInputFilter()->get('media_datei')->setRequired(false);

        // view
        $viewData = [
            'myInitConfig' => $myInitConfig,
            'mediaForm'    => $mediaForm,
            'mediaEntity'  => $mediaEntity,
            'redirectUrl'  => $request->getAttribute(UrlpoolService::class)->keep()->getUrlWithAnchor($mediaEntity->getMediaId()),
            'datalist'     => ['tag' => $tagData],
        ];

        if ('POST' !== $request->getMethod()) {
            return new HtmlResponse(
                $this
                    ->templateRenderer
                    ->render('app::mng/media/mng-media-write-update', $viewData)
            );
        }

        $mediaForm->setData(array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles()
        ));

        // process
        if (! $mediaForm->isValid()) {
            return new HtmlResponse(
                $this
                    ->templateRenderer
                    ->render('app::mng/media/mng-media-write-update', $viewData)
            );
        }

        $formData = $mediaForm->getData();

        $mediaEntity->exchangeArray($formData);

        $mediaCommand->storeMedia($mediaEntity, $formData['media_datei']);

        $this->flashMessages($request)->flash('info', 'default');

        return new RedirectResponse($request->getAttribute(UrlpoolService::class)->keep()->getUrlWithAnchor($mediaEntity->getMediaId()));
    }

    /**
     * @throws Exception
     */
    public function deleteAction(ServerRequestInterface $request): HtmlResponse|RedirectResponse
    {
        // param
        $mediaIdParam = (int) $request->getAttribute('p1');

        // init
        $myInitConfig = $this->getMyInitConfig();
        $mediaCommand = $this->getMediaCommand();
        $mediaEntity  = $this->getMediaEntityById($mediaIdParam);

        // view
        $viewData = [
            'myInitConfig' => $myInitConfig,
            'mediaEntity'  => $mediaEntity,
            'redirectUrl'  => $request->getAttribute(UrlpoolService::class)->keep()->getUrlWithAnchor($mediaEntity->getMediaId()),
        ];

        // ask for confirmation
        if ('POST' !== $request->getMethod()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/media/mng-media-write-delete', $viewData));
        }

        // redirect if confirmation is not given
        if (
            $mediaIdParam !== (int) $request->getParsedBody()['id'] ||
            ! isset($request->getParsedBody()['confirm']) ||
            'Löschen' !== $request->getParsedBody()['confirm']
        ) {
            return new RedirectResponse($request->getAttribute(UrlpoolService::class)->keep()->getUrlWithAnchor($mediaEntity->getMediaId()));
        }

        // ok ... now execute delete
        $mediaCommand->deleteMedia($mediaEntity);

        $this->flashMessages($request)->flash('info', 'default');

        return new RedirectResponse($request->getAttribute(UrlpoolService::class)->keep()->getUrlWithAnchor($mediaEntity->getMediaId()));
    }

    public function getMediaForm(): Form
    {
        /** @var Form $mediaForm */
        $mediaForm = $this->getForm('media-form');
        $mediaForm->setAttribute('method', 'POST');
        $mediaForm->setAttribute('enctype', 'multipart/form-data');

        return $mediaForm;
    }
}
