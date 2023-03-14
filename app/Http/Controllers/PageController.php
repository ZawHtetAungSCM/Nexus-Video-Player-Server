<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FileResource;
use App\Contracts\Services\Video\VideoServiceInterface;

class PageController extends Controller
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

    public function home()
    {
        $videos = $this->videoServiceInterface->showVideos();
        $videos = FileResource::collection($videos);

        return view('home', compact('videos'));
    }

    public function feed()
    {
        $videos = $this->videoServiceInterface->showVideos();
        $videos = FileResource::collection($videos);
        return view('feed', compact('videos'));
    }

    public function convert()
    {
        return view('convert');
    }



    public function create()
    {
        $videos = $this->videoServiceInterface->showVideos();
        $videos = FileResource::collection($videos);
        return view('create');
    }
}
