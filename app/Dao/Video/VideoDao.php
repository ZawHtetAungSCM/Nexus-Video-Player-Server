<?php

namespace App\Dao\Video;

use Throwable;
use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Contracts\Dao\Video\VideoDaoInterface;

/**
 * Data accessing object for post
 */
class VideoDao implements VideoDaoInterface
{
    protected $cipher = "aes-128-ctr";
    protected $encrypt_key = "S-C-M-MobileTeam";
    protected $chunks_size = 256 * 16;

    /**
     * To get All Video List
     * @return Array[Video]
     */
    public function showVideos()
    {
        $videos = Video::orderBy('created_at', 'asc')->get();
        return $videos;
    }

    /**
     * To Create Video
     * @param Request $request request with inputs
     * @return Boolean success or not
     */
    public function AddVideo(Request $request)
    {
        DB::beginTransaction();
        $dir = 'files/';
        try {
            $filename = Storage::disk('local')->put($dir, $request->file('file'));
            
            $this->encryptFile($filename);
            $fnArr = explode("/", $filename);
            $fn = end($fnArr) . ".enc";
            // $filename = Storage::disk('local')->put($dir, fopen($request->file('file'), 'r+'));
            // $fnArr = explode("/", $filename);
            // $fn = end($fnArr);

            $video = Video::create([
                'title' => "Video Testing " . rand(100, 900),
                'file_name' => $fn,
                'file_path' => $dir,
                'thumbnail' => url("storage/movie_poster.jpg"),
                'key' => $this->encrypt_key,
            ]);
            DB::commit();

            return true;
        } catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * To Download Video
     * @param Int $id for Video Id
     * @return response->download of Video File
     */
    public function DownloadVideo($id)
    {
        $video = Video::findOrFail($id);

        if (!Storage::has($video->file_path . $video->file_name)) {
            return false;
        }

        /**
         * Download (Decrypted) File
         */
        // return response()->streamDownload(function () use($video) {
        //     // $this->streamDecrypt('files/' . auth()->user()->id . '/' . $filename);
        //     $this->streamDecrypt($video->file_path.$video->file_name);
        // }, Str::replaceLast('.enc', '', $video->file_name));

        /**
         * Download (Encrypted) File
         */
        $file =  $this->getFilePath($video->file_path . $video->file_name);
        return response()->download($file);
    }

    /**
     * To Encrypt Upload Video (temporary)
     * @param Request $request request file inputs
     * @return String $filename
     */
    public function EncryptUploadFile(Request $request){
        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            $filePath = Storage::disk('local')->put('files/temp', $request->file('file'));

            if ($filePath) {
                $this->encryptFile($filePath);
            }
            $fileNameSplit = (explode("/", $filePath));
            $fileName = end($fileNameSplit);
            return "{$fileName}.enc";
        }
        return false;
    }

    /**
     * To Download Encrypted Upload Video (temporary)
     * @param $filename for download
     * @return response->download of Video File
     */
    public function DownloadEncryptedFile($filename){
        try {
            $encryptFile =  Storage::path('files/temp/' . $filename);
            return response()->download($encryptFile)->deleteFileAfterSend(true);
        } catch (Throwable $th) {
            return false;
        }
    }

    /**
     * To Decrypt Upload Video (temporary)
     * @param Request $request request file inputs
     * @return String $filename
     */
    public function DecryptUploadFile(Request $request){
        //Check Enc file ?
        $OriFileExt = $request->file('file')->getClientOriginalExtension();

        if ($OriFileExt == "enc" && $request->hasFile('file') && $request->file('file')->isValid()) {

            $file_name = time() . '-' . $request->file('file')->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('files/temp', $file_name);

            if ($this->decryptFile($file_path)) {
                $decryptFile = Str::replaceLast('.enc', '', $file_name);

                return $decryptFile;
            }
            return false;
        }
        return false;
    }

    /**
     * To Download Decrypted Upload Video (temporary)
     * @param $filename for download
     * @return response->download of Video File
     */
    public function DownloadDecryptedFile($filename){
        try {
            $decryptFile =  Storage::path('files/temp/' . $filename);
            return response()->download($decryptFile)->deleteFileAfterSend(true);
        } catch (Throwable $th) {
            return false;
        }
    }

    public function DeleteVideo($id)
    {
        Video::find($id)->delete();
        return redirect('/');
    }

    public function PassIdVideo($id)
    {
        $video = Video::find($id);
        return $video;
    }

    public function UpdateVideo(Request $request,  $id)
    {
        Video::find($id)->update([
            'name' => $request->name
        ]);
    }




    /**
     * Encryption/Decryption
     */
    public function encryptFile($sourceFile, $destFile = null, $deleteSource = true)
    {
        if (is_null($destFile)) {
            $destFile = "{$sourceFile}.enc";
        }

        $sourcePath = $this->getFilePath($sourceFile);
        $destPath = $this->getFilePath($destFile);

        // If encryption is successful, delete the source file
        if ($this->encrypt($sourcePath, $destPath) && $deleteSource) {
            Storage::disk('local')->delete($sourceFile);
        }

        return $this;
    }

    public function encrypt($sourcePath, $destPath)
    {
        $fpOut = $this->openDestFile($destPath);
        $fpIn = $this->openSourceFile($sourcePath);

        $ivLenght = openssl_cipher_iv_length($this->cipher);
        $encryption_iv = openssl_random_pseudo_bytes($ivLenght);

        fwrite($fpOut, $encryption_iv);

        while (!feof($fpIn)) {
            $plaintext = fread($fpIn, $this->chunks_size);
            $ciphertext = openssl_encrypt($plaintext, $this->cipher, $this->encrypt_key, OPENSSL_RAW_DATA, $encryption_iv);
            // $encryption_iv = substr($ciphertext, 0, $ivLenght);
            fwrite($fpOut, $ciphertext);
        }
        fclose($fpIn);
        fclose($fpOut);

        return true;
    }

    public function decryptFile($sourceFile, $destFile = null, $deleteSource = true)
    {
        if (is_null($destFile)) {
            $destFile = Str::endsWith($sourceFile, '.enc')
                ? Str::replaceLast('.enc', '', $sourceFile)
                : $sourceFile . '.dec';
        }

        $sourcePath = $this->getFilePath($sourceFile);
        $destPath = $this->getFilePath($destFile);

        // If decryption is successful, delete the source file
        if ($this->decrypt($sourcePath, $destPath) && $deleteSource) {
            Storage::disk('local')->delete($sourceFile);
        }

        return true;
    }

    public function streamDecrypt($sourceFile)
    {
        $sourcePath = $this->getFilePath($sourceFile);

        return $this->decrypt($sourcePath, 'php://output');
    }

    public function decrypt($sourcePath, $destPath)
    {
        $fpOut = $this->openDestFile($destPath);
        $fpIn = $this->openSourceFile($sourcePath);

        $ivLenght = openssl_cipher_iv_length($this->cipher);

        $encryption_iv = fread($fpIn, $ivLenght);

        while (!feof($fpIn)) {
            $ciphertext = fread($fpIn, $this->chunks_size);
            $plaintext = openssl_decrypt($ciphertext, $this->cipher, $this->encrypt_key, OPENSSL_RAW_DATA,  $encryption_iv);
            // $encryption_iv = substr($ciphertext, 0, $ivLenght);
            fwrite($fpOut, $plaintext);
        }
        fclose($fpIn);
        fclose($fpOut);

        return true;
    }

    protected function getFilePath($file)
    {
        return Storage::path($file);
    }

    protected function openDestFile($destPath)
    {
        if (($fpOut = fopen($destPath, 'w')) === false) {
            // throw new Exception('Cannot open file for writing');
        }

        return $fpOut;
    }

    protected function openSourceFile($sourcePath)
    {
        $contextOpts = Str::startsWith($sourcePath, 's3://') ? ['s3' => ['seekable' => true]] : [];

        if (($fpIn = fopen($sourcePath, 'r', false, stream_context_create($contextOpts))) === false) {
            // throw new Exception('Cannot open file for reading');
        }

        return $fpIn;
    }
}
