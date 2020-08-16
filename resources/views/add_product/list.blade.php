@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($products as $product)
                    <div class="card mt-2" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"> {{ $product->Name }}</h5>
                            <p class="card-text">{{ $product->Price }}</p>
                        </div>
                        <form action="{{ route('removeProduct', [
                            'id' => $product->id,
                        ])}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm float-right">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
