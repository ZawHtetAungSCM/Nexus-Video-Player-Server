<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResoruce;
use App\Http\Resources\FileResource;
use App\Http\Requests\FileStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileDecryptRequest;
use App\Contracts\Services\Video\VideoServiceInterface;

class FileController extends Controller
{
    /**
     * video interface
     */
    private $videoServiceInterface;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VideoServiceInterface $videoService)
    {
        // $this->middleware('auth');
        $this->videoServiceInterface = $videoService;
    }


    public function index()
    {
        $videos = Video::all();
        return FileResource::collection($videos);
    }

    public function store(FileStoreRequest $request)
    {
        $video = $this->videoServiceInterface->AddVideo($request);

        if ($video) {
            return response()->json(['message' => 'Video was uploaded successfully.'], 200);
        } else {
            return response()->json(['message' => 'Video uploaded fail.'], 500);
        }
    }

    public function downloadFile($id)
    {
        $response = $this->videoServiceInterface->DownloadVideo($id);

        if ($response) {
            return $response;
        } else {
            return response()->json(["message" => "Video File doesn't exist."], 404);
        }
    }

    public function encryptUploadFile(FileStoreRequest $request)
    {
        $filename = $this->videoServiceInterface->EncryptUploadFile($request);

        if ($filename) {
            return $filename;
        } else {
            return response()->json(['message' => 'File Encryption Fail'], 500);
        }
    }

    public function downloadEncryptedFile($filename)
    {
        $response = $this->videoServiceInterface->DownloadEncryptedFile($filename);

        if ($response) {
            return $response;
        } else {
            return response()->json(['message' => 'Download Encrypted File fail.'], 500);
        }
    }

    public function decryptUploadFile(FileDecryptRequest $request)
    {
        $filename = $this->videoServiceInterface->DecryptUploadFile($request);

        if ($filename) {
            return $filename;
        } else {
            return response()->json(['message' => 'Upload Decrypted File fail.'], 500);
        }
    }

    public function downloadDecryptedFile($filename)
    {
        $response = $this->videoServiceInterface->DownloadDecryptedFile($filename);

        if ($response) {
            return $response;
        } else {
            return response()->json(['message' => 'Download Decrypted File fail.'], 500);
        }
    }
}
