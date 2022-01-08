<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\FurnaceSizer\Block\Adminhtml\Custom;

use Magento\Backend\Block\Template\Context;

class Tab extends \Magento\Backend\Block\Template
{
    /**
     * @var Template
     */
    protected $_template = 'catalog/category/tab.phtml';
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}
