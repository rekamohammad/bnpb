<?php

namespace Botble\Language;

use Locale;
use Illuminate\Http\Request;

class LanguageNegotiator
{

    /**
     * @var String
     */
    protected $defaultLocale;

    /**
     * @var array
     */
    protected $supportedLanguages;

    /**
     * @var Request
     */
    protected $request;


    /**
     * @param string $defaultLocale
     * @param array $supportedLanguages
     * @param Request $request
     * @author Marc Cámara <mcamara88@gmail.com>
     */
    public function __construct($defaultLocale, $supportedLanguages, Request $request)
    {
        $this->defaultLocale = $defaultLocale;
        $this->supportedLanguages = $supportedLanguages;
        $this->request = $request;
    }


    /**
     * Negotiates language with the user's browser through the Accept-Language
     * HTTP header or the user's host address.  Language codes are generally in
     * the form "ll" for a language spoken in only one country, or "ll-CC" for a
     * language spoken in a particular country.  For example, U.S. English is
     * "en-US", while British English is "en-UK".  Portuguese as spoken in
     * Portugal is "pt-PT", while Brazilian Portuguese is "pt-BR".
     *
     * This function is based on negotiateLanguage from Pear HTTP2
     * http://pear.php.net/package/HTTP2/
     *
     * Quality factors in the Accept-Language: header are supported, e.g.:
     *      Accept-Language: en-UK;q=0.7, en-US;q=0.6, no, dk;q=0.8
     *
     * @return string  The negotiated language result or app.locale.
     * @author Marc Cámara <mcamara88@gmail.com>
     */
    public function negotiateLanguage()
    {
        $matches = $this->getMatchesFromAcceptedLanguages();
        foreach (array_keys($matches) as $key) {
            if (!empty($this->supportedLanguages[$key])) {
                return $key;
            }
        }
        // If any (i.e. "*") is acceptable, return the first supported format
        if (isset($matches['*'])) {
            reset($this->supportedLanguages);

            return key($this->supportedLanguages);
        }

        if (class_exists('Locale') && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $http_accept_language = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

            if (!empty($this->supportedLanguages[$http_accept_language])) {
                return $http_accept_language;
            }
        }

        if ($this->request->server('REMOTE_HOST')) {
            $remote_host = explode('.', $this->request->server('REMOTE_HOST'));
            $lang = strtolower(end($remote_host));

            if (!empty($this->supportedLanguages[$lang])) {
                return $lang;
            }
        }

        return $this->defaultLocale;
    }

    /**
     * Return all the accepted languages from the browser
     * @return array Matches from the header field Accept-Languages
     * @author Marc Cámara <mcamara88@gmail.com>
     */
    protected function getMatchesFromAcceptedLanguages()
    {
        $matches = [];

        if ($acceptLanguages = $this->request->header('Accept-Language')) {
            $acceptLanguages = explode(',', $acceptLanguages);

            $generic_matches = [];
            foreach ($acceptLanguages as $option) {
                $option = array_map('trim', explode(';', $option));
                $link = $option[0];
                if (isset($option[1])) {
                    $query = (float)str_replace('q=', '', $option[1]);
                } else {
                    $query = null;
                    // Assign default low weight for generic values
                    if ($link == '*/*') {
                        $query = 0.01;
                    } elseif (substr($link, -1) == '*') {
                        $query = 0.02;
                    }
                }
                // Unweighted values, get high weight by their position in the
                // list
                $query = isset($query) ? $query : 1000 - count($matches);
                $matches[$link] = $query;

                //If for some reason the Accept-Language header only sends language with country
                //we should make the language without country an accepted option, with a value
                //less than it's parent.
                $link_ops = explode('-', $link);
                array_pop($link_ops);
                while (!empty($link_ops)) {
                    //The new generic option needs to be slightly less important than it's base
                    $query -= 0.001;
                    $op = implode('-', $link_ops);
                    if (empty($generic_matches[$op]) || $generic_matches[$op] > $query) {
                        $generic_matches[$op] = $query;
                    }
                    array_pop($link_ops);
                }
            }
            $matches = array_merge($generic_matches, $matches);

            arsort($matches, SORT_NUMERIC);

        }

        return $matches;
    }
}
