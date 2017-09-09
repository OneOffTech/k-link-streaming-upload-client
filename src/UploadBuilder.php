<?php 

namespace Oneofftech\KlinkStreaming;

class UploadBuilder
{
    

    public function __construct($streaming_service_url, $application_token, $application_url, $configuration)
    {

    }



    public function upload($file)
    {
        $process = new \Oneofftech\KlinkStreaming\UploadProcess();


        if($this->autostart){
            $process->start();
        }

        return $process;
    }



    public static function make()
    {

    }
}
