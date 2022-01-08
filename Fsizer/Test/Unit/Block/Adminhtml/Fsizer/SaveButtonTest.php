<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Block\Adminhtml\Fsizer;

use Ecommerce121\Fsizer\Block\Adminhtml\Fsizer\SaveButton;
use PHPUnit\Framework\TestCase;

class SaveButtonTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $fsizerRepositoryInterface = $objectManager->getObject('Ecommerce121\Fsizer\Model\FsizerRepository');
        $context = $objectManager->getObject('Magento\Backend\Block\Widget\Context');
        $this->object = new SaveButton($context,$fsizerRepositoryInterface);
    }

    public function testSaveButton(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'getButtonData'));
        $this->assertEquals(class_parents(SaveButton::class), class_parents($this->object));
    }
}
