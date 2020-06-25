<?php

namespace App\Http\Controllers;

use App\Libraries\ShortUrl\ShortUrlLibrary;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(Request $request)
    {
        $url  = \Request::fullUrl();
        $parts = parse_url($url);
        $shortUrl = $this->getShortUrlLibrary()->getShortUrlFromUrlParts($parts['host'], $parts['path']);
        if (!$shortUrl instanceof ShortUrl) {
            return $this->redirectToHashNotFound();
        }
        $fullUrl = $shortUrl->full_url;
        if (array_key_exists('query', $parts)) {
            $fullUrl = $this->getShortUrlLibrary()->appendParametersToPath($fullUrl, $parts['query']);
        }

        return Redirect::away($fullUrl);
    }

    /**
     * @return ShortUrlLibrary
     */
    private function getShortUrlLibrary(): ShortUrlLibrary
    {
        return new ShortUrlLibrary();
    }
}
