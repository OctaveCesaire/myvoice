@props(['slt', 'title','choix'])

@php
    $length = is_array($slt) ? count($slt) : 0;
    $elt = ($length > 0) ? $slt : null;
@endphp



<select name="{{$choix}}" class="form-select" aria-label="Default select example">
    <option selected disabled>{{$title}}</option>
    @foreach ($elt as $key=>$value)
        <option value="{{$value}}">{{$value}}</option>
    @endforeach
</select>

