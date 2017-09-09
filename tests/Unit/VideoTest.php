<?php

namespace Tests\Unit;

use Tests\TestCase;
use Oneofftech\KlinkStreaming\Video;

class VideoTest extends TestCase
{


    public function test_attributes_are_accessible()
    {
        $attributes = [
            "created_at" => "2017-09-05 11:29:00",
            "updated_at" => "2017-09-05 11:39:17",
            "video_id"=>"20170813",
            "title"=>null,
            "fail_reason"=>null,
            "status"=>"completed",
            "poster"=>"http://localhost:8000/storage/20170813/20170813.jpg",
            "dash_stream" => "http://localhost:8000/storage/20170813/20170813.mpd",
            "url" => "http://localhost:8000/play/20170813"
        ];

        $video = new Video($attributes);

        $this->assertEquals($attributes['video_id'], $video->id);
        $this->assertEquals($attributes['video_id'], $video->video_id);
        $this->assertEquals($attributes['title'], $video->title);
        $this->assertEquals($attributes['fail_reason'], $video->fail_reason);
        $this->assertEquals($attributes['status'], $video->status);
        $this->assertEquals($attributes['poster'], $video->poster);
        $this->assertEquals($attributes['dash_stream'], $video->dash_stream);
        $this->assertEquals($attributes['url'], $video->url);
        $this->assertEquals($attributes['created_at'], $video->created_at);
        $this->assertEquals($attributes['updated_at'], $video->updated_at);
    }

    public function test_only_the_expected_attributes_are_accepted()
    {
        $attributes = [
            "created_at" => "2017-09-05 11:29:00",
            "updated_at" => "2017-09-05 11:39:17",
            "video_id"=>"20170813",
            "title"=>null,
            "fail_reason"=>null,
            "status"=>"completed",
            "poster"=>"http://localhost:8000/storage/20170813/20170813.jpg",
            "dash_stream" => "http://localhost:8000/storage/20170813/20170813.mpd",
            "url" => "http://localhost:8000/play/20170813",
            "another_attribute_to_discard" => 'value'
        ];

        $video = new Video($attributes);

        $this->assertFalse(isset($video->another_attribute_to_discard));
    }

}
