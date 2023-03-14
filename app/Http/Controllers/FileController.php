<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\FileStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\VideoStoreRequest;
use App\Http\Requests\FileDecryptRequest;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use App\Contracts\Services\Video\VideoServiceInterface;
use Illuminate\Contracts\Cache\Store;

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
            return redirect()->route('feed')->with('message', 'New Video added Successfully');
        } else {
            return redirect()->route('feed')->with('error', 'Add New Video Fail');
        }
    }
    /**
     * Store a user uploaded Video File
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadVideo(Request $request)
    {
        $dir = config('constants.video_file_dir_temp');
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            // $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName = md5(time()) . '.' . $extension; // a unique file name

            $path = Storage::putFileAs($dir, $file, $fileName);

            // delete chunked file
            unlink($file->getPathname());
            return [
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];

        return ['message', 'Video uploaded Fail'];
    }

    /**
     * Download a file
     *
     * @param  string  $filename
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($id)
    {
        
        // $file =  "files/temp/bp_stay.mp4";
        // $file =  "files/BLACKPINK - 'STAY' MV_fc9576d7aac8010884b8f5e0e5f8347e.mp4.enc";
        // $fileUrl = Storage::url($video->file_path . $video->file_name);
        // $dn =  Storage::download($file);
        
        // $filesPath = 'files/temp/'; // change this
        // $fileName = 'bp_stay.mp4'; // change this
        // return $fileSize;
        // $video = Video::findOrFail($id);
        // $file =   $video->file_path . $video->file_name;
        // $filePath =   Storage::path($file);
        // $fileSize = Storage::size($file);


        // $headers = [
        //     'Content-Length' => $fileSize
        // ];

        // return response()->download($filePath, $video->file_name, $headers);

        // return Storage::size($file );

        // return redirect($dn)->downloadFile($fileUrl);

        //---------------------------------
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
    public function encryptUploadFile(VideoStoreRequest $request)
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
