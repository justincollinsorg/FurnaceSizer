<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Test\Unit\Ui\Listing\Column;

use Ecommerce121\Fsizer\Ui\Component\Listing\Column\BlockActions;
use PHPUnit\Framework\TestCase;

class BlockActionsTest extends TestCase
{
    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $contextInterface = $this->getMockBuilder('Magento\Framework\View\Element\UiComponent\Context')
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();
        $uiComponentFactory = $objectManager->getObject('Magento\Framework\View\Element\UiComponentFactory');
        $urlInterface = $objectManager->getObject('Magento\Framework\Url');
        $this->object = new BlockActions( 
            $contextInterface,
            $uiComponentFactory,
            $urlInterface,
            $components = [],
            $data = []
            );
    }

    public function testBlockActions(): void
    {
        $this->assertEquals(true, method_exists($this->object, 'prepareDataSource'));
        $this->assertEquals(class_parents(BlockActions::class), class_parents($this->object));
    }
}
