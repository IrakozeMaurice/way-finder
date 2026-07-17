<!DOCTYPE html>
<html>

<head>

    <title>AR Way Finder</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

    <div class="w-64 min-h-screen bg-gray-900 text-white">

        <div class="text-center text-2xl font-bold p-6 border-b">

            AR Way Finder

        </div>

        <a href="/admin/dashboard" class="block px-6 py-3 hover:bg-gray-700">
            Dashboard
        </a>

        <a href="/admin/floors"
            class="block px-6 py-3 hover:bg-gray-700">

            Floors

            </a>

        <a href="#" class="block px-6 py-3 hover:bg-gray-700">
            Campus Maps
        </a>

        <a href="#" class="block px-6 py-3 hover:bg-gray-700">
            Locations
        </a>

        <a href="#" class="block px-6 py-3 hover:bg-gray-700">
            Waypoints
        </a>

        <a href="#" class="block px-6 py-3 hover:bg-gray-700">
            Connections
        </a>

        <form action="/admin/logout" method="POST">

            @csrf

            <button class="w-full text-left px-6 py-3 hover:bg-red-600">

                Logout

            </button>

        </form>

    </div>

    <div class="flex-1 p-8">

        @yield('content')

    </div>

</div>
@yield('scripts')
</body>

</html>