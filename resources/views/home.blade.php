@extends('layouts.app')

@section('content')
<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h5>Upload File</h5>
                </div>
                <div class="card-body">
                    <div id="upload-container" class="text-center">
                        <button id="browseFile" class="btn btn-primary">Brows File</button>
                    </div>
                    <div id="progress" style="display: none" class="progress mt-3" style="height: 25px">
                        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                    </div>
                </div>

                <div id="card-footer" class="card-footer p-4" style="display: none">Video Upload Complete</div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script type="text/javascript">
    
    let browseFile = document.getElementById('browseFile');
    let cardFooter = document.getElementById("card-footer");
    let progress = document.getElementById("progress");
    let progressBar = document.getElementById("progress-bar");

    let resumable = new Resumable({
        target: '{{ route("uploadtesting") }}',
        query:{_token:'{{ csrf_token() }}'} ,// CSRF token
        fileType: ['mp4'],
        chunkSize: 10*1024*1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
        headers: {
            'Accept' : 'application/json'
        },
        testChunks: false,
        throttleProgressCallbacks: 1,
    });

    resumable.assignBrowse(browseFile);

    resumable.on('fileAdded', function (file) { // trigger when file picked
        showProgress();
        resumable.upload() // to actually start uploading.
    });

    resumable.on('fileProgress', function (file) { // trigger when file progress update
        updateProgress(Math.floor(file.progress() * 100));
    });

    resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
        response = JSON.parse(response)
        cardFooter.style.display = "block";
        // hideProgress()
    });
    
    resumable.on('fileError', function (file, response) { // trigger when there is any error
        alert('file uploading error.')
    });

    function showProgress() {
        progressBar.style.width = "0%";
        progressBar.innerHTML = "0%";
        progress.style.display = "block";
        cardFooter.style.display = "none";
    }

    function updateProgress(value) {
        progressBar.style.width = `${value}%`;
        progressBar.innerHTML  = `${value}%`;
    }

    function hideProgress() {
        progress.style.display = "none";
    }
</script>
@endsection
