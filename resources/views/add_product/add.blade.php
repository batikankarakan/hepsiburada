@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="{{ route('addProduct') }}">
                    @csrf
                    <div class="form-group">
                        <label for="ProductLink">Name</label>
                        <input name="Name" type="text" class="form-control" id="ProductLink">
                    </div>
                    <div class="form-group">
                        <label for="ProductLink">Image Link</label>
                        <input name="ImageLink" type="text" class="form-control" id="ProductLink">
                    </div>
                    <div class="form-group">
                        <label for="ProductLink">Price</label>
                        <input name="Price" type="text" class="form-control" id="Price">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
