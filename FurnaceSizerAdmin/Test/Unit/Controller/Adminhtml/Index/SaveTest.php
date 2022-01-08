<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Adminhtml\Index;

use Ecommerce121\Fsizer\Controller\Adminhtml\Index\Save;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $context = $objectManager->getObject('Magento\Backend\App\Action\Context');
        $resultPageFactory = $objectManager->getObject('Magento\Framework\View\Result\PageFactory');
        $fsizerRepository = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerRepository');
        $fsizerFactory = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerFactory');
        $messageManagerInterface = $objectManager->getObject('Magento\Framework\Message\Manager');
        $sessionFactory = $this->getMockBuilder('Magento\Backend\Model\SessionFactory')
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $this->object = new Save($context,$resultPageFactory,$fsizerRepository,$fsizerFactory,$messageManagerInterface,$sessionFactory);
    }

    public function testSave(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(Save::class), class_parents($this->object));
    }
}
