<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Ui\Component\Form;

use Ecommerce121\Fsizer\Ui\Component\Form\DataProvider;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $collectionFactory = $objectManager->getObject('Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\CollectionFactory');
        $filterPool = $objectManager->getObject('Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool');
        $this->object = new DataProvider( 
            $name="test",
            $primaryFieldName="test",
            $requestFieldName="test",
            $collectionFactory,
            $filterPool,
            $meta = [],
            $data = []
            );
    }

    public function testDataProvider(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'getData'));
        $this->assertEquals(class_parents(DataProvider::class), class_parents($this->object));
    }
}
