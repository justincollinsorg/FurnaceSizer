<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Ecommerce121\Fsizer\Api\Data\FsizerInterface;

class Fsizer extends AbstractModel implements IdentityInterface, FsizerInterface
{
    /**
     * @const CACHE_TAG
     */
    const CACHE_TAG = 'ecommerce121_fsizer_post';
    /**
     * @var CacheTag
     */
    protected $_cacheTag = 'ecommerce121_fsizer_post';
    /**
     * @var EventPrefix
     */
    protected $_eventPrefix = 'ecommerce121_fsizer_post';
    /**
     * Initialize Model\ResourceModel\Fsizer
     */
    protected function _construct()
    {
        $this->_init('Ecommerce121\Fsizer\Model\ResourceModel\Fsizer');
    }
    /**
     * Get Identities
     *
     * @return Identities
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    /**
     * Get Default Values
     *
     * @return values
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
    /**
     * Get FSIZER_ID Value
     *
     * @return FSIZER_ID
     */
    public function getFsizerId()
    {
        return $this->getData(self::FSIZER_ID);
    }
    /**
     * Get CONTENT Value
     *
     * @return CONTENT
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }
    /**
     * Get CREATION_TIME Value
     *
     * @return CREATION_TIME
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }
    /**
     * Get UPDATE_TIME Value
     *
     * @return UPDATE_TIME
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }
    /**
     * Get Is Active Value
     *
     * @return IS_ACTIVE
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }
    /**
     * Get Description
     *
     * @return Description
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }
    /**
     * Set Content
     *
     * @param  string $content
     *
     * @return FsizerInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }
    /**
     * Set Is Active
     *
     * @param  int $active
     *
     * @return mixed
     */
    public function setIsActive($active)
    {
        return $this->setData(self::IS_ACTIVE, $active);
    }
    /**
     * Set Description
     *
     * @param  string $description
     *
     * @return mixed
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
}
