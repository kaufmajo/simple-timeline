<?php

declare(strict_types=1);

namespace App\Page\Media;

use App\Page\AbstractBasePage;
use App\Service\HelperService;
use App\Traits\Aware\FormStorageAwareTrait;
use App\Traits\Aware\MediaRepositoryAwareTrait;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Form\FormInterface;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;

class MngMediaReadPage extends AbstractBasePage
{
    use FormStorageAwareTrait;

    use MediaRepositoryAwareTrait;

    public function indexAction(ServerRequestInterface $request): HtmlResponse
    {
        $this->getUrlpoolService()->save();

        // init
        $myInitConfig = $this->getMyInitConfig();

        $mediaRepository = $this->getMediaRepository();

        // datalist data
        $tagData = $mediaRepository->fetchTag()->toArray();

        // form
        $mngMediaSearchForm = $this->getMediaSearchForm($request->getQueryParams());

        // view
        $viewData = [
            'myInitConfig'       => $myInitConfig,
            'mngMediaSearchForm' => $mngMediaSearchForm,
            'mediaArray'         => [],
            'datalist'           => array_merge($tagData),
        ];

        // check form
        if (empty($_GET) || !$mngMediaSearchForm->isValid()) {
            return new HtmlResponse($this->templateRenderer->render('app::mng/media/mng-media-read-index', $viewData));
        }

        // ...
        $formData = $mngMediaSearchForm->getData();

        $searchValues = $this->getMappedMediaSearchValues($formData);

        $viewData['searchValues'] = $searchValues;

        // fetch media
        $mediaResultSet = $mediaRepository->fetchMedia($searchValues);

        // set view data
        $viewData['mediaArray'] = $mediaResultSet->toArray();

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/media/mng-media-read-index', $viewData)
        );
    }

    public function versionAction(ServerRequestInterface $request): HtmlResponse
    {
        // param
        $mediaIdParam = (int) $request->getAttribute('p1');

        // init
        $myInitConfig    = $this->getMyInitConfig();
        $mediaRepository = $this->getMediaRepository();

        // view
        $viewData = [
            'myInitConfig' => $myInitConfig,
            'redirectUrl'  => $this->getUrlpoolService()->fragment(HelperService::getAnchorString($mediaIdParam))->get(),
        ];

        // fetch media
        $mediaResultSet = $mediaRepository->fetchMedia(['parent' => $mediaIdParam]);

        // set view data
        $viewData['mediaArray'] = $mediaResultSet->toArray();

        return new HtmlResponse(
            $this->templateRenderer->render('app::mng/media/mng-media-read-version', $viewData)
        );
    }

    public function getMediaSearchForm(array $params): FormInterface
    {
        $form = $this->getForm('media-mng-search-form');
        $form->setAttribute('method', 'GET');
        $form->setAttribute('action', '/manage/media-read');

        $form->setData($params);

        return $form;
    }

    public function getMappedMediaSearchValues(array $formData): array
    {
        $searchValues             = [];
        $searchValues['suchtext'] = $formData['search_suchtext'];

        return $searchValues;
    }
}
