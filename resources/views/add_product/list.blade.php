@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <ul class="col-md-8">
                @if(count($products) > 0)
                    @foreach($products as $product)
                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="{{ $product->image }}" class="card-img" alt="...">
                                </div>
                                <div class="col-md-8">

                                    <div class="card-body">
                                        <h5 class="card-title"><b>{{ $product->name }}</b></h5>
                                        <p class="card-text" style="font-size: large">{{ $product->price }} TL</p>
                                    </div>

                                    <form action="{{ route('products.destroy', [
                            'product' => $product->id,
                        ])}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm float-right mt-3 mr-2">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-2">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="alert-info alert mt-3 text-center">You don't have any products yet!</div>
                @endif
            </ul>
        </div>
    </div>
    </div>
@endsection
