<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Hepsipay\Hepsipay\Model\ResourceModel\History;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Hepsipay\Hepsipay\Model\History', 'Hepsipay\Hepsipay\Model\ResourceModel\History');
    }
}
