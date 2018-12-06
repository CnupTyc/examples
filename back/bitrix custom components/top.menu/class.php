<?php

use Sibirix\Base\Iblock\Highload;
use Sibirix\Base\Iblock\SectionSelect;
use Sibirix\Base\Iblock\Wrap;
use Sibirix\Base\MvcManager;
use Sibirix\Location\Model\City\CityEntity;
use Sibirix\Location\Model\City\CityService;

/**
 *
 */
class SibirixTopMenuComponent extends \CBitrixComponent {

    public function onPrepareComponentParams($params) {
        $params['CACHE_TIME'] = isset($params['CACHE_TIME'])
            ? intval($params['CACHE_TIME'])
            : 3600000;

        return $params;
    }

    /*
     * Алгоритм:
     * -выбираются все активные непустые разделы(1),
     * -выбираются разделы для меню(2),
     * -выбираются категории для разделов меню(3),
     * -выбираются бренды для разделов меню (4),
     * -выбирается товар для каждого из разделов меню, если нет товара, то используется привязанный баннер(5)
     */
    public function executeComponent() {
        /** @var CityService $cityService */
        $cityService = MvcManager::getLocator()->get(CityService::class);
        /** @var CityEntity $cityEntity */
        $cityEntity = $cityService->getCurrent();

        if ($this->startResultCache(false, [$cityEntity->getId()])) {

            list(
                $allSections,
                $allSectionsSubMenu,
                $sections,
                $brandsIds,
                $productsIds,
                $bannersIds
            ) = $this->getSections();
            $categories   = $this->getCategories($sections);
            $brands       = !empty($brandsIds) ? $this->getBrands($brandsIds) : [];
            $products     = !empty($productsIds) ? $this->getProducts($productsIds) : [];
            $productsCard = $this->getProductsCard($products);
            $banners      = !empty($bannersIds) ? $this->getBanners($bannersIds) : [];
            $sections     = $this->getMergeList($sections, $categories, $brands, $productsCard, $banners);

            $this->arResult['CITY']                   = $cityEntity;
            $this->arResult['ALL_SECTIONS']           = $allSections;
            $this->arResult['CATEGORY']               = $sections;
            $this->arResult['FIRST_SECTION_SUB_MENU'] = $allSectionsSubMenu;

            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate('');
        }
    }

    /**
     * Метод выборки всех разделов и подразделов, разделов меню, их брендов и продуктов/баннеров
     * @return array|iterable
     */
    public function getSections() {
        $sections =  SectionSelect::instance(IB_PRODUCTS)
            ->select([
                'ID',
                'DEPTH_LEVEL',
                'NAME',
                'CODE',
                'UF_SHOW_MENU',
                'UF_SHOW_PRODUCT',
                'UF_BRANDS_IN_SECTION',
                'UF_SECTION_BANNER',
            ])
            ->orderBy(['SORT' => 'ASC'], true)
            ->calcCount(true, true)
            ->where([
                "ACTIVE" => "Y",
                "DEPTH_LEVEL" => 1,
            ])
            ->fetch();

        $result       = [];
        $brandsIds    = [];
        $showSections = [];
        $productsIds  = [];
        $bannersIds   = [];

        foreach ($sections as $section) {
            if ($section['ELEMENT_CNT'] != 0) {
                $result[] = $section;
                if ($section['UF_SHOW_MENU']) {
                    $showSections [] = $section;
                    $productsIds[] = $section["UF_SHOW_PRODUCT"];
                    $bannersIds[] = $section["UF_SECTION_BANNER"];
                    foreach ($section['UF_BRANDS_IN_SECTION'] as $brandId) {
                        $brandsIds [] = $brandId;
                    }
                }
            }
        }

        $allSectionsSubMenu = Wrap::instance(IB_SETTINGS)
            ->select([
                'ID',
                'NAME',
                'PROPERTY_VALUE',
            ])
            ->where([
                "SECTION_CODE" => 'SECTION_SUBMENU',
                "ACTIVE" => "Y",
            ])
            ->limit(4)
            ->fetch();

        return [
            $result,
            $allSectionsSubMenu,
            array_slice($showSections,0,5),
            $brandsIds,
            $productsIds,
            $bannersIds,
        ];
    }

    /**
     * Метод выборки всех подразделов для разделов, выводимых в главном меню
     * @param $sections
     * @return array
     */
    public function getCategories($sections) {
        $sectionsId = [];

        foreach ($sections as $section) {
            $sectionsId[] = $section['ID'];
        }

        $result = [];
        $categorys = SectionSelect::instance(IB_PRODUCTS)
            ->select([
                'ID',
                'DEPTH_LEVEL',
                'NAME',
                'SECTION_PAGE_URL',
            ])
            ->calcCount(true, true)
            ->where([
                "ACTIVE" => "Y",
                "DEPTH_LEVEL" => 2,
            ])
            ->fetch();

        foreach ($categorys as $category) {
            if ($category['ELEMENT_CNT'] != 0) {
                $result[] = $category;
            }
        }

        return $result;
    }

    /**
     * Метод выборки всех брендов для разделов, выводимых в главном меню
     * @param $brandsIds
     * @return array
     */
    public function getBrands($brandsIds) {
        return  Highload::instance(HL_B_HLBD_BRAND)
            ->where([
                'ID' => $brandsIds,
            ])
            ->fetch();
    }

    /**
     * Метод выборки всех продуктов, выводимых в главном меню
     * @param $productsIds
     * @return mixed
     */
    public function getProducts($productsIds) {
        return Wrap::instance(IB_PRODUCTS)
            ->where([
                'ID' => $productsIds,
            ])
            ->fetch();
    }

    /**
     * Функция заполнения карточек продуктов, выводимых в главном меню
     * @param $products
     * @return array
     */
    public function getProductsCard($products) {
        $productService = MvcManager::getLocator()->get(\Sibirix\Catalog\Model\ProductService::class);
        $productsCard = [];

        foreach ($products as $product) {
            $productsCard[] = $productService->getProductInSection($product['ID']);
        }

        return $productsCard;
    }

    /**
     * Метод получения баннеров для разделов, выводимых на главном меню
     * @param $bannersIds
     * @return array|bool
     */
    public function getBanners($bannersIds) {
        return Highload::instance(HL_B_HLBD_SECTIONBANNER)
            ->where([
                'ID' => $bannersIds,
            ])
            ->fetch();
    }

    /**
     * Метод для слияния всех полученных выборок
     * @param $sections
     * @param $categories
     * @param $brands
     * @param $products
     * @param $banners
     * @return mixed
     */
    public function getMergeList($sections, $categories, $brands, $products, $banners) {
        foreach ($sections as &$section) {
            $section['SUBSECTIONS'] = array_filter($categories, function ($obj) use ($section) {
                return $section['ID'] == $obj['IBLOCK_SECTION_ID'];
            });

            $section['BRANDS'] = array_filter($brands, function ($obj) use ($section) {
                return array_search($obj['ID'], $section['UF_BRANDS_IN_SECTION']) !== false;
            });

            $section['PRODUCT'] = reset(array_filter($products, function ($obj) use ($section) {
                return $section['UF_SHOW_PRODUCT'] == $obj->getId();
            }));

            $section['BANNER'] = reset(array_filter($banners, function ($obj) use ($section) {
                return $section['UF_SECTION_BANNER'] == $obj['ID'];
            }));
        }
        unset($section);

        return $sections;
    }
}