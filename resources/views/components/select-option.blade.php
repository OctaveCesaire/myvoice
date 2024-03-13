@props(['slt', 'title'])

@php
    $length = is_array($slt) ? count($slt) : 0;
    $elt = ($length > 0) ? $slt : null;
@endphp

<select name="choix" required id="" class="mt-1 block w-full block font-medium text-sm text-gray-700 dark:text-gray-300 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
    <option value="" selected disabled>{{$title}}</option>
    @for ($i = 0; $i < $length; $i++)
        <option value="{{$elt[$i]}}">{{$elt[$i]}}</option>
    @endfor
</select>
