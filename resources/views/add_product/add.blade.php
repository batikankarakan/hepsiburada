@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="{{ route('addProduct') }}">
                    @csrf
                    <div class="form-group">
                        <label for="link">Product Link</label>
                        <input name="link" type="text" class="form-control" id="link">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
