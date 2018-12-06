<?php

namespace Sibirix\TextContent\Controller;

use Sibirix\Base\MvcManager;
use Sibirix\Catalog\Controller\ListController;
use Sibirix\Catalog\Model\ProductService;
use Sibirix\Main\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Sibirix\TextContent\Model\Actions\ActionsService;

class ActionsController extends AbstractController {

    /**
     * @var ActionsService
     */
    private $actionsService;

    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var \CMain
     */
    private $bxApp;

    /**
     * ActionsController constructor.
     * @param ActionsService $actionsService
     * @param ProductService $productService
     * @param \CMain $bxApp
     */
    public function __construct(ActionsService $actionsService, ProductService $productService, \CMain $bxApp) {
        $this->actionsService = $actionsService;
        $this->productService = $productService;

        $this->bxApp = $bxApp;
    }

    /**
     * @return array|ViewModel
     * @throws \Exception
     */
    public function indexAction() {
        $this->bxApp->SetPageProperty('page-type','actions-list');

        $this->bxApp->SetTitle('Акции');
        $this->bxApp->AddChainItem('Акции');

        $this->seo()->forIBlock(IB_ACTIONS);

        $page       = $this->params()->fromRoute('page', 1);
        $pageEntity = $this->actionsService->getListInfo($page);

        $result = new ViewModel([
            'pageEntity'  => $pageEntity,
            'bxApp' => $this->bxApp,
        ]);

        return $result;
    }

    /**
     * @return ViewModel
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function detailAction() {
        $code                    = $this->params()->fromRoute('code');
        $item                    = $this->actionsService->getDetail($code);

        if (empty($item)) {
            return $this->notFoundAction();
        }

        $xmlIdsProductsInActions = $item->getProductsInAction();
        $page                    = $this->params()->fromRoute('page', 1);
        $pageEntity              = null;
        $paginator               = null;

        $this->bxApp->AddChainItem(
            "Акции",
            MvcManager::url('actions::actions', [])
        );
        $this->bxApp->AddChainItem($item->getName());

        if ($xmlIdsProductsInActions) {
            $pageEntity = $this->productService->getProductsInAction($xmlIdsProductsInActions, $page);
        }

        $this->seo()->forElement(IB_ACTIONS, $item->getId());

        $this->openGraph()->setTitle($item->getName());
        $this->openGraph()->setDescription($item->getPreviewText());
        $this->openGraph()->setImage($item->getPreviewPicture()['SRC']);
        $this->openGraph()->setUrl($this->getRequest()->getRequestUri());

        $result = new ViewModel([
            'code'       => $code,
            'item'       => $item,
            'pageEntity' => $pageEntity,
        ]);

        if ($pageEntity) {
            $listAction = $this->forward()->dispatch(ListController::class, [
                'action'          => 'list',
                'pageEntity'      => $pageEntity,
                'paginatorParams' => [
                    'query'         => [],
                    'route'         => 'actions::actions/detail/detail-with-page',
                    'routeParams'   => [
                        'code' => $code,
                    ],
                ]
            ]);
            $result->addChild($listAction, 'listAction');
        }

        $this->bxApp->SetPageProperty('page-type','actions-detail');
        $this->bxApp->SetTitle($item->getName());

        return $result;
    }
}