<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;

/**
 * Class Localization
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Localization
{

    /**
     * Localization constructor.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // read the language from the request header
        $locale = $request->header('Accept-Language');

        // if the header is missed
        if(!$locale){
            // take the default local language
            $locale = $this->app->config->get('app.locale');
        }

        // check the languages defined is supported
        if (!array_key_exists($locale, $this->app->config->get('app.supported_languages'))) {
            // respond with error
            // return abort(403, 'Language not supported.');
        } else {
            // set the local language
            $this->app->setLocale($locale);
        }

        if ($request->header('Timezone')) {
            // set Timezone
            config('app.timezone', $request->header('Timezone'));
            date_default_timezone_set($request->header('Timezone'));
        }

        // get the response after the request is done
        $response = $next($request);

        // set Content Languages header in the response
        $response->headers->set('Content-Language', $locale);

        // return the response
        return $response;
    }
}
