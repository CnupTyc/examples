<?php

namespace Sibirix\TextContent\Model\Actions;

use Sibirix\Base\Iblock\Wrap;
use Sibirix\Main\RepositoryInterface;

class ActionsRepository implements RepositoryInterface {

    /**
     * @return \Sibirix\Base\Iblock\Wrap
     */
    public function getList() {
        return Wrap::instance(IB_ACTIONS)
            ->select([
                'ID',
                'NAME',
                'CODE',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
                'PROPERTY_DATE_START_ACTION',
                'PROPERTY_DATE_END_ACTION',
                'PROPERTY_BUY_IN_ONLINE_SHOP',
                'PROPERTY_BUY_IN_SHOP',
                'PROPERTY_PRODUCT_IN_ACTION',
                'PROPERTY_SHOW_IN_MAIN',
            ])
            ->setEntityClass(ActionEntity::class);
    }

    /**
     * @param $entity
     * @return void
     */
    public function save($entity) {}

    /**
     * @param $primary
     * @return void
     */
    public function delete($primary) {}
}