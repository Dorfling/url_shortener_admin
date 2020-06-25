<?php

namespace App\Http\Controllers;

use App\Libraries\ShortUrl\ShortUrlLibrary;
class RedirectController extends Controller
{

    /**
     * @return ShortUrlLibrary
     */
    private function getShortUrlLibrary(): ShortUrlLibrary
    {
        return new ShortUrlLibrary();
    }
}
