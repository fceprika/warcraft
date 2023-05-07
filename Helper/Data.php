<?php

namespace Blizzard\Warcraft\Helper;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;

class Data
{
    protected $directoryList;
    protected $file;

    public function __construct(
        DirectoryList $directoryList,
        File $file
    ) {
        $this->directoryList = $directoryList;
        $this->file = $file;
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
