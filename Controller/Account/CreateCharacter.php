<?php

namespace Blizzard\Warcraft\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;
use Magento\Framework\Controller\Result\RedirectFactory;

class CreateCharacter extends Action
{
    protected $customerSession;
    protected $warcraftFactory;
    protected $warcraftResource;
    protected $resultRedirectFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        WarcraftFactory $warcraftFactory,
        WarcraftResource $warcraftResource,
        RedirectFactory $resultRedirectFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->warcraftFactory = $warcraftFactory;
        $this->warcraftResource = $warcraftResource;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();

            $character = $this->warcraftFactory->create();
            $warcraftResource = $this->warcraftResource;
            $this->warcraftResource->load($character, $customerId, 'customer_id');

            $data = [
                'customer_id' => $customerId,
                'level' => 1,
                'experience' => 0,
                'promotion' => 'None yet !',
                'rank' => 'New player'
            ];

            if (!$character->getId()) {

                $connection = $warcraftResource->getConnection();
                $connection->insert($warcraftResource->getMainTable(), $data);
                $this->messageManager->addSuccessMessage(__('Character created successfully.'));
            } else {
                $this->messageManager->addErrorMessage(__('Character already exists for this customer.'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('You must be logged in to create a character.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('warcraft/account/index');
        return $resultRedirect;
    }
}
