<?php

namespace Tests\Features;

use InvalidArgumentException;
use Tests\TestCase;
use Tests\Fixtures\MockableClient;
use Oneofftech\KlinkStreaming\Client;
use Oneofftech\KlinkStreaming\Exceptions\UploadFailedException;
use Oneofftech\KlinkStreaming\Video;
use Oneofftech\KlinkStreaming\Upload;

class AddVideoTest extends TestCase
{
    use MockableClient;

    private $storage_path = null;

    function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            
            foreach( $files as $file )
            {
                $this->delete_files( $file );      
            }
          
            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }

    protected function setUp(): void
    {
        $this->storage_path = __DIR__ .'/../storage/';

        if(!is_dir($this->storage_path)){
            mkdir($this->storage_path);
        }
    }

    protected function tearDown(): void
    {
        if(is_dir($this->storage_path)){
            $this->delete_files($this->storage_path);
        }
    }

    public function test_text_file_cannot_be_uploaded()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $this->expectException(InvalidArgumentException::class);

        $videos = new Client($url, $app_token, $app_url);

        $file_path = $this->storage_path . 'file.txt';
        file_put_contents($file_path, 'Hello');

        $videos->add($file_path);
    }

    public function test_video_file_upload_started_and_failure_is_handled()
    {
        $url = getenv('VIDEO_STREAMING_SERVICE_URL');
        $app_token = 'token';
        $app_url = 'url';

        $http_response = [
            "video_id" => "cj7dc7s160001ucm3jnvtdkd2",
            "request_id" => "sc-1504963172",
            "upload_token" => "sFsr7n3gXBg7w8eSUgIlViZ1l1EnUYVZjmCgjarHOVG7zFnsc-1504963172",
            "upload_location" => "http://127.0.0.1:1080/video.uploads/",
        ];

        $videos = $this->getMockedClient($url, $app_token, $app_url, 200, $http_response);

        $file_path = $this->storage_path . 'file.mp4';
        file_put_contents($file_path, file_get_contents(__DIR__ .'/../data/video.mp4'));

        $this->expectException(UploadFailedException::class);

        $response = $videos->add($file_path);

        $this->assertNotNull($response);
        
        $this->assertInstanceOf(Upload::class, $response);
      
    }
}
