<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Controller\Adminhtml\Index;

use Ecommerce121\Fsizer\Controller\Adminhtml\Index\Delete;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $fsizerRepositoryInterface = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerRepository');
        $context = $objectManager->getObject('Magento\Backend\App\Action\Context');
        $this->object = new Delete($context,$fsizerRepositoryInterface);
    }

    public function testControllerDelete(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'execute'));
        $this->assertEquals(class_parents(Delete::class), class_parents($this->object));
    }
}
