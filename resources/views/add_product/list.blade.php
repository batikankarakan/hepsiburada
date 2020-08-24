@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <ul class="col-md-8">
                @foreach($products as $product)
                    <div class="card mt-2" style="width: 18rem;">
                        <img src="{{$product->image}}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"> {{ $product->name }}</h5>
                            <p class="card-text">{{ $product->price }}</p>
                        </div>
                        <form action="{{ route('removeProduct', [
                            'id' => $product->id,
                        ])}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm float-right">Remove</button>
                        </form>
                    </div>
                @endforeach
            <div class="mt-2">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    </div>
@endsection
