@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="mb-4">
                    {{ __('Managing Accounts') }}
                </h2>

                @if(session()->has('flash'))
                    <div class="alert alert-success">
                        {{ session()->get('flash') }}
                        <button type="button" class="close" data-dismiss="alert">x</button>
                    </div>
                @endif

                <div id="grid">
                    <account-grid></account-grid>
                </div>
            </div>
        </div>
    </div>
@endsection
