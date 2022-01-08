<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Fsizer extends AbstractDb
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ){
        parent::__construct($context);
    }
    /**
     * Initialize fsizer
     */
    protected function _construct()
    {
        $this->_init('fsizer', 'fsizer_id');
    }

}
