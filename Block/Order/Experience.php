<?php
namespace Blizzard\Warcraft\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;
use Magento\Customer\Model\Session;

class Experience extends Template
{
    protected $checkoutSession;
    protected $warcraftFactory;
    protected $warcraftResource;

    protected $customerSession;

    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        WarcraftFactory $warcraftFactory,
        WarcraftResource $warcraftResource,
        Session $customerSession,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->warcraftFactory = $warcraftFactory;
        $this->warcraftResource = $warcraftResource;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getGainedExperience()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        $grandTotal = $order->getGrandTotal();
        $experience = $grandTotal * 100;
        return $experience;
    }

    public function getCustomerCharacter()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();

            $character = $this->warcraftFactory->create();
            $warcraftResource = $this->warcraftResource;
            $this->warcraftResource->load($character, $customerId, 'customer_id');
            if (!$character->getId()) {

            }
        }

        }

}
