@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-8">

Floors

</h1>

<div class="grid grid-cols-2 gap-6">

@foreach($floors as $floor)

<div class="bg-white rounded shadow p-6">

<h2 class="text-xl font-bold">

{{ $floor->name }}

</h2>

<p class="text-gray-500 mt-2">

Design hallways, locations and navigation graph.

</p>

<a

href="/admin/designer/{{ $floor->id }}"

class="inline-block mt-5 bg-blue-600 text-white px-5 py-2 rounded">

Open Designer

</a>

</div>

@endforeach

</div>

@endsection