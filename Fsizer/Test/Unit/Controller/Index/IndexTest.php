<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Index;

use Ecommerce121\Fsizer\Controller\Index\Index;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $context = $objectManager->getObject('Magento\Framework\App\Action\Context');
        $pageFactory = $objectManager->getObject('Magento\Framework\View\Result\PageFactory');
        $fsizer = $this->getMockBuilder('Ecommerce121\Fsizer\Model\FsizerFactory')
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $resourceModelFsizer = $this->getMockBuilder('Ecommerce121\Fsizer\Model\ResourceModel\FsizerFactory')
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $collectionFactory = $objectManager->getObject('Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\CollectionFactory');
        $fsizerRepository = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerRepository');
        $fsizerInterface = $objectManager->getObject('Ecommerce121\Fsizer\Model\Fsizer');
        $searchCriteriaBuilder = $objectManager->getObject('Magento\Framework\Api\SearchCriteriaBuilder');
        $sortOrderBuilder = $objectManager->getObject('Magento\Framework\Api\SortOrderBuilder');
        $jsonResultFactory = $objectManager->getObject('Magento\Framework\Controller\Result\JsonFactory');
        $rawResultFactory = $objectManager->getObject('Magento\Framework\Controller\Result\RawFactory');
        $request = $objectManager->getObject('Magento\Framework\App\Request\Http');
        $blockFactory = $this->getMockBuilder('Magento\Cms\Model\BlockFactory')
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $blockGetter = $objectManager->getObject('Magento\Cms\Model\GetBlockByIdentifier');
        $storeManager = $objectManager->getObject('Magento\Store\Model\StoreManager');

        $this->object = new Index($context,$pageFactory,$fsizer,$resourceModelFsizer,$collectionFactory,$fsizerRepository,$fsizerInterface,$searchCriteriaBuilder,$sortOrderBuilder,$jsonResultFactory,$rawResultFactory,$request,$blockFactory,$blockGetter,$storeManager);
    }

    public function testIndex(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(Index::class), class_parents($this->object));
    }
}
