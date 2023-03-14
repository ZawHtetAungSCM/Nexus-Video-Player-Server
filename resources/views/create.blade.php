@extends('layouts.app')

@section('title','Video Upload')

@section('content')
<div class="container pt-4">
    <div class="d-flex justify-content-center">
        <div class="col-md-8">
            <div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card">
                    <div class="card-header text-center">
                        <h5>Add New Video File</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Video</label>
                        <div id="upload-container" class="d-flex align-items-center mb-3">
                            <button id="browseFile" class="btn btn-secondary ">Brows File</button>
                            <div id="progress" style="display: none" class="progress w-75 ms-4" style="height: 25px">
                                <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                            </div>
                        </div>
                        <form action="{{ route('uploadFile') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter Video Title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thumbnail</label>
                                <input class="form-control" type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png">
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="filename" name="filename" value="{{ old('filename') }}">
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                <button id="add_btn" type="submit" class="btn btn-primary" >Add Video</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script type="text/javascript">
    let browseFile = document.getElementById('browseFile');
    let progress = document.getElementById("progress");
    let progressBar = document.getElementById("progress-bar");

    let resumable = new Resumable({
        target: '{{ route("uploadVideo") }}',
        query: {
            _token: '{{ csrf_token() }}'
        }, // CSRF token
        fileType: ['mp4'],
        chunkSize: 10 * 1024 * 1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
        headers: {
            'Accept': 'application/json'
        },
        testChunks: false,
        throttleProgressCallbacks: 1,
    });

    resumable.assignBrowse(browseFile);

    resumable.on('fileAdded', function(file) { // trigger when file picked
        showProgress();
        resumable.upload() // to actually start uploading.
    });

    resumable.on('fileProgress', function(file) { // trigger when file progress update
        updateProgress(Math.floor(file.progress() * 100));
    });

    resumable.on('fileSuccess', function(file, response) { // trigger when file upload complete
        response = JSON.parse(response)
        console.log("response : ", response);
        document.getElementById("filename").value = response.filename;
        document.getElementById('add_btn').disabled = false;
        // hideProgress()
    });

    resumable.on('fileError', function(file, response) { // trigger when there is any error
        alert('file uploading error.')
    });

    function showProgress() {
        progressBar.style.width = "0%";
        progressBar.innerHTML = "0%";
        progress.style.display = "block";
    }

    function updateProgress(value) {
        progressBar.style.width = `${value}%`;
        progressBar.innerHTML = `${value}%`;
    }

    function hideProgress() {
        progress.style.display = "none";
    }
</script>
@endsection