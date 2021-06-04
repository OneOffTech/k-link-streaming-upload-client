<?php

namespace Tests\Unit;

use Zttp\Zttp;
use Tests\TestCase;
use Zttp\PendingZttpRequest;
use Oneofftech\KlinkStreaming\Concerns\InteractWithHttp;
use Psr\Http\Message\ResponseInterface;

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

}
