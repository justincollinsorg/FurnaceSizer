<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Adminhtml\Index;

use Ecommerce121\Fsizer\Controller\Adminhtml\Index\Index;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $context = $objectManager->getObject('Magento\Backend\App\Action\Context');
        $resultPageFactory = $objectManager->getObject('Magento\Framework\View\Result\PageFactory');
        $this->object = new Index($context,$resultPageFactory);
    }

    public function testIndex(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(Index::class), class_parents($this->object));
    }
}
