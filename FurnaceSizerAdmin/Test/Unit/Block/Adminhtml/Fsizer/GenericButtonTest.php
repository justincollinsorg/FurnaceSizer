<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Block\Adminhtml\Fsizer;

use Ecommerce121\Fsizer\Block\Adminhtml\Fsizer\GenericButton;
use PHPUnit\Framework\TestCase;

class GenericButtonTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $fsizerRepositoryInterface = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerRepository');
        $context = $objectManager->getObject('Magento\Backend\Block\Widget\Context');
        $this->object = new GenericButton($context,$fsizerRepositoryInterface);
    }

    public function testGenericButton(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'getUrl'));
        $this->assertEquals(class_parents(GenericButton::class), class_parents($this->object));
    }
}
