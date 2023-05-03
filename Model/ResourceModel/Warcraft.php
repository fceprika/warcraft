<?php

namespace Blizzard\Warcraft\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\DB\LoggerInterface;

class Warcraft extends AbstractDb
{
    protected $logger;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        $connectionName = null
    ) {
        $this->logger = $logger;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('blizzard_warcraft', 'customer_id');
    }

}
