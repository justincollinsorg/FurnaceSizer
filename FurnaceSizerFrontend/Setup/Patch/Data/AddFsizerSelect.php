<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
declare (strict_types = 1);
namespace Ecommerce121\FurnaceSizer\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Category;

class AddFsizerSelect implements DataPatchInterface
{
    /**
     * constant
     */
    const ATTRIBUTE_CODE = 'select_fsizer';
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }
    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * This function is responsible for create feature attribute to category
     *
     * @return AddFeatureCategoryAttribute|void
     */
    public function apply()
    {
        try {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $eavSetup->addAttribute(Category::ENTITY, self::ATTRIBUTE_CODE, [
                'type'     => 'int',
                'label'    => 'Select Furnace Sizer',
                'input'    => 'select',
                'source'   => 'Ecommerce121\Fsizer\Model\Config\Source\FsizerOptionList',
                // 'source'   => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'visible'  => true,
                'default'  => '0',
                'required' => false,
                'global'   => ScopedAttributeInterface::SCOPE_STORE,
                'group'    => 'General Information',
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
