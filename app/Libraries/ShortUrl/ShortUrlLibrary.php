<?php

namespace App\Libraries\ShortUrl;

use App\Exceptions\CustomDomainHashExistsException;
use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\ShortUrlDomain;
use Illuminate\Support\Facades\DB;

class ShortUrlLibrary
{

    /**
     * This exists solely to only have one central point to build the short url, lowering chances for mistyping
     *
     * @param ShortUrlDomain $shortUrlDomain
     * @param string $hash
     * @return string
     */
    private function getShortUrlStringFromDomainAndHash(ShortUrlDomain $shortUrlDomain, string $hash):string
    {
        return $shortUrlDomain->domain . '/' . $hash;
    }


}
