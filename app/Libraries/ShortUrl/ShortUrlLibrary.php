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
     * @param Company $company
     * @param ShortUrlDomain $shortUrlDomain
     * @param string $fullUrl
     * @param string|null $customPath
     * @param int|null $hashLength
     * @return ShortUrl
     * @throws CustomDomainHashExistsException
     */
    public function createShortUrl(Company $company, ShortUrlDomain $shortUrlDomain, string $fullUrl, string $customPath = null, int $hashLength = null): ShortUrl
    {
        $shortUrl = ShortUrl::create(
            [
                'company_id' => $company->id,
                'short_url_domain_id' => $shortUrlDomain->id,
                'full_url' => $fullUrl,
            ]
        );
        if ($customPath) {
            if ($this->shortURLExists($shortUrlDomain, $customPath)) {
                throw new CustomDomainHashExistsException("Path is in use");
            }
            $shortUrl->hash = $customPath;
            $shortUrl->short_url = $this->getShortUrlStringFromDomainAndHash($shortUrlDomain, $customPath);
            $shortUrl->save();
            return $shortUrl;
        }
        if ($hashLength === null || $hashLength === 0) {
            $hashLength = env('HASH_LENGTH',6);
        }
        $hash = $this->getUniqueHash($shortUrlDomain, $shortUrl->id,$hashLength);
        $shortUrl->hash = $hash;
        $shortUrl->short_url = $this->getShortUrlStringFromDomainAndHash($shortUrlDomain, $hash);
        $shortUrl->save();
        return $shortUrl;
    }

    /**
     * @param string $host
     * @param string $path
     * @return ShortUrl|null
     */
    public function getShortUrlFromUrlParts(string $host, string $path): ?ShortUrl
    {
        return $this->getShortUrlFromUrl($host . $path);
    }

    /**
     * @param $url
     * @return ShortUrl|null
     */
    public function getShortUrlFromUrl($url): ?ShortUrl
    {
        return ShortUrl::where('short_url', $url)->first();
    }

    /**
     * @param string $url
     * @param string $queryString
     * @return string
     */
    public function appendParametersToPath(string $url, string $queryString)
    {
        return $this->appendValueToHref($url, $queryString);
    }

    /**
     * @param string $href
     * @param string|null $value
     * @return string
     */
    private function appendValueToHref(string $href, ?string $value): string
    {
        if (strpos($href, '?') !== false) {
            //If the url has a ? already in, but it's at the end, append the params
            if (substr($href, -1) === '?') {
                $href .= $value;
                return $href;
            }
            //If the url has a & at the end, don't append another
            if (substr($href, -1) === '&') {
                $href .= $value;
                return $href;
            }
            $href .= '&' . $value;
            return $href;
        }
        $href .= '?' . $value;
        return $href;
    }

    /**
     * @param ShortUrlDomain $shortUrlDomain
     * @param $int
     * @param int|null $minLength
     * @return false|string
     */
    private function getUniqueHash(ShortUrlDomain $shortUrlDomain, $int, int $minLength = null)
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
