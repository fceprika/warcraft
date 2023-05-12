<?php

namespace Blizzard\Warcraft\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote as MagentoQuote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\SalesRule\Model\Validator;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Blizzard\Warcraft\Helper\Data as HelperData;

class Discount extends AbstractTotal
{

    /**
     * @var Validator Sales rule validator instance.
     */
    protected $calculator;

    /**
     * @var PriceCurrencyInterface Price currency instance.
     */
    protected $priceCurrency;

    /**
     * @var HelperData Custom helper data instance.
     */
    protected $helperData;

    /**
     * Constructor: Initializes the required dependencies.
     *
     * @param Validator $validator
     * @param PriceCurrencyInterface $priceCurrency
     * @param HelperData $helperData
     */
    public function __construct(
        Validator $validator,
        PriceCurrencyInterface $priceCurrency,
        HelperData $helperData
    ) {
        $this->setCode('testdiscount');
        $this->calculator = $validator;
        $this->priceCurrency = $priceCurrency;
        $this->helperData = $helperData;
    }


    /**
     * Collects the custom discount data based on the customer's rank.
     *
     * @param MagentoQuote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        MagentoQuote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $promo = $this->helperData->getCustomerPromo();
        if ($promo) {
            $baseDiscountPercent = abs((float) rtrim($promo, '%'));
            $totalAmount = $total->getSubtotal() * ($baseDiscountPercent / 100);

            $discountAmount = -$totalAmount;

            $total->setDiscountDescription($promo);
            $total->setDiscountAmount($discountAmount);
            $total->setBaseDiscountAmount($discountAmount);
            $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
            $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);
            $total->addTotalAmount($this->getCode(), $discountAmount);
            $total->addBaseTotalAmount($this->getCode(), $discountAmount);

        }

        return $this;
    }

    /**
     * Fetches the discount data to be displayed on the frontend.
     *
     * @param MagentoQuote $quote
     * @param Total $total
     * @return array|null
     */
    public function fetch(MagentoQuote $quote, Total $total)
    {
        $result = null;
        $amount = $total->getDiscountAmount();

        if ($amount != 0) {
            $description = $total->getDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => strlen($description) ? __('Character Discount (%1)', $description) : __('Character Discount'),
                'value' => $amount,
            ];
        }

        return $result;
    }
}
