<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ecommerce121_Fsizer::test_main';
    /**
     * @var FsizerRepositoryInterface
     */
    private $fsizerRepository;
    /**
     * @param Context $context
     * @param FsizerRepositoryInterface $fsizerRepository
     */
    public function __construct(
        Context $context,
        FsizerRepositoryInterface $fsizerRepository
    ) {
        $this->fsizerRepository = $fsizerRepository;
        parent::__construct($context);
    }
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('fsizer_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $this->fsizerRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The Furnace Sizer has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['fsizer_id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Furnace Sizer to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
