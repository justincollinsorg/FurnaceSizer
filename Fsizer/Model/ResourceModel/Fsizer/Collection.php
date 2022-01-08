<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Model\ResourceModel\Fsizer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var IdFieldName
     */
    protected $_idFieldName = 'fsizer_id';
    /**
     * @var EventPrefix
     */
    protected $_eventPrefix = 'ecommerce121_fsizer_post';
    /**
     * @var EventObject
     */
    protected $_eventObject = 'post_collection';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ecommerce121\Fsizer\Model\Fsizer', 'Ecommerce121\Fsizer\Model\ResourceModel\Fsizer');
    }
}
