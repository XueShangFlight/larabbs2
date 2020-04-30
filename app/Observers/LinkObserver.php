<?php
/**
 * Created by: PhpStorm
 * Author: xueshang
 * Date: 2020-04-30 10:00
 */

namespace App\Observers;

use App\Models\Link;
use Illuminate\Support\Facades\Cache;

class LinkObserver
{
    //在保存时清空cache_key对应的缓存
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }
}
