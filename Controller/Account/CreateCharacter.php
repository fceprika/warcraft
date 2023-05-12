<?php

namespace Blizzard\Warcraft\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * Controller class for creating a character in the account section.
 */
class CreateCharacter extends Action
{
    /**
     * @var Session Customer session instance.
     */
    protected $customerSession;

    /**
     * @var WarcraftFactory Warcraft model factory instance.
     */
    protected $warcraftFactory;

    /**
     * @var WarcraftResource Warcraft resource model instance.
     */
    protected $warcraftResource;

    /**
     * @var RedirectFactory Redirect factory instance.
     */
    protected $resultRedirectFactory;

    /**
     * Constructor: Initializes the required dependencies.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param WarcraftFactory $warcraftFactory
     * @param WarcraftResource $warcraftResource
     * @param RedirectFactory $resultRedirectFactory
     */
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

    /**
     * Execute method for creating a character in the account section.
     *
     * @return \Magento\Framework\Controller\Result\Redirect Redirect to the account page.
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();

            $character = $this->warcraftFactory->create();
            $warcraftResource = $this->warcraftResource;
            $this->warcraftResource->load($character, $customerId, 'customer_id');

            $data = [
                'customer_id' => $customerId,
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
