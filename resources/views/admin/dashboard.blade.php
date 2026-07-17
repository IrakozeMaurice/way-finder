@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-8">

    Dashboard

</h1>

<p class="mb-8">

    Welcome,

    <strong>{{ session('admin_name') }}</strong>

</p>

<div class="grid grid-cols-4 gap-6">

   
    <div class="bg-white rounded shadow p-6 text-center">

        <h2 class="text-xl font-bold">

            Floors

        </h2>

        <div class="text-5xl mt-4">

            {{$floors}}

        </div>

    </div>

    <div class="bg-white rounded shadow p-6 text-center">

        <h2 class="text-xl font-bold">

            Locations

        </h2>

        <div class="text-5xl mt-4">

            {{$locations}}

        </div>

    </div>

    <div class="bg-white rounded shadow p-6 text-center">

        <h2 class="text-xl font-bold">

            Waypoints

        </h2>

        <div class="text-5xl mt-4">

            {{$waypoints}}

        </div>

    </div>

    <div class="bg-white rounded shadow p-6 text-center">

        <h2 class="text-xl font-bold">

            Connections

        </h2>

        <div class="text-5xl mt-4">

            {{$connections}}

        </div>

    </div>

</div>

@endsection