<?php

namespace Blizzard\Warcraft\Helper;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Customer\Model\Session;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;

class Data
{
    protected $directoryList;
    protected $file;
    protected $customerSession;
    protected $warcraftFactory;
    protected $warcraftResource;

    public function __construct(
        DirectoryList $directoryList,
        File $file,
        Session $customerSession,
        WarcraftFactory $warcraftFactory,
        WarcraftResource $warcraftResource
    ) {
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->customerSession = $customerSession;
        $this->warcraftFactory = $warcraftFactory;
        $this->warcraftResource = $warcraftResource;
    }

    public function getCustomerPromo()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();
            $customer_char = $this->warcraftFactory->create();
            $this->warcraftResource->load($customer_char, $customerId, 'customer_id');
            if($customer_char){
                return $customer_char->getPromotion();
            } else {
                return null;
            }
        } else {
            return null;
        }

    }

    public function getRankInfoByExperience($experience)
    {
        $ranksFilePath = $this->directoryList->getPath('app')
            . '/code/Blizzard/Warcraft/etc/ranks.json';
        $ranksJson = $this->file->read($ranksFilePath);
        $ranks = json_decode($ranksJson, true);

        foreach ($ranks as $rank) {
            if ($experience >= $rank['min_xp'] && $experience <= $rank['max_xp']) {
                return $rank;
            }
        }

        return null;
    }
}
