<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Ecommerce121\Fsizer\Api\Data\FsizerInterface;

interface FsizerRepositoryInterface
{
    /**
     * Save block.
     *
     * @param FsizerInterface $fsizer
     * @return FsizerInterface
     * @throws LocalizedException
     */
    public function save(FsizerInterface $fsizer);

    /**
     * Retrieve block.
     *
     * @param int $blockId
     * @return FsizerInterface
     * @throws LocalizedException
     */
    public function getById($blockId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return BlockSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param FsizerInterface $block
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(Data\FsizerInterface $block);

    /**
     * Delete block by ID.
     *
     * @param int $blockId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($blockId);
}
