<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Block\Adminhtml\Fsizer;

use Magento\Backend\Block\Widget\Context;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var FsizerRepositoryInterface
     */
    protected $fsizerRepository;

    /**
     * @param Context $context
     * @param FsizerRepositoryInterface $fsizerRepository
     */
    public function __construct(
        Context $context,
        FsizerRepositoryInterface $fsizerRepository
    ) {
        $this->context = $context;
        $this->fsizerRepository = $fsizerRepository;
    }

    /**
     * Return CMS page ID
     *
     * @return int|null
     */
    public function getPageId()
    {
        try {
            return $this->fsizerRepository->getById(
                $this->context->getRequest()->getParam('fsizer_id')
            )->getId();
        } catch (\Exception $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
