<?php
namespace Blizzard\Warcraft\Model;

use Magento\Framework\Model\AbstractModel;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResourceModel;

class Warcraft extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(WarcraftResourceModel::class);
    }
}
