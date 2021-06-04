<?php

namespace Oneofftech\KlinkStreaming\Concerns;

use Oneofftech\KlinkStreaming\Http\Response;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;
use Psr\Http\Message\ResponseInterface;

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

    private $httpClient = null;

    private $messageFactory = null;

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
     * @return ResponseInterface
     */
    protected function request($method, $url, $body)
    {
        if(is_null($this->httpClient)){

            $this->httpClient = new PluginClient(
                HttpClientDiscovery::find(),
                [
                    new HeaderSetPlugin([
                        'User-Agent' => 'K-Link Streaming Client',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Origin' => $this->app_url, 
                        'Authorization' => 'Bearer ' . $this->app_token
                        ]),
                        ]
                    );

            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        $request = $this->messageFactory->createRequest($method, $url, [], json_encode($body));

        return $this->httpClient->sendRequest($request);
    }

}
