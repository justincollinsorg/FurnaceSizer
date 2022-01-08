<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Adminhtml\Index;

use Ecommerce121\Fsizer\Controller\Adminhtml\Index\Edit;
use PHPUnit\Framework\TestCase;

class EditTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $context = $objectManager->getObject('Magento\Backend\App\Action\Context');
        $pageFactory = $objectManager->getObject('Magento\Framework\View\Result\PageFactory');
        $fsizerFactory = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerFactory');
        $registry = $objectManager->getObject('Magento\Framework\Registry');
        $this->object = new Edit($context,$pageFactory,$fsizerFactory,$registry);
    }

    public function testControllerEdit(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(Edit::class), class_parents($this->object));
    }
}
