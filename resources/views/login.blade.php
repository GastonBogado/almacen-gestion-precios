<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · Almacén Viejos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">

    <div class="w-full max-w-sm">
        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                Almacén Viejos
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Iniciá sesión para continuar</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

            @error('email')
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-400">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        placeholder="tucorreo@ejemplo.com"
                    >
                </div>

                <div>
                    <label for="password" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950"
                        >
                        Recordarme
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                >
                    Ingresar
                </button>
            </form>
        </div>
    </div>

</body>
</html>
