@extends('index')
@section('content')
    @if ($tab === [])
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form action="{{route('affiche')}}" method="POST" role="form">
                            @csrf
                            <div>
                                <x-select-option :choix="'Type_Election'" :slt="$tab" :title="'Select the election that you want to express yourself.'"/>
                            </div>
                            <input type="submit" value="{{__('CONFIRM MY CHOICE')}}" class="btn btn-outline-primary mt-2">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
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
    @endif

@endsection
