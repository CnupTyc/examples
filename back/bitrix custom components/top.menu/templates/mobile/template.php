<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die ();

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

?>

<?

use Sibirix\Base\Settings;
use Sibirix\Base\MvcManager;
use Sibirix\Location\Model\City\CityEntity;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die ();

$allSections = $arResult["ALL_SECTIONS"];
$categories  = $arResult["CATEGORY"];
$subMenu     = $arResult['FIRST_SECTION_SUB_MENU'];
/** @var CityEntity $city */
$city        = $arResult['CITY'];
$style       = [
    'red',
    'blue-medium-dark',
    'blue-medium',
    'blue',
];
?>
    <div class="state js-state default">
        <div class="state-content">
            <ul class="nav">
                <li>
                    <a href="javascript:void(0);" class="link -not-implemented">
                        <span class="icon user"></span>
                        <span>Личный кабинет</span>
                    </a>
                    <a href="javascript:void(0);" class="link -not-implemented">
                        <span class="icon favorite"><span class="counter">3</span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);"
                       class="js-header-select-city">
                        Город&nbsp;&mdash; <span class="js-city-name"><?= $city->getName() ?></span>
                    </a>
                </li>

                <? //ВЫВОД РАЗНОЦВЕТНЫХ ПУНКТОВ?>
                <? foreach ($subMenu as $i => $item):?>
                    <li>
                        <a class="<?= $style[$i] ?>"
                           href="<?= $item['PROPERTY_VALUE_VALUE'] ?>">
                            <?= $item['NAME'] ?></a></li>
                <? endforeach; ?>

                <? //ВЫВОД ВСЕХ НЕПУСТЫХ РАЗДЕЛОВ?>

                <? foreach ($categories as $i => $section): ?>
                    <? if ($section['SUBSECTIONS']): ?>
                        <li class="nav-item"><a class="js-state-toggle" data-state="category-<?= $i; ?>"
                                                href="javascript:void(0);"><?= $section["NAME"] ?></a></li>
                    <? endif; ?>
                <? endforeach; ?>

                <li>
                    <a class="red" href="<?= MvcManager::url('actions::actions', []); ?>">Акции</a>
                </li>
            </ul>

            <? if (Settings::get("GENERAL_QUESTIONS")->getValue()): ?>
                <div class="row">
                    <a href="tel:<?= Settings::get("CONTACTS_GENERAL_QUESTIONS")
                        ->getClearValue('#\D+#') ?>"><?= Settings::get("GENERAL_QUESTIONS")->getValue(); ?></a><br>
                    <span class="link-label"><?= Settings::get("GENERAL_QUESTIONS")->getName(); ?></span>
                </div>
            <? endif; ?>

            <? if (Settings::get("INTERNET_SHOP")->getValue()): ?>
                <div class="row">
                    <a href="tel:<?= Settings::get("CONTACTS_INTERNET_SHOP")->getClearValue('#\D+#') ?>"
                       class="phone-link"><?= Settings::get("INTERNET_SHOP")->getValue(); ?></a><br>
                    <span class="link-label"><nobr><?= Settings::get("INTERNET_SHOP")->getName(); ?></nobr></span>
                </div>
            <? endif; ?>

            <a href="javascript:void(0);"
               data-fancybox
               data-type="ajax"
               data-src="<?= MvcManager::url('main::callback-popup') ?>"
               class="call-me-btn"
            >Перезвонить?</a><br>

            <? if (Settings::get("E_MAIL")->getValue()): ?>
                <a href="mailto:<?= Settings::get("E_MAIL")->getValue(); ?>" class="email">
                    <?= Settings::get("E_MAIL")->getValue(); ?></a>
            <? endif; ?>

            <? $APPLICATION->IncludeComponent(
                'sibirix:social.links',
                '.default',
                [],
                false
            ) ?>
        </div>
    </div>

<? //ВЫВОД ВСЕХ КАТЕГОРИЙ РАЗДЕЛА?>
<? foreach ($categories as $i => $section): ?>
    <? if ($section['SUBSECTIONS']): ?>
        <div class="state js-state" data-state="category-<?= $i; ?>">
            <div class="state-content">
                <a class="back-link js-state-toggle" href="javascript:void(0);">Назад</a>
                <div class="nav-title"><a href="<?= MvcManager::url('catalog::catalog/section-with-page',
                        ['sectionCode' => $section['CODE']]) ?>"><?= $section["NAME"]?></a></div>
                <ul class="nav">
                    <? foreach ($section['SUBSECTIONS'] as $subsection): ?>
                        <li class="nav-item"><a href="<?= MvcManager::url('catalog::catalog/section-with-page',
                                ['sectionCode' => $subsection['CODE']]) ?>"><?= $subsection["NAME"]?></a></li>
                    <? endforeach; ?>
                </ul>

                <? if (!empty($section['BRANDS'])): ?>
                    <div class="nav-title">Бренды</div>
                    <ul class="nav">

                        <? foreach ($section['BRANDS'] as $brand): ?>
                            <li class="nav-item"><a href="<?= $brand['UF_LINK'] ?>"><?= $brand["UF_NAME"] ?></a></li>
                        <? endforeach; ?>

                        <li class="nav-item"><a class="grey" href="javascript:void(0);">Все бренды</a></li>
                    </ul>
                <? endif; ?>

            </div>
        </div>
    <? endif; ?>
<? endforeach; ?>