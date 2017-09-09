<?php

namespace Tests\Features;

use Tests\TestCase;
use Tests\Fixtures\MockableClient;
use Oneofftech\KlinkStreaming\Client;
use Oneofftech\KlinkStreaming\Video;

class DeleteVideoTest extends TestCase
{
    use MockableClient;

    public function test_video_delete_request_is_sent_and_response_parsed()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $http_response = [
            "request_id" => 'request_id',
            "response" => [
                "video_id" => '',
                "status" => 'deleted',
                "created_at" => 'a date',
            ]
        ];

        $videos = $this->getMockedClient($url, $app_token, $app_url, 200, $http_response);

        $response = $videos->delete('2017081');

        $this->assertNotNull($response);

        $this->assertInstanceOf(Video::class, $response);
        $this->assertEquals('deleted', $response->status);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The selected params.video id is invalid.
     */
    public function test_video_delete_request_with_invalid_video_id()
    {

        // {"params.video_id":["The selected params.video id is invalid."]}
        // {"error":"Something happened."}
        // "{"request_id":"sc-1504943923","response":{"created_at":"2017-09-05 11:29:00","updated_at":"2
        //     017-09-05 11:39:17","video_id":"20170813","title":null,"fail_reason":null,"status":"completed","poster":"http:\/\/localhost:8000\/storage\/20170813\/20170813.jpg","dash_stream":"http:\/
        //     \/localhost:8000\/storage\/20170813\/20170813.mpd","url":"http:\/\/localhost:8000\/play\/20170813"}}"

        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $http_response = [
            "params.video_id" => ["The selected params.video id is invalid."],
        ];

        $videos = $this->getMockedClient($url, $app_token, $app_url, 422, $http_response);

        $response = $videos->delete('2017081');
    }
}
