<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface FsizerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return FsizerInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param FsizerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
