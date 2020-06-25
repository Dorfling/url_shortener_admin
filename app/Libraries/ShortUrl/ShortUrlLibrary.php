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
     * @param ShortUrlDomain $shortUrlDomain
     * @param $int
     * @param int|null $minLength
     * @return false|string
     */
    public function getUniqueHash(ShortUrlDomain $shortUrlDomain, $int, int $minLength = null)
    {
        $hash = $this->getShortUrlHashFromId($int);
        //If a minimum length was set, trim the hash if it's longer than the min length
        if ($minLength) {
            if (strlen($hash) <= $minLength) {
                dump(__LINE__, 'short enough');
                return $hash;
            }
            $hash = substr($hash, 0, $minLength);
        }
        //If the shortUrl Exists, do a recursive search to get a new one
        if ($this->shortURLExists($shortUrlDomain, $hash)) {
            //Get a random big int
            $randomNumber = rand(0, PHP_INT_MAX);
            return $this->getUniqueHash($shortUrlDomain, $randomNumber, $minLength);
        }
        //If all went well, pass the hash back
        return $hash;
    }

    /**
     * This exists solely to only have one central point to build the short url, lowering chances for mistyping
     *
     * @param ShortUrlDomain $shortUrlDomain
     * @param string $hash
     * @return string
     */
    private function getShortUrlStringFromDomainAndHash(ShortUrlDomain $shortUrlDomain, string $hash): string
    {
        return $shortUrlDomain->domain . '/' . $hash;
    }

    /**
     * @param ShortUrlDomain $shortUrlDomain
     * @param $hash
     * @return bool
     */
    private function shortURLExists(ShortUrlDomain $shortUrlDomain, $hash): bool
    {
        $sql = 'SELECT count(*) as counter FROM short_urls.short_urls
                WHERE short_url_domain_id = :short_url_domain_id
                AND hash = :hash LIMIT 1';
        $result = DB::selectOne(
            $sql,
            [
                'short_url_domain_id' => $shortUrlDomain->id,
                'hash' => $hash,
            ]
        );
        return ($result->counter > 0);
    }

    /**
     * @param $int
     * @return string
     */
    private function getShortUrlHashFromId($int): string
    {
        $sql = 'select stringify_bigint(pseudo_encrypt(CAST ( :int AS BIGINT ))) as stringify';
        $result = DB::selectOne($sql, ['int' => $int]);

        return $result->stringify;
    }
}
