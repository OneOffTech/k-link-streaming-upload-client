<?php 

namespace Oneofftech\KlinkStreaming;

use Zttp\Zttp;
use InvalidArgumentException;
use Oneofftech\KlinkStreaming\Contracts\Client as ClientContract;

class Client implements ClientContract
{   

    use Concerns\InteractWithHttp;

    
    /**
     * Create a client instance to connect to the K-Link Streaming Service API
     *
     * @param string $streaming_service_url The K-Link Streaming service URL
     * @param string $application_token The token to authenticate against the K-Link Video Streaming Service
     * @param string $application_url The domain from which the requests will come, see the Authentication section of the Video Streaming Service API for further information
     * @param string $options Upload options
     * @return \Oneofftech\KlinkStreaming\Client
     */
    public function __construct($streaming_service_url, $application_token, $application_url)
    {
        $this->url = $streaming_service_url;
        $this->app_token = $application_token;
        $this->app_url = $application_url;
    }


    /**
     * @see upload()
     */
    public function add($file)
    {
        return $this->upload($file);
    }

    /**
     * Upload a file
     *
     * @param string $file the path of the file to upload
     * @return \Oneofftech\KlinkStreaming\Upload
     */
    public function upload($file)
    {

        $file_info = new \SplFileInfo($file);

        if(!$file_info->isReadable()){
            throw new InvalidArgumentException("File [$file] is not readable.");
        }

        $mime = mime_content_type($file);

        if($mime !== 'video/mp4'){
            throw new InvalidArgumentException("Only video/mp4 files are supported. Given [$mime].");
        }

        $request_id = 'sc-' . microtime();

        $response = $this->request()->post($this->url('video.add'), [
                'id' => $request_id,
                'params' => [
                    'filename' => $file_info->getFilename(),
                    'filesize' => $file_info->getSize(),
                    'filetype' => $mime
                ]
        ]);

        $body = $this->processResponse($response);

        $video_id = $body['video_id'];
        $upload_token = $body['upload_token'];
        $upload_location = $body['upload_location'];

        $upload = new Upload($video_id, $file, $upload_location, $request_id, $upload_token);

        $upload->start();

        return $upload;
    }

    /**
     * Get a video by its identifier
     *
     * @param string $video_id The identifier of the video
     * @return \Oneofftech\KlinkStreaming\Video
     */
    public function get($video_id)
    {
        $response = $this->request()->post($this->url('video.get'), [
                'id' => 'sc-' . microtime(),
                'params' => ['video_id' => $video_id]
        ]);

        $video = $this->processVideoResponse($response);

        return $video;
    }

    /**
     * Delete a video by its identifier
     *
     * @param string $video_id The identifier of the video
     * @return \Oneofftech\KlinkStreaming\Video
     */
    public function delete($video_id)
    {
        $response = $this->request()->post($this->url('video.delete'), [
                'id' => 'sc-' . microtime(),
                'params' => ['video_id' => $video_id],
        ]);
        
        $video = $this->processVideoResponse($response);
        
        return $video;
    }

    protected function processResponse($response)
    {
        $body = $response->json();
        // response code 200 => ok
        // response code 422 => parameter error
        // response code 401 => error object with information
        // response code else => error object with information
        
        if($response->status() === 422){
            $mappedErrors = array_map(function($a){
                return join(', ', $a);
            }, array_values($body));
            throw new \InvalidArgumentException(join(', ', $mappedErrors));
        }


        if(isset($body['error'])){
            throw new \Exception($body['error']);
        }

        if(!isset($body['request_id'])){
            throw new \Exception('Unexpected response from server');
        }

        return $body;
    }

    protected function processVideoResponse($response)
    {
        $body = $this->processResponse($response);

        if(!isset($body['response'])){
            throw new \Exception('Unexpected response from server');
        }

        return new Video($body['response']);
    }
}
