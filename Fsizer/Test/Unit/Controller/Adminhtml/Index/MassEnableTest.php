<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Adminhtml\Index;

use Ecommerce121\Fsizer\Controller\Adminhtml\Index\MassEnable;
use PHPUnit\Framework\TestCase;

class MassEnableTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $context = $objectManager->getObject('Magento\Backend\App\Action\Context');
        $filter = $objectManager->getObject('Magento\Ui\Component\MassAction\Filter');
        $collectionFactory = $objectManager->getObject('Ecommerce121\Fsizer\Model\ResourceModel\Fsizer\CollectionFactory');
        $this->object = new MassEnable($context,$filter,$collectionFactory);
    }

    public function testMassEnable(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(MassEnable::class), class_parents($this->object));
    }
}
