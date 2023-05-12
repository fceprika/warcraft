<?php

namespace Blizzard\Warcraft\Helper;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Customer\Model\Session;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;

/**
 * Helper class for various Warcraft related functionalities.
 */
class Data
{
    /**
     * @var DirectoryList Directory list instance.
     */
    protected $directoryList;

    /**
     * @var File File instance.
     */
    protected $file;

    /**
     * @var Session Customer session instance.
     */
    protected $customerSession;

    /**
     * @var WarcraftFactory Warcraft model factory instance.
     */
    protected $warcraftFactory;

    /**
     * @var WarcraftResource Warcraft resource model instance.
     */
    protected $warcraftResource;

    /**
     * Constructor: Initializes the required dependencies.
     *
     * @param DirectoryList $directoryList
     * @param File $file
     * @param Session $customerSession
     * @param WarcraftFactory $warcraftFactory
     * @param WarcraftResource $warcraftResource
     */
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

    /**
     * Get the current customer's promotion.
     *
     * @return string|null Customer's promotion, or null if not logged in or no promotion found.
     */
    public function getCustomerPromo()
    {
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomer()->getId();
            $customer_char = $this->warcraftFactory->create();
            $this->warcraftResource->load($customer_char, $customerId, 'customer_id');
            if ($customer_char) {
                return $customer_char->getPromotion();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Get rank information based on the given experience.
     *
     * @param int $experience The experience to look up rank information for.
     * @return array|null Rank information, or null if not found.
     */
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
