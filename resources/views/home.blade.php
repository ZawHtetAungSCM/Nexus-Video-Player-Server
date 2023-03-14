@extends('layouts.app')

@section('title','Nexus')

@section('content')
<div class="container">
    <div class="d-flex justify-content-center mt-3">
        <div class="text-center">
            <img src="/images/nexus_app_logo.png" class="card-img-top" style=" width: 120px; border-radius: 40px; object-fit:cover;">
            <h3 class="mt-3">Nexus Video Player</h3>
            <p class="mt-3">"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."</p>
        </div>
    </div>
    <div class="d-flex align-items-center">
        <div class="row justify-content-center w-100 ms-0">
            <div class="col-md-6 d-flex justify-content-center mt-4">
                <a href="{{ route('feed')}}" class="btn btn-lg btn-primary rounded-4 w-50">New Feeds</a>
            </div>
            <div class="col-md-6 d-flex justify-content-center mt-4">
                <a href="{{ route('convert')}}" class="btn btn-lg btn-primary rounded-4 w-50">Converter</a>
            </div>
        </div>
    </div>
</div>
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('addVideo')}}" class="btn btn-primary">Upload New Video</a>
                    </div>

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
</div> -->
@endsection