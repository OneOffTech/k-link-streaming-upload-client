<?php 

namespace Oneofftech\KlinkStreaming\Contracts;

interface Client
{
    /**
     * @see upload()
     */
    public function add($file);

    /**
     * Upload a file
     *
     * @param string $file the path of the file to upload
     * @return \Oneofftech\KlinkStreaming\Upload
     */
    public function upload($file);

    /**
     * Get a video by its identifier
     *
     * @param string $video_id The identifier of the video
     * @return \Oneofftech\KlinkStreaming\Video
     */
    public function get($video_id);

    /**
     * Delete a video by its identifier
     *
     * @param string $video_id The identifier of the video
     * @return \Oneofftech\KlinkStreaming\Video
     */
    public function delete($video_id);

}
