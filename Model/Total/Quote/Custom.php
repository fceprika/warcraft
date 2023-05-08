<?php

namespace Blizzard\Warcraft\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Blizzard\Warcraft\Helper\Data as HelperData;

class Custom extends AbstractTotal
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * Custom constructor.
     *
     * @param PriceCurrencyInterface $priceCurrency
     * @param HelperData $helperData
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        HelperData $helperData
    ) {
        $this->_priceCurrency = $priceCurrency;
        $this->helperData = $helperData;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $promo = $this->helperData->getCustomerPromo();

        if ($promo)
        {
            // Get the base discount from the helper
            $baseDiscountPercent = abs((float) rtrim($promo, '%'));
            if ($baseDiscountPercent) {

                // Calculate discount amount based on the percentage
                $baseDiscountAmount = $quote->getBaseGrandTotal() * ($baseDiscountPercent / 100);

                $total->setDiscountDescription($promo);
                $total->setDiscountAmount('-'.$baseDiscountAmount);

                // Convert the base discount to store currency
                $discountAmount = $this->_priceCurrency->convert($baseDiscountAmount);

                // Add the custom discount amounts to the total
                $total->addTotalAmount('customdiscount', -$discountAmount);
                $total->addBaseTotalAmount('customdiscount', -$baseDiscountAmount);

                // Update the grand total
                $total->setGrandTotal($total->getGrandTotal() - $discountAmount);
                $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscountAmount);

                // Set the custom discount for the quote
                $quote->setCustomDiscount(-$discountAmount);
            }
        }


        return $this;
    }

    public function fetch(Quote $quote, Total $total)
    {
        $result = null;
        $amount = $total->getDiscountAmount();

        if ($amount != 0)
        {
            $description = $total->getDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
                'value' => $amount
            ];
        }

        return $result;
    }
}
