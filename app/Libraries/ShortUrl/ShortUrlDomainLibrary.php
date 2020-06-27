<?php

namespace App\Libraries\ShortUrl;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShortUrlDomainLibrary
{
    /**
     * This still will need to cater for internationalized domains and IDNs
     *
     * @param $domainString
     * @return bool
     */
    public function validDomainString($domainString): bool
    {
        return preg_match('/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/i', $domainString);
    }

    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function getAvailableHosts(User $user)
    {
        $companies = $user->companies;

        if ($companies->isEmpty()) {
            Log::error('Companies are empty for user ' . $user->id);
            throw new \Exception('User does not have companies set');
        }
        $sql = 'SELECT uuid, public, domain
                FROM short_urls.short_url_domains sud
                WHERE public = true
                   OR company_id IN (:company_ids)
                ORDER BY public DESC';
        $results = DB::select(
            $sql,
            [
                'company_ids' => $companies->implode('id', ','),
            ]
        );
        return $results;
    }
}
