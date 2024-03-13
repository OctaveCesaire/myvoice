
@extends('index')
@section('content')
    <div class="page-header" style="display: flex; flex-direction:column; align-items:center; justify-content:center">
        <div class="text-uppercase container">
            <div class="card">
                <div class="card-header h2">
                    {{Auth::user()->name}}
                </div>
                <div class="card-body text-success bg-dark">
                    you had done. Thanks to wait end of the companion.
                </div>
            </div>
        </div>
    </div>
@endsection
