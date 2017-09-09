<?php

namespace Tests\Unit;

use Zttp\Zttp;
use Tests\TestCase;
use Zttp\PendingZttpRequest;
use Oneofftech\KlinkStreaming\Concerns\InteractWithHttp;

class InteractWithHttpTest extends TestCase
{

    use InteractWithHttp;


    public function test_action_url_is_returned()
    {
        $this->url = getenv('VIDEO_STREAMING_SERVICE_URL').'/';
        $this->app_token = 'token';
        $this->app_url = 'url';

        $url = $this->url('video.get');

        $this->assertEquals( rtrim(getenv('VIDEO_STREAMING_SERVICE_URL'), '/') . '/api/video.get', $url);
    }

    public function test_request_as_headers_set()
    {
        $this->url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $this->app_token = 'token';
        $this->app_url = 'url';

        $request = $this->request();

        $this->assertInstanceOf(PendingZttpRequest::class, $request);

        $this->assertEquals('json', $request->bodyFormat);

        $this->assertEquals( [
            "Origin" => $this->app_url,
            "Authorization" => "Bearer $this->app_token",
            "Accept" => "application/json",
            "Content-Type" => "application/json"], $request->options['headers']);
    }

}
