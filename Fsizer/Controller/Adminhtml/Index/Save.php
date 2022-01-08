<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\Fsizer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\Controller\Result\Redirect as RedirectAlias;
use Magento\Framework\View\Result\PageFactory;
use Ecommerce121\Fsizer\Model\FsizerFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\Model\SessionFactory;

class Save extends Action
{

    /**
     * constants
     */
    const FORM_CODE = 'fsizer_form';
    const FIELD_CODE = 'fsizer_id';

    /**
     * @var ManagerInterface
     */
    private $messageManagerInterface;

    /**
     * @var FsizerRepositoryInterface
     */
    private $fsizerRepository;

    /**
     * @var FsizerFactory
     */
    private $fsizerFactory;

    /**
     * @var SessionFactory
     */
    private $sessionFactory;
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param FsizerRepositoryInterface $fsizerRepository
     * @param FsizerFactory $fsizerFactory
     * @param ManagerInterface $messageManagerInterface
     * @param SessionFactory $sessionFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        FsizerRepositoryInterface $fsizerRepository,
        FsizerFactory $fsizerFactory,
        ManagerInterface $messageManagerInterface,
        SessionFactory $sessionFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->fsizerRepository = $fsizerRepository;
        $this->fsizerFactory = $fsizerFactory;
        $this->messageManagerInterface = $messageManagerInterface;
        $this->sessionFactory = $sessionFactory;
        parent::__construct($context);
    }

    /**
     * This function used to save rule by admin
     *
     * @return ResponseInterfaceAlias|RedirectAlias|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if data sent
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam(self::FIELD_CODE);
            if (!$id) {
                $data['fsizer_id'] = null;
                $model = $this->fsizerFactory->create();
            } else {
                $model = $this->fsizerRepository->getById($id);
            }
            $filteredData = [];
            foreach ($data as $key => $value) {
                $filteredData[$key] = $value;
            }

            $model->setData($filteredData);

            try {
                $this->fsizerRepository->save($model);
                // display success message
                $this->messageManagerInterface->addSuccessMessage('You saved the Furnace Sizer.');
                // clear previously saved data from session
                $session = $this->sessionFactory->create();
                $session->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['fsizer_id' => $model->getFsizerId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManagerInterface->addErrorMessage($e->getMessage());
                // save data in session
                $session = $this->sessionFactory->create();
                $session->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['fsizer_id' => $this->getRequest()->getParam('fsizer_id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
