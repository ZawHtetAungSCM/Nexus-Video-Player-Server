@extends('layouts.app')

@section('title','Convert')

@section('content')
<div class="container">

    <div class="d-flex justify-content-center my-4">
        <h2>Converter</h2>
    </div>
    <div class="d-flex">
        <div class="col-md-6 p-5">
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
                            <input class="form-control" type="file" id="file" name="file" accept=".mp4">
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Encrypt</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-6 p-5">
            <div class="card">
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
                            <input class="form-control" type="file" id="file" name="file" accept=".mp4">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Decrypt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection