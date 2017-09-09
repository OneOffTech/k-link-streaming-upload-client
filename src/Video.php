<?php 

namespace Oneofftech\KlinkStreaming;

/**
 *
 * @property-read string $id @see $video_id
 * @property-read string $video_id The public identifier of the video
 * @property-read string $created_at When the video was added
 * @property-read string $updated_at When the video was lastly updated
 * @property-read string|null $title The title assigned to this video (could be null)
 * @property-read string $fail_reason The reason of the processing failure
 * @property-read string $status The status of the video
 * @property-read string $url The URL of the video playback page
 * @property-read string|null $poster The URL of the poster image, if video processing is complete, null otherwise
 * @property-read string|null $dash_stream The URL of the DASH MPD manifest, if video processing is complete, null otherwise
 */
class Video
{

    private $primary_key = 'video_id';

    private $attributes = [];

    /**
     * The attributes that are considered when deserializing a video from json
     */
    private $only = [
        'created_at',
        'updated_at',
        'video_id',
        'title',
        'fail_reason',
        'status',
        'poster',
        'dash_stream',
        'url',
    ];
    
    /**
     * Create a Video instance
     *
     * @param array $attributes The attributes used to initialize the Video model
     * @return \Oneofftech\KlinkStreaming\Upload
     */
    public function __construct(array $attributes = [])
    {
        $keys = $this->only;
        
        $results = [];

        foreach ($keys as $key) {
            if(isset($attributes[$key])){
                $results[$key] = $attributes[$key];
            }
        }

        $this->attributes = $results;
    }

    private function getAttribute($key)
    {
        if (! $key) {
            return;
        }
 
        if($key==='id'){
            $key = $this->primary_key;
        }
 
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
       return $this->getAttribute($key);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return ! is_null($this->getAttribute($key));
    }

}
