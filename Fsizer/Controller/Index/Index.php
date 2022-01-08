<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Ecommerce121\Fsizer\Model\FsizerFactory;
use Ecommerce121\Fsizer\Model\ResourceModel\FsizerFactory as Fsizer;
use Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\CollectionFactory;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Ecommerce121\Fsizer\Api\Data\FsizerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Api\GetBlockByIdentifierInterface;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    private $_pageFactory;
    /**
     * @var FsizerFactory
     */
    private $fsizer;
    /**
     * @var FsizerRepositoryInterface
     */
    private $fsizerRepository;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Fsizer
     */
    private $resourceModelFsizer;
    /**
     * @var FsizerInterface
     */
    private $fsizerInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;
    /**
     * @var JsonResultFactory
     */
    protected $jsonResultFactory;
    /**
     * @var BlockGetter
     */
    private $blockGetter;
    /**
     * @var StoreManager
     */
    private $storeManager;
    /**
     * @var RawResultFactory
     */
    public $rawResultFactory;
    /**
     * @param Context                       $context
     * @param PageFactory                   $pageFactory
     * @param FsizerFactory                 $fsizer
     * @param Fsizer                        $resourceModelFsizer
     * @param CollectionFactory             $collectionFactory
     * @param FsizerRepositoryInterface     $fsizerRepository
     * @param FsizerInterface               $fsizerInterface
     * @param SearchCriteriaBuilder         $searchCriteriaBuilder
     * @param SortOrderBuilder              $sortOrderBuilder
     * @param JsonFactory                   $jsonResultFactory
     * @param RawFactory                    $rawResultFactory
     * @param RequestInterface              $request
     * @param BlockFactory                  $blockFactory
     * @param GetBlockByIdentifierInterface $blockGetter
     * @param StoreManagerInterface         $storeManager
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        FsizerFactory $fsizer,
        Fsizer $resourceModelFsizer,
        CollectionFactory $collectionFactory,
        FsizerRepositoryInterface $fsizerRepository,
        FsizerInterface $fsizerInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        JsonFactory $jsonResultFactory,
        RawFactory $rawResultFactory,
        RequestInterface $request,
        BlockFactory $blockFactory,
        GetBlockByIdentifierInterface $blockGetter,
        StoreManagerInterface $storeManager
    ) {
        $this->_pageFactory = $pageFactory;
        $this->fsizer = $fsizer;
        $this->resourceModelFsizer = $resourceModelFsizer;
        $this->collectionFactory = $collectionFactory;
        $this->fsizerRepository = $fsizerRepository;
        $this->fsizerInterface = $fsizerInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        // JSON AND HTTP
        $this->jsonResultFactory = $jsonResultFactory;
        $this->rawResultFactory = $rawResultFactory;
        $this->request = $request;
        // DATABASE
        $this->blockFactory = $blockFactory;
        $this->blockGetter  = $blockGetter;
        $this->storeManager  = $storeManager;
        return parent::__construct($context);
    }
        /**
         * Get Request
         *
         * @return request
         */
    public function getGetRequest()
    {
        return $this->request->getParam('id');//in Magento 2.*
    }
        /**
         * Execute
         *
         * @return result
         */
    public function execute()
    {
        
        $fsizer = $this->fsizer->create();
        $sortOrder = $this->sortOrderBuilder->setField('fsizer_id')->setDirection('DESC')->create();
        $this->searchCriteriaBuilder->setSortOrders([$sortOrder]);
        $builder = $this->searchCriteriaBuilder->addFilter('is_active', 1);
        $list =  $this->fsizerRepository->getList($builder->create())->getItems();
        $result = $this->rawResultFactory->create();
        $result->setHeader('Content-Type', 'text/xml');
        $result->setContents("");
        foreach ($list as $key => $item) {
            if ($item->getData()['fsizer_id'] == $this->getGetRequest()) {
                $html = $item->getData()['content'];
                $result->setContents($html);
            }
        }
        return $result;
    }
}
