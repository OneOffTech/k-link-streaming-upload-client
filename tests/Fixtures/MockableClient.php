<?php 

namespace Tests\Fixtures;

use Mockery;
use GuzzleHttp\Psr7\Response;
use Oneofftech\KlinkStreaming\Client;
use Tests\Fixtures\FakePendingZttpRequest;

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
            return (new FakePendingZttpRequest())->withHeaders([
                            'Origin' => $app_url, 
                            'Authorization' => 'Bearer ' . $app_token
                    ])->accept('application/json')->asJson()->respondWith($return_code, $return_data);
        });
        return $mock;
    }

}
