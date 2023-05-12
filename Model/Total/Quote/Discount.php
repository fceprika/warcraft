<?php

namespace Blizzard\Warcraft\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote as MagentoQuote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\SalesRule\Model\Validator;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Blizzard\Warcraft\Helper\Data as HelperData;

class Discount extends AbstractTotal
{
    protected $eventManager;
    protected $calculator;
    protected $storeManager;
    protected $priceCurrency;
    protected $helperData;

    public function __construct(
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Validator $validator,
        PriceCurrencyInterface $priceCurrency,
        HelperData $helperData
    ) {
        $this->setCode('testdiscount');
        $this->eventManager = $eventManager;
        $this->calculator = $validator;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        $this->helperData = $helperData;
    }

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

    public function fetch(MagentoQuote $quote, Total $total)
    {
        $result = null;
        $amount = $total->getDiscountAmount();

        if ($amount != 0) {
            $description = $total->getDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
                'value' => $amount,
            ];
        }

        return $result;
    }
}
