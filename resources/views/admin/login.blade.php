<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-zinc-100 px-4 font-sans">
<form method="POST" action="{{ route('admin.login.post') }}" class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-lg">
    @csrf
    <h1 class="text-xl font-extrabold text-zinc-900">Login Admin</h1>
    <p class="mt-1 text-sm text-zinc-500">DealerMotorHondaGarut.id</p>
    <label class="mt-6 block text-sm font-semibold">Email
        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda"></label>
    <label class="mt-4 block text-sm font-semibold">Password
        <input type="password" name="password" required class="mt-1 w-full rounded-xl border border-zinc-300 px-4 py-2.5 focus:border-honda focus:ring-honda"></label>
    @error('email')<p class="mt-3 text-sm text-red-600">{{ $message }}</p>@enderror
    <label class="mt-4 flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
    <button class="mt-6 w-full rounded-xl bg-honda py-3 text-sm font-bold text-white hover:bg-honda-dark">Masuk</button>
</form>
</body>
</html>
