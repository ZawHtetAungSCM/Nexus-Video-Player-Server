<div class="col">
    <div class="card p-2 rounded-4">
        <img src="{{ $video->thumbnail}}" class="card-img-top" style="width: 100%; aspect-ratio: 3/2.5; border-radius: 15px; object-fit:cover;">
        <div class="card-body p-1 mt-2">
            <p class="card-text" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                {{$video->title}}
            </p>

            <div class="d-flex justify-content-center w-100">
                <a href="{{ route('downloadFile', $video->id) }}" class="btn btn-secondary  w-100 rounded-3">Download</a>
            </div>
        </div>
    </div>
</div>