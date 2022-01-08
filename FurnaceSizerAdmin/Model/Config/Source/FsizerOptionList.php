<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\CollectionFactory;

class FsizerOptionList extends AbstractSource
{
    /**
     * @var $collectionFactory
     */
    private $collectionFactory;
    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * Get All Options
     *
     * @return options
     */
    public function getAllOptions()
    {
        $fsizerCollection = $this->collectionFactory->create()->load();
        $options = [];
        foreach ($fsizerCollection as $key => $fsizerRow) {
            $options[$key]['value'] = $fsizerRow->getFsizerId();
            $label = $fsizerRow->getDescription();
            $description = mb_strimwidth($fsizerRow->getDescription(), 0, 30, "...");
            $options[$key]['label'] = "[".$fsizerRow->getFsizerId() . "] " . $description;
        }
        $this->_options = $options;
        array_unshift($this->_options, ['value' => '', 'label' => __('Select System Block')]);
        $res = $this->_options;
        return $res;
    }
}
