<?php 

namespace Tests\Fixtures;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Zttp\PendingZttpRequest;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class FakePendingZttpRequest extends PendingZttpRequest
{

    private $return_code = 204;

    private $return_json = '';
    
    private $return_headers = [
        'Content-Type' => 'application/json'
    ];

    function respondWith($code, $data, $headers = [])
    {
        $this->return_code = $code;
        $this->return_json = json_encode($data);
        $this->return_headers = array_merge($this->return_headers, $headers);

        return $this;
    }

    function buildHandlerStack()
    {
        return \Zttp\tap(HandlerStack::create(new MockHandler([
            new Response($this->return_code, $this->return_headers, $this->return_json)
        ])), function ($stack) {
            $stack->push($this->buildBeforeSendingHandler());
        });
    }

}
