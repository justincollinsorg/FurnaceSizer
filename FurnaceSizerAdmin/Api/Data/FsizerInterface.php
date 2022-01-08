<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Api\Data;

interface FsizerInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FSIZER_ID      = 'fsizer_id';
    const CONTENT       = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    const DESCRIPTION    = 'description';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getFsizerId();

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getContent();

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getCreationTime();

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getUpdateTime();

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getIsActive();

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set ID
     *
     * @param string $content
     * @return FsizerInterface
     */
    public function setContent($content);

    /**
     * Set ID
     *
     * @param int $active
     * @return FsizerInterface
     */
    public function setIsActive($active);

    /**
     * Set Description
     *
     * @param string $description
     * @return FsizerInterface
     */
    public function setDescription($description);
}
