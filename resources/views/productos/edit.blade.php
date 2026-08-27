<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar {{ $producto->nombre }} · Almacén Viejos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">

    <header class="sticky top-0 z-20 border-b-2 border-indigo-500/60 bg-white/95 backdrop-blur dark:border-indigo-500/30 dark:bg-slate-950/95">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-2 px-4 py-2.5">
            <div class="flex items-baseline gap-2">
                <h1 class="text-base font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                    Almacén Viejos
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Editar producto</p>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-2xl space-y-6 px-4 py-6">

        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver al dashboard
        </a>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-400">
                {{ session('error') }}
            </div>
        @endif

        {{-- Sección 1: editar datos del producto --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="mb-4 text-sm font-bold text-slate-800 dark:text-slate-100">Datos del producto</h2>

            <form method="POST" action="{{ route('productos.update', $producto->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="nombre" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('nombre', $producto->nombre) }}"
                        autocomplete="off"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('nombre')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unidad" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Unidad</label>
                    <input
                        type="text"
                        name="unidad"
                        id="unidad"
                        value="{{ old('unidad', $producto->unidad) }}"
                        autocomplete="off"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('unidad')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="categoria_id" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Categoría</label>
                    <select
                        name="categoria_id"
                        id="categoria_id"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                        <option value="">Sin categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="margen_pct" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Margen (%)</label>
                    <input
                        type="number"
                        name="margen_pct"
                        id="margen_pct"
                        value="{{ old('margen_pct', $producto->margen_pct) }}"
                        step="0.01"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('margen_pct')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        {{-- Sección 2: proveedores de este producto --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="mb-4 text-sm font-bold text-slate-800 dark:text-slate-100">Proveedores de este producto</h2>

            @if ($producto->productoProveedores->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:text-slate-400">
                                <th class="py-2 pr-3 font-medium">Proveedor</th>
                                <th class="py-2 pr-3 font-medium">Precio</th>
                                <th class="py-2 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($producto->productoProveedores as $item)
                                <tr class="border-b border-slate-100 last:border-0 dark:border-slate-800/60">
                                    <td class="py-2 pr-3 text-slate-700 dark:text-slate-300">{{ $item->proveedor->nombre }}</td>
                                    <td class="py-2 pr-3">
                                        <form method="POST" action="{{ route('producto-proveedores.update', $item->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input
                                                type="number"
                                                name="precio"
                                                value="{{ old('precio', $item->precio) }}"
                                                step="0.01"
                                                min="0.01"
                                                required
                                                class="w-28 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                                            >
                                            <button type="submit" class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700">
                                                Actualizar
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-2"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-500 dark:text-slate-400">Este producto todavía no tiene proveedores cargados.</p>
            @endif

            @php
                $proveedorIdsAsignados = $producto->productoProveedores->pluck('proveedor_id');
                $proveedoresDisponibles = $proveedores->whereNotIn('id', $proveedorIdsAsignados);
            @endphp

            @if ($proveedoresDisponibles->count())
                <div class="mt-5 border-t border-slate-200 pt-4 dark:border-slate-800">
                    <h3 class="mb-3 text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Agregar proveedor a este producto</h3>

                    <form method="POST" action="{{ route('producto-proveedores.store', $producto->id) }}" class="flex flex-wrap items-end gap-2">
                        @csrf

                        <div class="flex-1">
                            <label for="proveedor_id" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Proveedor</label>
                            <select
                                name="proveedor_id"
                                id="proveedor_id"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                            >
                                @foreach ($proveedoresDisponibles as $proveedor)
                                    <option value="{{ $proveedor->id }}" @selected(old('proveedor_id') == $proveedor->id)>
                                        {{ $proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                                <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nuevo_precio" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Precio</label>
                            <input
                                type="number"
                                name="precio"
                                id="nuevo_precio"
                                value="{{ old('precio') }}"
                                step="0.01"
                                min="0.01"
                                required
                                class="w-28 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                            >
                            @error('precio')
                                <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                            Agregar
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </main>

</body>
</html>
