@extends('index')
@section('content')
    <div class="page-header min-vh-100">
        <div class="container">
            <div class="row">
                @foreach ($candidatAll as $candidat)
                    <div class="col-xl-3 col-lg-3 col-6 mt-3">
                        <div class="card" style="min-height: 100%; max-width: 18rem;">
                            <img src="https://imgs.search.brave.com/tf2uwe7z-D6DQ_PNA2U48xLrXzD6pTQ5RpM2Qgeu1no/rs:fit:500:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMuZXVyb25ld3Mu/Y29tL2FydGljbGVz/L3N0b3JpZXMvMDgv/MjAvMjMvNDQvNDUx/eDI1NF9jbXN2Ml8w/ODk3ZTY5Ni01ZjA2/LTUxYzAtYWY5Yy1j/YjAyNDQ5OGQ0ZjMt/ODIwMjM0NC5qcGc" class="card-img-top" alt="{{ $candidat->fullName }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $candidat->fullName }} </h5>

                                {{-- Pour eviter de récupérer le dernier éléments de la chaine --}}

                                <form action="{{route('vote')}}" method="POST" role="form">
                                    @csrf
                                    <input type="hidden" name="vote_for" value="{{ $candidat->id }}">
                                    <input type="hidden" name="election_id" value="{{ $candidat->election_id }}">
                                    <input type="submit" value="{{__('Vote')}} . {{$candidat->id}}" class="btn btn-outline-primary">
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
