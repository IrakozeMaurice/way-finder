<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-200">

<div class="flex justify-center items-center min-h-screen">

    <div class="bg-white w-96 rounded shadow p-6">

        <h2 class="text-3xl font-bold mb-6 text-center">

            Admin Login

        </h2>

        @if(session('error'))

            <div class="bg-red-100 text-red-600 p-3 rounded mb-4">

                {{ session('error') }}

            </div>

        @endif

        <form action="/admin/login" method="POST">

            @csrf

            <div class="mb-4">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded p-2"
                >

            </div>

            <div class="mb-4">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded p-2"
                >

            </div>

            <button
                class="bg-blue-600 text-white w-full p-2 rounded"
            >

                Login

            </button>

        </form>

    </div>

</div>

</body>

</html>