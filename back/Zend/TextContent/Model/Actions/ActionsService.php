<?php

namespace Sibirix\TextContent\Model\Actions;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Type\Date;
use Sibirix\Base\Model\Iblock;
use Sibirix\Base\Seo;
use Sibirix\Catalog\Model\Product\ProductRepository;
use Sibirix\Catalog\Model\ProductService;
use Sibirix\Main\Collection\Collection;
use Sibirix\Main\Model\PageEntity;

class ActionsService {
    /**
     * @var ActionsRepository
     */
    private $actionsRepository;
    /**
     * @var ProductRepository
     */
    private $productsRepository;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * NewsService constructor.
     * @param ActionsRepository $actionsRepository
     * @param ProductRepository $productsRepository
     * @param ProductService $productService
     */
    public function __construct(
        ActionsRepository $actionsRepository,
        ProductRepository $productsRepository,
        ProductService $productService
    ) {
        $this->actionsRepository  = $actionsRepository;
        $this->productsRepository = $productsRepository;
        $this->productService     = $productService;
    }

    /**
     * @param int $page
     * @return PageEntity
     */
    public function getListInfo($page = 1) {
        $wrap = $this->actionsRepository->getList();

        $items = $wrap
            ->orderBy(['PROPERTY_DATE_START_ACTION' => "DESC"], true)
            ->page($page, 15)
            ->fetch();

        $this->fillPictures($items);

        return new PageEntity($items, $wrap->getPageData());
    }

    /**
     * @param $items
     *
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    protected function fillPictures(&$items) {
        $items = Iblock::loadListPictures($items, ['PREVIEW_PICTURE', 'DETAIL_PICTURE']);
        $cSeo = new Seo();
        /** @var ActionEntity $item */
        foreach ($items as $item) {
            $seoValues = $cSeo->getSeoElement($item->getIBlockId(), $item->getId());
            $cSeo->setElementPreviewPictureAttributes($item, $seoValues);
            $cSeo->setElementDetailPictureAttributes($item, $seoValues);
        }
    }

    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getMainPageList($limit = 5) {
        $items = $this->actionsRepository->getList()
            ->where(['=PROPERTY_SHOW_IN_MAIN' => true])
            ->limit($limit)
            ->orderBy(['PROPERTY_DATE_START_ACTION' => "DESC"], true)
            ->toCollection();

        if ($items->count()) {
            Iblock::loadListPictures($items, ['PREVIEW_PICTURE']);
            return $items;
        }

        $items = $this->actionsRepository->getList()
            ->orderBy(['PROPERTY_DATE_START_ACTION' => "DESC"], true)
            ->limit($limit)
            ->toCollection();

        Iblock::loadListPictures($items, ['PREVIEW_PICTURE']);

        return $items;
    }

    /**
     * @param $code
     * @return null|ActionEntity
     */
    public function getDetail($code): ?ActionEntity{
        $items = $this->actionsRepository
            ->getList()
            ->where(['=CODE' => $code])
            ->fetch();
        if (empty($items)) {
            return null;
        }

        $this->fillPictures($items);

        return reset($items);
    }

    /**
     * @param $idsProducts
     * @return null|\Sibirix\Main\Collection\Collection
     */
    public function getProductsInAction($idsProducts) {
        $items = $this->productsRepository
            ->getList()
            ->where(['=XML_ID' => $idsProducts])
            ->toCollection();

        if (empty($items)) {
            return null;
        }

        $this->productService->fillProducts($items);

        return $items;
    }

    /**
     * @param $arFields
     *
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function beforeElementUpdate(&$arFields) {
        if ((int)$arFields['IBLOCK_ID'] !== IB_ACTIONS) {
            return true;
        }

        $rsProp = PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => IB_ACTIONS,
                'CODE' => ['DATE_START_ACTION', 'DATE_END_ACTION']
            ],
            'select' => ['ID', 'CODE']
        ]);
        $props = [];
        while ($prop = $rsProp->fetch()) {
            $props[$prop['CODE']] = $prop['ID'];
        }

        $propValues = $arFields['PROPERTY_VALUES'] ?? [];
        $dateStart  = $propValues[$props['DATE_START_ACTION']] ?? false;
        $dateEnd    = $propValues[$props['DATE_END_ACTION']] ?? false;
        if (!$dateStart && !$dateEnd) {
            return true;
        }

        $dateStart = new Date(reset($dateStart)['VALUE'], 'd.m.Y');
        $dateEnd   = new Date(reset($dateEnd)['VALUE'], 'd.m.Y');

        if ($dateStart <= $dateEnd) {
            return true;
        }

        global $APPLICATION;
        $APPLICATION->throwException("Дата начала акции не должна быть позже даты окончания акции");
        return false;
    }
}