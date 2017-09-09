<?php

namespace Oneofftech\KlinkStreaming\Concerns;

use Zttp\Zttp;
use Oneofftech\KlinkStreaming\Http\Response;

trait InteractWithHttp
{   
    /**
     * The K-Link Video Streaming service endpoint URL
     * @var string
     */
    private $url = null;
     
     /**
      * The token to use for authentication
      * @var string
      */
    private $app_token = null;
      
      /**
       * The application URL to specify in the request Origin. Used also for authentication
       * @var string
       */
    private $app_url = null;

    /**
     * Build the URL for an API action
     *
     * @param string $action The API action, e.g. "video.add"
     * @return string the URL to use for invoking the specific action
     */
    protected function url($action)
    {
        return rtrim($this->url, '/') . '/api/' . $action;
    }

    /**
     * Generates a JSON request with authorization
     *
     * @return \Zttp\PendingZttpRequest
     */
    protected function request()
    {
        return Zttp::withHeaders([
                'Origin' => $this->app_url, 
                'Authorization' => 'Bearer ' . $this->app_token
        ])->beforeSending(function($request){
            // var_dump($request);
        })->accept('application/json')->asJson();
    }

}
