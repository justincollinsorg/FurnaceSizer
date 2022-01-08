<?php
/**
 * Copyright © JustinCollins.org All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ecommerce121\FurnaceSizer\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Category;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\CategoryFactory;

/**
 * Gets current products Categories
 */
class CategoryLogicFs extends Template implements ArgumentInterface
{
    /**
     * @var layerResolver - for method getCurrentCategory()
     */
    private $layerResolver;
    /**
     * @var Fsizer
     */
    public $fsizer;
    /**
     * @var DefaultFsizerId
     */
    public $defaultFsizerId = 1;
    /**
     * @var Children
     */
    public $_children;

    /**
     * @param Registry $registry
     * @param Category $categoryModel
     * @param Context  $context
     * @param Resolver $layerResolver
     * @param FsizerRepositoryInterface $fsizerRepositoryInterface
     * @param CategoryFactory $categoryFactory
     * @param Category $categoryHelper
     */
    public function __construct(
        Registry $registry,
        Category $categoryModel,
        Template\Context $context,
        Resolver $layerResolver,
        FsizerRepositoryInterface $fsizerRepositoryInterface,
        CategoryFactory $categoryFactory,
        Category $categoryHelper,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->categoryModel = $categoryModel;
        $this->fsizer = $fsizerRepositoryInterface;
        parent::__construct($context, $data);
        $this->layerResolver = $layerResolver;
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryHelper = $categoryHelper;
    }

    /**
     * Gets current products Categories
     *
     * @return Category[]
     */
    public function getProductCatagory()
    {
        $product = $this->registry->registry('current_product');
        $categories = $product->getCategoryIds(); /*will return category ids array*/
        $arr =[];
        foreach ($categories as $category) {
            $cat = $this->categoryModel->load($category);
            // $arr[] = $cat->getId() . ": " . $cat->getName();
            $arr[] = $cat->getName();
        }
        return $arr;
    }
    /**
     * Gets Product Catgory by ID
     *
     * @return Category[]
     */
    public function getProductCatagoryById($id)
    {
        $product = $this->registry->registry('current_product');
        $categories = $product->getCategoryIds(); /*will return category ids array*/
        $arr =[];
        foreach ($categories as $category) {
            $cat = $this->categoryModel->load($category);
            $arr[] = $cat->getName();
        }
        return $this->categoryModel->load($id);;
    }
    /**
     * Gets Product Catgory ID's
     *
     * @return CategoryIds[]
     */
    public function getProductCatagoryIds()
    {
        $product = $this->registry->registry('current_product');
        $categories = $product->getCategoryIds(); /*will return category ids array*/
        return $categories;
    }

    /**
     * Gets current products
     *
     * @return Product Object
     */
    public function getCurrentProduct()
    {
        $product = $this->registry->registry('current_product');
        return $product;
    }
    /**
     * Gets current category from page different than product
     *
     * @return Category Object
     */
    public function getCurrentCategory()
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }
    /**
     * Gets current category Name page different than product
     *
     * @return Category Name (String)
     */
    public function getCurrentCategoryName()
    {
        return $this->layerResolver->get()->getCurrentCategory()->getName();
    }
    /**
     * Is FurnaceSizer Visible
     *
     * @return bool
     */
    public function isShowFurnaceSizer()
    {
        return $this->layerResolver->get()->getCurrentCategory()->getShowForm();
    }
    /**
     * Is FurnaceSizer inheritance Disabled
     *
     * @return bool
     */
    public function isDisableFsizerInheritance()
    {
        return $this->layerResolver->get()->getCurrentCategory()->getDisableSubcategoryInheritance();
    }
    /**
     * Is FurnaceSizer Visiblilty Overriden to Show
     *
     * @return bool
     */
    public function isForceShowFsizer()
    {
        return $this->layerResolver->get()->getCurrentCategory()->getBypassDisableSubcategoryInheritance();
    }
    /**
     * Get Select Fsizer Id
     *
     * @return Fsizer Select id
     */
    public function getSelectFsizerId()
    {

        return $this->layerResolver->get()->getCurrentCategory()->getSelectFsizer();
    }
    /**
     * Get Fsizer Id
     *
     * @return Fsizer id
     */
    public function getFsizerId()
    {

        return $this->layerResolver->get()->getCurrentCategory()->getShowFormId();
    }
    /**
     * Get Fsizer Row by Id
     *
     * @param int $id
     *
     * @return array Fsizer row
     */
    public function getFsizerRowById($id)
    {
        return $this->fsizer->getById($id);
    }
    /**
     * Get Fsizer Description
     *
     * @param int $id
     *
     * @return Fsizer Description
     */
    public function getFsizerDescriptionById($id)
    {
        return $this->fsizer->getById($id)->getDescription();
    }
    /**
     * Get Fsizer Html by Id
     *
     * @param int $id
     *
     * @return Fsizer Block Html
     */
    public function getFsizerHtmlById($id)
    {
        if ($id != "displayNone") {
            return $this->fsizer->getById($id)->getContent();
        }
        return "HtmlDisplayNone";
    }
    /**
     * Get Default Fsizer Id
     *
     * @param  int $id
     *
     * @return Default Fsizer Id
     */
    public function getDefaultFsizerId()
    {
        return $this->defaultFsizerId;
    }
    /**
     * Is Fsizer Visiblilty Determined to Show
     *
     * @return bool
     */
    public function isDetermineShow()
    {
        if ($this->isShowFurnaceSizer()) {
            return 1;
        } else {
            return $this->isParentShowFurnaceSizer();
        }

    }
    /**
     * Is Fsizer Parent Visiblilty Show
     *
     * @return bool
     */
    public function isParentShowFurnaceSizer()
    {
        if ($this->isShowFurnaceSizer() == "0") {
            return false;
        }
        $parentIds = $this->getParentCategoryIds();
        foreach ($parentIds as $pid) {
            if ($this->getCategoryById($pid)->getShowForm() != "" &&
                $this->getCategoryById($pid)->getShowForm() == true) {
                return true;
            }
        }
    }
    /**
     * Get Parent Category Ids
     *
     * @return array Ids
     */
    public function getParentCategoryIds()
    {
        $array = array();
        $recursivelyFoundCategories = $this->getAllCategoryNames();
        $categories = $this->layerResolver->get()->getCurrentCategory()->getParentCategories();
        foreach ($categories as $category) {
            if (in_array($category->getName(), $recursivelyFoundCategories)) {
                if ($this->layerResolver->get()->getCurrentCategory()->getLevel() != $category->getLevel()) {
                    $array[] = $category->getId();
                }
            }
        }
        return array_reverse($array);
    }
    /**
     * Get Parent Category Names
     *
     * @return array Names
     */
    public function getParentCategoryNames()
    {
        $array = array();
        $recursivelyFoundCategories = $this->getAllCategoryNames();
        $categories = $this->layerResolver->get()->getCurrentCategory()->getParentCategories();
        foreach ($categories as $category) {
            if (in_array($category->getName(), $recursivelyFoundCategories)) {
                 $array[] = $category->getName();
            }
        }
        return array_reverse($array);
    }
    /**
     * Get Category by Id
     *
     * @param int $id
     *
     * @return Category
     */
    public function getCategoryById($id)
    {
        $categoryId = $id;
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category;
    }
    /**
     * Get Next Parent Category Id
     *
     * @return int Id
     */
    public function getNextParentCategoriesFsizerId()
    {
        $parentIds = $this->getParentCategoryIds();
        foreach ($parentIds as $pid) {
            $this->getCategoryById($pid)->getName();
            if ($this->getCategoryById($pid)->getShowForm()) {
                return $this->getCategoryById($pid)->getSelectFsizer();
            }
        }
    }
    /**
     * Is Inherited to Show
     *
     * @return bool
     */
    public function isInheritedToShow()
    {
        $parentIds = $this->getParentCategoryIds();
        foreach ($parentIds as $pid) {
            if ($this->getCategoryById($pid)->getShowForm()) {
                return true;
            } else {
                if ($this->getCategoryById($pid)->getDisableSubcategoryInheritance == true) {
                    // will return false unless returned true
                } else {
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * Determine Next Fsizer Id
     *
     * @return Id
     */
    public function determineNextFsizerId()
    {
        if ($this->layerResolver->get()->getCurrentCategory()->getSelectFsizer()) {
            return $this->layerResolver->get()->getCurrentCategory()->getSelectFsizer();
        } else {
            $parentIds = $this->getParentCategoryIds();
            foreach ($parentIds as $pid) {
                if ($this->getCategoryById($pid)->getDisableSubcategoryInheritance()) {
                    return 0;
                } elseif ($this->getCategoryById($pid)->getShowForm()) {
                    if ($this->getCategoryById($pid)->getSelectFsizer() > 1) {
                        return $this->getCategoryById($pid)->getSelectFsizer();
                    } else {
                        return $this->defaultFsizerId;
                    }
                }
            }
            return $this->defaultFsizerId;
        }
    }
    /**
     * Get Children Names
     *
     * @param array $children
     *
     * @return array Names
     */
    public function getChildrenNames($children)
    {
        foreach ($children as $child) {
            $this->_children[] = $child->getName();
            if (count($child->getChildren()) > 0) {
                $this->getChildrenNames($child->getChildren());
            }
        }
    }
    /**
     * Get All Category Names
     *
     * @return array Names
     */
    public function getAllCategoryNames()
    {
        $categories = $this->_categoryHelper->getStoreCategories();
        $this->_children = array();
        foreach ($categories as $category) {
            $this->_children[] = $category->getName();
            if (count($category->getChildren()) > 0) {
                $this->getChildrenNames($category->getChildren());
            }
        }
        return $this->_children;
    }
}
