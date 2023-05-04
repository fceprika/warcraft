<?php
namespace Blizzard\Warcraft\Block\Account;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;

class Warcraft extends Template
{
    protected $customerSession;
    protected $warcraftFactory;

    protected $warcraftResource;
    public function __construct(
        Context $context,
        Session $customerSession,
        WarcraftFactory $warcraftFactory,
        WarcraftResource $warcraftResource,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->warcraftFactory = $warcraftFactory;
        $this->warcraftResource = $warcraftResource;
        parent::__construct($context, $data);
    }

    public function getCustomerCharacter()
    {

        $customerId = $this->customerSession->getCustomer()->getId();
        $character = $this->warcraftFactory->create();
        $this->warcraftResource->load($character, $customerId, 'customer_id');

        if ($character->getId()) {
            return $character->getData();
        }

        return null;
    }

}
