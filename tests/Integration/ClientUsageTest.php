<?php

namespace Tests\Integration;

use InvalidArgumentException;
use Tests\TestCase;
use Tests\Fixtures\MockableClient;
use Oneofftech\KlinkStreaming\Client;
use Oneofftech\KlinkStreaming\Video;
use Oneofftech\KlinkStreaming\Upload;

class ClientUsageTest extends TestCase
{

    protected function setUp(): void
    {
        if (empty(getenv('VIDEO_STREAMING_SERVICE_URL'))) {
            $this->markTestSkipped(
              'The VIDEO_STREAMING_SERVICE_URL is not configured.'
            );
        }
    }

    public function test_add_video()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $videos = new Client($url, $app_token, $app_url);
        
        $video_path = __DIR__ .'/../data/video.mp4';

        $upload = $videos->add($video_path);

        $this->assertNotNull($upload);
        
        $this->assertInstanceOf(Upload::class, $upload);

        $this->assertNotNull($upload->videoId());

        return $upload->videoId();
    }
    
    /**
     * @depends test_add_video
     */
    public function test_get_added_video($video_id)
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $videos = new Client($url, $app_token, $app_url);
        
        $video = $videos->get($video_id);

        $this->assertNotNull($video);

        $this->assertInstanceOf(Video::class, $video);
        $this->assertNotEmpty($video->video_id);
        $this->assertNotEmpty($video->created_at);
        $this->assertNotEmpty($video->updated_at);
        $this->assertNotEmpty($video->status);
        $this->assertNotEmpty($video->url);
        
        return $video_id;
    }
    
    /**
     * @depends test_get_added_video
     */
    public function test_delete_added_video($video_id)
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $videos = new Client($url, $app_token, $app_url);
        
        $video = $videos->delete($video_id);

        $this->assertNotNull($video);

        $this->assertInstanceOf(Video::class, $video);
        $this->assertNotEmpty($video->video_id);
        $this->assertNotEmpty($video->status);
        $this->assertEquals('deleted', $video->status);
    }

    public function test_get_non_existing_video()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $videos = new Client($url, $app_token, $app_url);

        $this->expectException(InvalidArgumentException::class);
        
        $video = $videos->get('20170813');
    }
}
