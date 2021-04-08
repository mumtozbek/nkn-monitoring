@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Edit the Node') }}
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="host">{{ __('Host') }}</label>
                            <input type="text" name="host" id="host" value="{{ $node->host }}" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="host">{{ __('Account') }}</label>
                            <input type="text" name="account" id="account" value="{{ $node->account->name . ' (' . $node->account->provider->name . ')' }}" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('nodes.index') }}" class="btn btn-secondary">
                                {{ __('Back to list') }}
                            </a>
                        </div>

                        @if(count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
