<?php
namespace Blizzard\Warcraft\Model\ResourceModel\Warcraft;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Blizzard\Warcraft\Model\Warcraft as WarcraftModel;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'customer_id';

    protected function _construct()
    {
        $this->_init(WarcraftModel::class, WarcraftResourceModel::class);
    }
}
