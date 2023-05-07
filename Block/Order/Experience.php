<?php
namespace Blizzard\Warcraft\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;

class Experience extends Template
{
    protected $checkoutSession;

    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    public function getGainedExperience()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        $grandTotal = $order->getGrandTotal();
        $experience = $grandTotal * 100;
        return $experience;
    }
}
