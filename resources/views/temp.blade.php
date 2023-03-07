<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <form action="{{ route('uploadFile') }}" method="post" enctype="multipart/form-data" class="my-4">
                        @csrf

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Upload Your Video File</label>
                            <input class="form-control" type="file" id="file" name="file">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                        @php
                        
                        @endphp

                        @if (session()->has('message'))
                            <div class="alert alert-success mt-3">
                                {{ session('message') }}
                            </div>
                        @endif
                    </form>

                    <h4>Your files</h4>
                    <ul class="list-group">
                        @forelse ($videos as $video)
                            <li class="list-group-item p-3">
                                <a href="{{ route('downloadFile', $video->id) }}">
                                    {{ $video->title }}
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item">You have no files</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Encrypt Your File</div>
                <div class="card-body">
                    <form action="{{ route('encryptFile') }}" method="post" enctype="multipart/form-data" class="my-4">
                        @csrf
                        @if (session()->has('encrypt_message'))
                            <div class="alert alert-danger mt-3">
                                {{ session('encrypt_message') }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <input class="form-control" type="file" id="file" name="file">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Encrypt</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Decrypt Your File</div>
                <div class="card-body">
                    <form action="{{ route('decryptFile') }}" method="post" enctype="multipart/form-data" class="my-4">
                        @csrf
                        @if (session()->has('decrypt_message'))
                            <div class="alert alert-danger mt-3">
                                {{ session('decrypt_message') }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <input class="form-control" type="file" id="file" name="file">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Decrypt</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>