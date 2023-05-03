<?php
namespace Blizzard\Warcraft\Block\Account;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Warcraft extends Template
{
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getWarcraftInfo()
    {
        // Implement your custom logic to fetch Warcraft info for the customer here
        // For example, you can load the customer data from your custom table and return it as an array

        return [
            'level' => 5,
            'experience' => 1000,
            'promotion' => '-10%',
            'rank' => 'Palouf'
        ];
    }
}
