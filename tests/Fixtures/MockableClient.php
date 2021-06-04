<?php 

namespace Tests\Fixtures;

use Mockery;
use GuzzleHttp\Psr7\Response;
use Oneofftech\KlinkStreaming\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use JMS\Serializer\SerializerBuilder;
use Http\Mock\Client as HttpMockClient;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Http\Discovery\MessageFactoryDiscovery;

trait MockableClient
{

    /**
     * Create a mocked version of the Client subject to testing, so the Http response can be faked
     */
    private function getMockedClient($url, $app_token, $app_url, $return_code, $return_data)
    {

        $mock = Mockery::mock(Client::class , [$url, $app_token, $app_url])->makePartial();
        $mock->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('request')->andReturnUsing(function() use($app_token, $app_url, $return_code, $return_data)
        {

            return new Response($return_code, 
                ['Content-Type' => 'application/json'],
                json_encode($return_data)
            );


            // return (new FakePendingZttpRequest())->withHeaders([
            //                 'Origin' => $app_url, 
            //                 'Authorization' => 'Bearer ' . $app_token
            //         ])->accept('application/json')->asJson()->respondWith($return_code, $return_data);
        });
        return $mock;
    }

}
