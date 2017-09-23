<?php 

namespace Oneofftech\KlinkStreaming;

use Symfony\Component\Process\Process;
use Oneofftech\KlinkStreaming\Exceptions\UploadFailedException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Upload
{

    private $video_id = null;
    
    private $request_id = null;

    private $file = null;
    
    private $url = null;
    
    private $token = null;
    
    private $app_url = null;

    private $process = null;
    
    /**
     * Create an Upload instance, to upload a file to a K-Link Streaming Service as an application
     *
     * @param string $video_id The video identifier
     * @param string $file The path of the file to upload
     * @param string $tus_url The K-Link Streaming service upload URL
     * @param string $request_id The request identifier, as used in the video.add call
     * @param string $token The token to authenticate the upload
     
     * @return \Oneofftech\KlinkStreaming\Upload
     */
    public function __construct($video_id, $file, $tus_url, $request_id, $token)
    {
        $this->video_id = $video_id;
        $this->request_id = $request_id;
        $this->file = $file;
        $this->url = $tus_url;
        $this->token = $token;

        $this->process = new Process( $this->getUploaderBinary() . ' upload --meta "upload_request_id='.$this->request_id.',token='.$this->token.'" "'.$this->file.'" '.$this->url.'', realpath(__DIR__ . '/../bin/'));
    }

    private function getUploaderBinary(){
        return  strtolower(PHP_OS)==='winnt' ? 'tus-client.exe' : realpath(__DIR__ . '/../bin/') . '/tus-client';
    }


    /**
     * Start the upload
     *
     * @return \Oneofftech\KlinkStreaming\Upload
     */
    public function start()
    {
        $code = $this->process->run();

        $err = $this->process->getErrorOutput();

        if(!empty($err) && strpos($err, 'upload failed') !== false){
            throw new UploadFailedException($err);
        }
        else if(!empty($err)){
            throw new \Exception($err);
        }

        return $this;
    }

    /**
     * Cancel the upload, if in progress
     *
     * @return int the process exit code
     */
    public function cancel()
    {
        return $this->process->stop(2);
    }

    /**
     * Check if the upload is in progress
     *
     * @return \Oneofftech\KlinkStreaming\Upload
     */
    public function isRunning()
    {
        return $this->process->isRunning();
    }

    /**
     * Get the video identifier
     *
     * @return string
     */
    public function videoId()
    {
        return $this->video_id;
    }
}
