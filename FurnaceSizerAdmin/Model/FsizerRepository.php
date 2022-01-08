<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Model;

use Magento\Framework\Api\SortOrder;
use Ecommerce121\Fsizer\Api\Data\FsizerSearchResultsInter;
use Magento\Framework\Api\SearchCriteriaInterface;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Ecommerce121\Fsizer\Api\Data\FsizerSearchResultsInterfaceFactory;
use Ecommerce121\Fsizer\Model\ResourceModel\Fsizer as ResourceFsizer;
use Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\CollectionFactory as FsizerCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Ecommerce121\Fsizer\Api\Data\FsizerInterface;
use Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\Collection;

class FsizerRepository implements FsizerRepositoryInterface
{
    /**
     * @var ResourceFsizer
     */
    protected $resource;

    /**
     * @var FsizerFactory
     */
    protected $blockFactory;

    /**
     * @var FsizerCollectionFactory
     */
    protected $blockCollectionFactory;

    /**
     * @var FsizerSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Magento\Cms\Api\Data\BlockInterfaceFactory
     */
    protected $dataBlockFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var FsizerFactory
     */
    private $fsizerFactory;
    /**
     * @var FsizerInterface
     */
    private $dataFsizerFactory;
    /**
     * @var Collection
     */
    private $fsizerkCollectionFactory;
    /**
     * @var FsizerCollectionFactory
     */
    private $fsizerCollectionFactory;

    /**
     * @param ResourceFsizer $resource
     * @param FsizerFactory $fsizerFactory
     * @param FsizerInterface $dataFsizerFactory
     * @param FsizerCollectionFactory $fsizerCollectionFactory
     * @param FsizerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceFsizer $resource,
        FsizerFactory $fsizerFactory,
        FsizerInterface $dataFsizerFactory,
        FsizerCollectionFactory $fsizerCollectionFactory,
        FsizerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->fsizerFactory = $fsizerFactory;
        $this->fsizerCollectionFactory = $fsizerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataFsizerFactory = $dataFsizerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Block data
     *
     * @param FsizerInterface $fsizer
     * @return Fsizer
     * @throws CouldNotSaveException
     */
    public function save(FsizerInterface $fsizer)
    {
        try {
            $this->resource->save($fsizer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $fsizer;
    }

    /**
     * Load Block data by given Block Identity
     *
     * @param string $blockId
     * @return Fsizer
     * @throws NoSuchEntityException
     */
    public function getById($blockId)
    {
        $block = $this->fsizerFactory->create();
        $this->resource->load($block, $blockId);
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('CMS Block with id "%1" does not exist.', $blockId));
        }
        return $block;
    }

    /**
     * Get Search Results
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return FsizerSearchResultsInter
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var $collection Collection */
        $collection = $this->fsizerCollectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * Add Filters to Search
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * Add Sort Order to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * Add Pagination to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * Build Search Result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return FsizerSearchResultsInter
     */
    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
    /**
     * Delete Block
     *
     * @param FsizerInterface $block
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(FsizerInterface $block)
    {
        try {
            $this->resource->delete($block);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }
    /**
     * Delete Block by given Block Identity
     *
     * @param string $blockId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($blockId)
    {
        if ($blockId != "1") {
            return $this->delete($this->getById($blockId));
        }
    }
}
