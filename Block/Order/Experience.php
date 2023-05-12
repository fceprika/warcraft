<?php
namespace Blizzard\Warcraft\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;
use Magento\Customer\Model\Session;

/**
 * Custom block for displaying gained experience after placing an order.
 */
class Experience extends Template
{
    /**
     * @var CheckoutSession Checkout session instance.
     */
    protected $checkoutSession;

    /**
     * @var WarcraftFactory Warcraft model factory instance.
     */
    protected $warcraftFactory;

    /**
     * @var WarcraftResource Warcraft resource model instance.
     */
    protected $warcraftResource;

    /**
     * @var Session Customer session instance.
     */
    protected $customerSession;

    /**
     * Constructor: Initializes the required dependencies.
     *
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param WarcraftFactory $warcraftFactory
     * @param WarcraftResource $warcraftResource
     * @param Session $customerSession
     * @param array $data
     */
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

    /**
     * Returns the experience gained by the character.
     *
     * @return float | int
     */
    public function getGainedExperience()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        return $order->getGrandTotal() * 100;
    }

}
