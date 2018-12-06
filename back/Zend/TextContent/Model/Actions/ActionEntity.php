<?php

namespace Sibirix\TextContent\Model\Actions;

use Sibirix\Base\Model\Row;


/**
 * Class ActionEntity
 * @package Sibirix\TextContent\Model\Actions
 */
class ActionEntity extends Row {

    /**
     * Получение id
     * @return mixed
     */
    public function getId() {
        return $this->ID;
    }

    /**
     * @return mixed
     */
    public function getIBlockId() {
        return $this->IBLOCK_ID;
    }

    /**
     * Получение имени
     * @return mixed
     */
    public function getName() {
        return $this->NAME;
    }

    /**
     * Получение кода
     * @return mixed
     */
    public function getCode() {
        return $this->CODE;
    }

    /**
     * Получение картинки для анонса
     * @return mixed
     */
    public function getPreviewPicture() {
        return $this->PREVIEW_PICTURE;
    }

    public function getDetailPictureAlt():string {
        $image = $this->getDetailPicture();
        if (!is_array($image)) {
            return $this->getName();
        }

        return !empty($image['FILE_ALT']) ? $image['FILE_ALT'] : $this->getName();
    }

    public function getDetailPictureTitle():string {
        $image = $this->getDetailPicture();
        if (!is_array($image)) {
            return $this->getName();
        }

        return !empty($image['FILE_TITLE']) ? $image['FILE_TITLE'] : $this->getName();
    }

    public function getPreviewPictureAlt():string {
        $image = $this->getPreviewPicture();
        if (!is_array($image)) {
            return $this->getName();
        }

        return !empty($image['FILE_ALT']) ? $image['FILE_ALT'] : $this->getName();
    }

    public function getPreviewPictureTitle():string {
        $image = $this->getPreviewPicture();
        if (!is_array($image)) {
            return $this->getName();
        }

        return !empty($image['FILE_TITLE']) ? $image['FILE_TITLE'] : $this->getName();
    }

    /**
     * Получение детальной картинки
     * @return mixed
     */
    public function getDetailPicture() {
        return $this->DETAIL_PICTURE;
    }

    /**
     * Получение получение даты начала акции
     * @return mixed
     */
    public function getDateStartAction() {
        $filter = new \Zend\Filter\StringToLower();

        return $filter->filter(FormatDate('d F Y', MakeTimeStamp($this->PROPERTY_DATE_START_ACTION)));
    }


    public function getDateEndAction() {
        $filter = new \Zend\Filter\StringToLower();

        return $filter->filter(FormatDate('d F Y', MakeTimeStamp($this->PROPERTY_DATE_END_ACTION)));
    }

    /**
     * получение даты начала акции без года
     * @return mixed
     */
    public function getDateStartActionWithoutYear() {
        $filter = new \Zend\Filter\StringToLower();

        return $filter->filter(FormatDate('d F', MakeTimeStamp($this->PROPERTY_DATE_START_ACTION)));
    }

    /**
     * получение даты окончания акции без года
     * @return mixed
     */
    public function getDateEndActionWithoutYear() {
        $filter = new \Zend\Filter\StringToLower();

        return $filter->filter(FormatDate('d F', MakeTimeStamp($this->PROPERTY_DATE_END_ACTION)));
    }

    /**
     * Получение описания для анонса
     * @return mixed
     */
    public function getPreviewText() {
        return $this->PREVIEW_TEXT;
    }

    /**
     * Получение детального описания
     * @return mixed
     */
    public function getDetailText() {
        return $this->DETAIL_TEXT;
    }

    /**
     * Получение значения пользовательского поля "купить в магазине"
     * @return mixed
     */
    public function getPropertyInShop() {
        return $this->PROPERTY_BUY_IN_SHOP;
    }

    /**
     * Получение значения пользовательского поля "купить в интернет-магазине"
     * @return mixed
     */
    public function getPropertyInOnlineShop() {
        return $this->PROPERTY_BUY_IN_ONLINE_SHOP;
    }

    /**
     * Получение XML_ID продуктов, относящихся к акции
     * @return mixed
     */
    public function getProductsInAction() {
        return $this->PROPERTY_PRODUCT_IN_ACTION;
    }
}
