<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Adminhtml\Index;

use Ecommerce121\Fsizer\Controller\Adminhtml\Index\NewAction;
use PHPUnit\Framework\TestCase;

class NewActionTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $context = $objectManager->getObject('Magento\Backend\App\Action\Context');
        $someFactoryMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\ForwardFactory')
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $this->object = new NewAction($context, $someFactoryMock);
    }

    public function testNewAction(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(NewAction::class), class_parents($this->object));
    }
}
