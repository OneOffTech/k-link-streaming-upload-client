<?php

namespace Tests\Features;

use Tests\TestCase;
use InvalidArgumentException;
use Tests\Fixtures\MockableClient;
use Oneofftech\KlinkStreaming\Client;
use Oneofftech\KlinkStreaming\Video;

class GetVideoTest extends TestCase
{
    use MockableClient;

    public function test_video_get_request_is_sent_and_response_parsed()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        
        $http_response = [
            "request_id" => 'request_id',
            "response" => [
                "created_at" => "2017-09-05 11:29:00",
                "updated_at" => "2017-09-05 11:39:17",
                "video_id"=>"20170813",
                "title"=>null,
                "fail_reason"=>null,
                "status"=>"completed",
                "poster"=>"http://localhost:8000/storage/20170813/20170813.jpg",
                "dash_stream" => "http://localhost:8000/storage/20170813/20170813.mpd",
                "url" => "http://localhost:8000/play/20170813"
            ]
        ];

        $videos = $this->getMockedClient($url, $app_token, $app_url, 200, $http_response);

        $video = $videos->get('20170813');

        $this->assertNotNull($video);

        $this->assertInstanceOf(Video::class, $video);

        $this->assertEquals('20170813', $video->video_id);
        $this->assertNotEmpty($video->created_at);
        $this->assertNotEmpty($video->updated_at);
        $this->assertNotEmpty($video->status);
        $this->assertNotEmpty($video->poster);
        $this->assertNotEmpty($video->dash_stream);
        $this->assertNotEmpty($video->url);
    }

    public function test_video_get_request_with_invalid_video_id()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $http_response = [
            "params.video_id" => ["The selected params.video id is invalid."],
        ];

        $videos = $this->getMockedClient($url, $app_token, $app_url, 422, $http_response);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The selected params.video id is invalid.');

        $response = $videos->get('2017081');
    }
}
