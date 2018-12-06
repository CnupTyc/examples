<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die ();

use \Sibirix\Base\MvcManager;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$allSections = $arResult["ALL_SECTIONS"];
$categories  = $arResult["CATEGORY"];
$subMenu     = $arResult['FIRST_SECTION_SUB_MENU'];
$style       = [
    'red',
    'blue-medium-dark',
    'blue-medium',
    'blue',
];
?>
<div class="header-nav-container">
    <div class="header-nav js-header-nav">
        <div>
            <ul class="menu">
                <li class="js-item first-item" data-submenu="category-all">
                    <a></a>
                </li>
            </ul>
        </div>
        <div class="scroll js-scroll">
            <ul class="menu">
                <span class="line"></span>
                <? foreach ($categories as $categoryNum => $item): ?>

                    <li class="js-item" data-submenu="category-<?= $categoryNum; ?>">
                        <a href="<?= MvcManager::url('catalog::catalog/section-with-page',
                            ['sectionCode' => $item['CODE']]) ?>"><?= $item["NAME"]
                            ?></a>
                    </li>

                <? endforeach; ?>
            </ul>
        </div>
        <div style="display: none !important;">
            <div data-submenu="category-all" class="submenu js-submenu">
                <? if ($subMenu): ?>
                    <div class="submenu-links">
                        <? foreach($subMenu as $i => $item): ?>
                            <a class="<?= $style[$i] ?>" href="<?= $item['PROPERTY_VALUE_VALUE'] ?>">
                                <?= $item['NAME'] ?></a>
                        <? endforeach; ?>
                    </div>
                <? endif; ?>

                <div class="submenu-content">
                    <div class="grid-row">
                        <?
                        $countInColumn = ceil(count($allSections) / 3);
                        $itemsInColumns = array_chunk($allSections, $countInColumn);
                        foreach($itemsInColumns as $itemsInColumn):?>
                            <div class="col-4">
                                <ul class="nav big">

                                    <? foreach($itemsInColumn as $item): ?>
                                        <li><a href="<?= MvcManager::url('catalog::catalog/section-with-page',
                                                ['sectionCode' => $item['CODE']]) ?>"><?= $item["NAME"]
                                                ?></a></li>
                                    <? endforeach; ?>

                                </ul>
                            </div>
                        <? endforeach; ?>

                    </div>
                </div>
            </div>
            <? foreach ($categories as $i => $category): ?>
                <div data-submenu="category-<?= $i ?>" class="submenu js-submenu">
                    <div class="submenu-content">
                        <div class="grid-row">

                            <?
                            $countInColumn = ceil(count($category["SUBSECTIONS"]) / 2);
                            $itemsInColumns = array_chunk($category["SUBSECTIONS"], $countInColumn);
                            $numberColumn = 0;

                            if (empty($itemsInColumns)): ?>
                                <div class="col-3">
                                    <ul class="nav">
                                        <li><a>Раздел ещё не заполнен</a></li>
                                    </ul>
                                </div>
                            <? else: ?>

                                <? foreach ($itemsInColumns as $numberColumn => $itemsInColumn):?>
                                    <div class="col-3">
                                        <?= $numberColumn == 0 ? "<div class='nav-title''>Категории</div>" : '' ?>
                                        <ul class="nav <?= $numberColumn != 0 ? 'title-offset' : '' ?>">

                                            <? foreach ($itemsInColumn as $item): ?>
                                                <li><a href="<?= MvcManager::url('catalog::catalog/section-with-page',
                                                        ['sectionCode' => $item['CODE']]) ?>"><?= $item["NAME"]
                                                        ?></a></li>
                                            <? endforeach; ?>
                                        </ul>
                                    </div>
                                <? endforeach; ?>

                                <? if (!empty($category['BRANDS'])): ?>
                                    <div class="col-3 col-md-2">
                                        <div class="nav-title">Бренды</div>
                                        <ul class="nav">

                                            <? foreach ($category['BRANDS'] as $brand): ?>
                                                <li><a href="<?= $brand['UF_LINK'] ?>"><?= $brand["UF_NAME"] ?></a></li>
                                            <? endforeach; ?>

                                            <li><a class="grey" href="javascript:void(0);">Все бренды</a></li>
                                        </ul>
                                    </div>
                                <? endif; ?>

                                <?
                                if($category['PRODUCT']): ?>
                                    <div class="col-3 col-md-4">
                                        <?echo \Sibirix\Base\MvcManager::partial(
                                            'sibirix/catalog/_partials/product-card.phtml',
                                            ['item' => $category['PRODUCT']]
                                        ); ?>
                                    </div>
                                <?
                                elseif ($category['BANNER']): ?>
                                    <div class="col-3 col-md-4">
                                        <a href="<?= $category['BANNER']['UF_LINK_BANNER'] ?>">
                                            <img src="<?= \Sibirix\Base\Resizer::resizeImage(
                                                $category['BANNER']['UF_PICTURE_BANNER'],
                                                'TOP_MENU_BANNER'
                                            )?>"
                                        </a>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
    <a class="action-link" href="<?= \Sibirix\Base\MvcManager::url('actions::actions', []); ?>">Акции</a>
</div>