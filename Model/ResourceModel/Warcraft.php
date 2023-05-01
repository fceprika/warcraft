<?php
namespace Blizzard\Warcraft\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Warcraft extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('blizzard_warcraft', 'customer_id');
    }
}
