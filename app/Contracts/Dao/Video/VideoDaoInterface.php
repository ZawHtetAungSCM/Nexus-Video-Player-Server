<?php

namespace App\Contracts\Dao\Video;

use Illuminate\Http\Request;

/**
 * Interface for Data Accessing Object of Video
 */
interface VideoDaoInterface
{

  /**
   * To get All Video List
   * @return Array[Video]
   */
  public function showVideos();

  /**
   * To Create Video
   * @param Request $request request with inputs
   * @return Boolean success or not
   */
  public function AddVideo(Request $request);

  /**
   * To Download Video
   * @param Int $id for Video Id
   * @return response->download of Video File
   */
  public function DownloadVideo($id);

  /**
   * To Encrypt Upload Video (temporary)
   * @param Request $request request file inputs
   * @return String $filename
   */
  public function EncryptUploadFile(Request $request);

  /**
   * To Download Encrypted Upload Video (temporary)
   * @param $filename for download
   * @return response->download of Video File
   */
  public function DownloadEncryptedFile($filename);

  /**
   * To Decrypt Upload Video (temporary)
   * @param Request $request request file inputs
   * @return String $filename
   */
  public function DecryptUploadFile(Request $request);

  /**
   * To Download Decrypted Upload Video (temporary)
   * @param $filename for download
   * @return response->download of Video File
   */
  public function DownloadDecryptedFile($filename);

  public function DeleteVideo($id);

  public function PassIdVideo($id);

  public function UpdateVideo(Request $request, $id);
}
