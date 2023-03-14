@extends('layouts.app')

@section('title','Videos')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h3>New Feeds</h3>
        <a href="{{ route('addVideo')}}" class="btn btn-primary">Upload New Video</a>
    </div>
    <div>
        @if (session()->has('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
        @endif

        @if (session()->has('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
        @endif

        @if($videos->count())
        <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 g-4">
            @foreach($videos as $video)
            <div>
                @include('components.card')
            </div>
            @endforeach
        </div>
        @else
        <div class="d-flex justify-content-center mt-5">
            <i>There is no Uploaded Video.</i>
        </div>
        @endif
    </div>
</div>
@endsection