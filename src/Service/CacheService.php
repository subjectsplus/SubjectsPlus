<?php

namespace App\Service;

class CacheService
{
    public function isApcuEnabled()
    {
        return apcu_enabled();
    }

    public function getApcuCacheInfo()
    {
//        if(apcu_enabled()) {
//            $result = apcu_cache_info();
//        } else {
//            $result['return'] = "APCU Cache not enabled";
//        }
        ini_set('memory_limit','512');
        return apcu_cache_info();
    }
}