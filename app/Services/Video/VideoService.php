<?php

namespace App\Services\Video;

use App\Contracts\Dao\Video\VideoDaoInterface;
use App\Contracts\Services\Video\VideoServiceInterface;
use Illuminate\Http\Request;

/**
 * User Service class
 */
class VideoService implements VideoServiceInterface
{
    /**
     * Video Dao
     */
    private $videoDao;

    /**
     * Class Constructor
     * @param VideoDaoInterface
     * @return
     */
    public function __construct(VideoDaoInterface $videoDao)
    {
        $this->videoDao = $videoDao;
    }

    /**
     * To get All Video List
     * @return Array[Video]
     */
    public function showVideos()
    {
        $videos = $this->videoDao->showVideos();
        return $videos;
    }

    /**
     * To Create Video
     * @param Request $request request with inputs
     * @return Boolean success or not
     */
    public function AddVideo(Request $request)
    {
        $video = $this->videoDao->AddVideo($request);
        return $video;
    }

    /**
     * To Download Video
     * @param Int $id for Video Id
     * @return response->download of Video File
     */
    public function DownloadVideo($id)
    {
        $response = $this->videoDao->DownloadVideo($id);
        return $response;
    }

    /**
     * To Encrypt Upload Video (temporary)
     * @param Request $request request file inputs
     * @return String $filename
     */
    public function EncryptUploadFile(Request $request)
    {
        $filename = $this->videoDao->EncryptUploadFile($request);
        return $filename;
    }

    /**
     * To Encrypt Upload Video (temporary)
     * @param $filename for download
     * @return response->download of Video File
     */
    public function DownloadEncryptedFile($filename)
    {
        $response = $this->videoDao->DownloadEncryptedFile($filename);
        return $response;
    }

    /**
     * To Decrypt Upload Video (temporary)
     * @param Request $request request file inputs
     * @return String $filename
     */
    public function DecryptUploadFile(Request $request)
    {
        $filename = $this->videoDao->DecryptUploadFile($request);
        return $filename;
    }

    /**
     * To Download Decrypted Upload Video (temporary)
     * @param $filename for download
     * @return response->download of Video File
     */
    public function DownloadDecryptedFile($filename)
    {
        $response = $this->videoDao->DownloadDecryptedFile($filename);
        return $response;
    }

    public function DeleteVideo($id)
    {
        $video = $this->videoDao->DeleteVideo($id);
        return $video;
    }

    public function PassIdVideo($id)
    {
        $video = $this->videoDao->PassIdVideo($id);
        return $video;
    }

    public function UpdateVideo(Request $request, $id)
    {
        $video = $this->videoDao->UpdateVideo($request, $id);
        return $video;
    }
}
