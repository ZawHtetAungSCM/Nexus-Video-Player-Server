<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * Store a user uploaded file
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileStoreRequest $request)
    {
        $video = $this->videoServiceInterface->AddVideo($request);

        if ($video) {
            return redirect()->route('home')->with('message', 'Video uploaded Successfully');
        } else {
            return redirect()->route('home')->with('message', 'Upload complete');
        }
    }

    /**
     * Download a file
     *
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($id)
    {
        $response = $this->videoServiceInterface->DownloadVideo($id);

        if ($response) {
            return $response;
        } else {
            abort(404);
        }
    }

    /**
     * Encrypt a uploaded file
     */
    public function encryptUploadFile(FileStoreRequest $request)
    {
        $filename = $this->videoServiceInterface->EncryptUploadFile($request);

        if ($filename) {
            $response = $this->videoServiceInterface->DownloadEncryptedFile($filename);

            if ($response) {
                return $response;
            } else {
                return redirect()->route('home')->with('encrypt_message', 'Download Encrypted Video fail');
            }
            return $filename;
        } else {
            return redirect()->route('home')->with('encrypt_message', 'Video Encryption fail');
        }
    }

    /**
     * Encrypt a uploaded file
     */
    public function decryptUploadFile(FileDecryptRequest $request)
    {
        $filename = $this->videoServiceInterface->DecryptUploadFile($request);

        if ($filename) {
            $response = $this->videoServiceInterface->DownloadDecryptedFile($filename);

            if ($response) {
                return $response;
            } else {
                return redirect()->route('home')->with('decrypt_message', 'Download Decrypted Video fail');
            }
        } else {
            return redirect()->route('home')->with('decrypt_message', 'Video Decryption fail');
        }
    }
}
