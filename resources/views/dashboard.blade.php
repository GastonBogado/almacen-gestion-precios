<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard de precios · Almacén Viejos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .categoria-filtro-item-activo {
            background-color: rgb(79 70 229);
            color: #fff;
        }
        .categoria-filtro-item-activo:hover {
            background-color: rgb(79 70 229);
            color: #fff;
        }
        .categoria-filtro-item-activo .categoria-filtro-badge {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .proveedor-filtro-item-activo {
            background-color: rgb(79 70 229);
            color: #fff;
        }
        .proveedor-filtro-item-activo:hover {
            background-color: rgb(79 70 229);
            color: #fff;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">

    <header class="sticky top-0 z-20 border-b-2 border-indigo-500/60 bg-white/95 backdrop-blur dark:border-indigo-500/30 dark:bg-slate-950/95">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-2 px-4 py-2.5">
            <div class="flex items-baseline gap-2">
                <h1 class="text-base font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                    Almacén Viejos
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Lista de precios</p>
            </div>

            <div class="flex shrink-0 items-center gap-2">
               @can('admin') <button
                    type="button"
                    id="btn-nueva-categoria"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva categoría
                </button>

                <button
                    type="button"
                    id="btn-nuevo-producto"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo producto
                </button>

                <button
                    type="button"
                    id="btn-nuevo-proveedor"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo proveedor
                </button>
            @endcan
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-colors hover:border-rose-300 hover:bg-rose-50 hover:text-rose-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-rose-500/40 dark:hover:bg-rose-500/10 dark:hover:text-rose-400"
                        aria-label="Cerrar sesión"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m0-8H5a2 2 0 00-2 2v12a2 2 0 002 2h2"></path>
                        </svg>
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl space-y-8 px-4 py-6">

        @if (session('success'))
            <div class="mx-auto w-full max-w-2xl rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-auto w-full max-w-2xl rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-400">
                {{ session('error') }}
            </div>
        @endif

        {{-- Buscador + filtro de categoría --}}
        <div class="mx-auto w-full max-w-2xl">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <label for="buscador" class="sr-only">Buscar producto</label>
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3.5-3.5"></path>
                    </svg>
                    <input
                        id="buscador"
                        type="search"
                        inputmode="search"
                        placeholder="Buscar producto por nombre..."
                        class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500"
                    >
                </div>

                <div class="relative shrink-0">
                    <button
                        type="button"
                        id="btn-filtro"
                        aria-expanded="false"
                        aria-controls="panel-filtro-categoria"
                        class="relative flex h-full items-center justify-center rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-600 shadow-sm transition-colors hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-indigo-500/40 dark:hover:bg-slate-800 dark:hover:text-indigo-300"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8.5V19l-4 2v-8.5L3 4z"></path>
                        </svg>
                        <span id="btn-filtro-dot" class="absolute -right-1 -top-1 hidden h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                    </button>

                    <div id="backdrop-filtro" class="fixed inset-0 z-30 hidden"></div>

                    <div id="panel-filtro-categoria" class="absolute right-0 z-40 mt-2 hidden max-h-80 w-56 overflow-y-auto rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        <button type="button" class="categoria-filtro-item flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300" data-categoria-id="" data-nombre="Todas las categorías">
                            <span class="truncate">Todas las categorías</span>
                        </button>
                        @foreach ($categorias as $categoria)
                            <button type="button" class="categoria-filtro-item flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300" data-categoria-id="{{ $categoria->id }}" data-nombre="{{ $categoria->nombre }}">
                                <span class="truncate">{{ $categoria->nombre }}</span>
                                <span class="categoria-filtro-badge shrink-0 rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                                    {{ $categoria->productos->count() }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="relative shrink-0">
                    <button
                        type="button"
                        id="btn-filtro-proveedor"
                        aria-expanded="false"
                        aria-controls="panel-filtro-proveedor"
                        class="relative flex h-full items-center justify-center rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-600 shadow-sm transition-colors hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-indigo-500/40 dark:hover:bg-slate-800 dark:hover:text-indigo-300"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h11v8H3zM14 10h4l3 3v2h-7zM6 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM17 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                        <span id="btn-filtro-proveedor-dot" class="absolute -right-1 -top-1 hidden h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                    </button>

                    <div id="backdrop-filtro-proveedor" class="fixed inset-0 z-30 hidden"></div>

                    <div id="panel-filtro-proveedor" class="absolute right-0 z-40 mt-2 hidden max-h-80 w-56 overflow-y-auto rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        <button type="button" class="proveedor-filtro-item flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300" data-proveedor-id="" data-nombre="Todos los proveedores">
                            <span class="truncate">Todos los proveedores</span>
                        </button>
                        @foreach ($proveedores as $proveedor)
                            <button type="button" class="proveedor-filtro-item flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 transition-colors hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-300" data-proveedor-id="{{ $proveedor->id }}" data-nombre="{{ $proveedor->nombre }}">
                                <span class="truncate">{{ $proveedor->nombre }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Chips de filtros activos --}}
            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                <span id="filtro-activo" class="hidden items-center gap-1.5 rounded-full bg-indigo-50 py-1 pl-2.5 pr-1.5 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <span class="text-indigo-400 dark:text-indigo-500">Categoría:</span>
                    <span id="filtro-activo-nombre"></span>
                    <button type="button" id="quitar-filtro-categoria" class="rounded-full p-0.5 text-indigo-500 hover:bg-indigo-100 hover:text-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-500/20 dark:hover:text-indigo-100" aria-label="Quitar filtro de categoría">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"></path>
                        </svg>
                    </button>
                </span>

                <span id="filtro-activo-proveedor" class="hidden items-center gap-1.5 rounded-full bg-indigo-50 py-1 pl-2.5 pr-1.5 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <span class="text-indigo-400 dark:text-indigo-500">Proveedor:</span>
                    <span id="filtro-activo-proveedor-nombre"></span>
                    <button type="button" id="quitar-filtro-proveedor" class="rounded-full p-0.5 text-indigo-500 hover:bg-indigo-100 hover:text-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-500/20 dark:hover:text-indigo-100" aria-label="Quitar filtro de proveedor">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"></path>
                        </svg>
                    </button>
                </span>
            </div>
        </div>

        {{-- Precios por categoría --}}
        <div id="lista-categorias" class="space-y-8">
            @forelse ($categorias as $categoria)
                <section class="categoria-card" data-categoria-id="{{ $categoria->id }}" data-vacia="{{ $categoria->productos->count() === 0 ? '1' : '0' }}">
                    <div class="mb-3 flex items-center justify-between gap-2 border-b border-slate-200 pb-1.5 dark:border-slate-800">
                        <h2 class="text-sm font-bold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">{{ $categoria->nombre }}</h2>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                                {{ $categoria->productos->count() }}
                            </span>
                            @can('admin')
                            <form method="POST" action="{{ route('categorias.delete', $categoria->id) }}" class="form-borrar-categoria">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600 dark:text-slate-500 dark:hover:bg-rose-500/10 dark:hover:text-rose-400" aria-label="Borrar categoría {{ $categoria->nombre }}">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"></path>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    @if ($categoria->productos->count() > 0)
                        <div class="grid grid-cols-3 gap-2.5 sm:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7">
                            @foreach ($categoria->productos as $producto)
                                <div class="producto-row group flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900" data-nombre="{{ Str::lower($producto->nombre) }}" data-proveedores="{{ $producto->productoProveedores->pluck('proveedor_id')->implode(',') }}">
                                    <div class="relative flex aspect-square items-center justify-center bg-slate-100 text-slate-300 dark:bg-slate-800 dark:text-slate-600">
                                      @can('admin')
                                        <div class="absolute right-1 top-1 z-10 flex items-center gap-1">
                                            <a href="{{ route('productos.edit', $producto->id) }}" class="rounded-full bg-indigo-600 p-1 text-white shadow-sm hover:bg-indigo-700" aria-label="Editar producto {{ $producto->nombre }}">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4h4.5M4 20l4.5-1L18.5 8.5a1.5 1.5 0 000-2.121L17.121 5A1.5 1.5 0 0015 5L5 15l-1 5z"></path>
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('productos.delete', $producto->id) }}" class="form-borrar-producto">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full bg-rose-600 p-1 text-white shadow-sm hover:bg-rose-700" aria-label="Borrar producto {{ $producto->nombre }}">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        @endcan
                                        @if ($producto->foto)
                                            <img src="{{ Storage::url($producto->foto) }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <path d="M21 15l-5-5L5 21"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col gap-1.5 p-3">
                                        <p class="producto-nombre line-clamp-2 text-sm font-medium leading-snug text-slate-800 dark:text-slate-100">{{ $producto->nombre }}</p>

                                        @if ($producto->productoProveedores->count())
                                            <p class="truncate text-[11px] text-slate-500 dark:text-slate-400">
                                                {{ $producto->productoProveedores->pluck('proveedor.nombre')->implode(', ') }}
                                            </p>
                                        @endif

                                        <p class="mt-auto text-lg font-extrabold text-emerald-600 dark:text-emerald-400">
                                            ${{ number_format($producto->precio_venta_calculado ?? 0, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">Sin productos cargados.</p>
                    @endif
                </section>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                    Todavía no hay categorías cargadas.
                </p>
            @endforelse
        </div>

        <p id="sin-resultados" class="hidden rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
            No se encontraron productos que coincidan con la búsqueda.
        </p>

    </main>

    {{-- Modal: nueva categoría --}}
    <div id="modal-nueva-categoria" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div id="modal-nueva-categoria-backdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-xl dark:border-slate-800 dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Nueva categoría</h2>
                <button type="button" id="btn-cerrar-modal-categoria" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Cerrar">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('categorias.store') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="_form" value="categoria">
                <div>
                    <label for="nombre" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('_form') === 'categoria' ? old('nombre') : '' }}"
                        autocomplete="off"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('nombre')
                        @if (old('_form') === 'categoria')
                            <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="btn-cancelar-categoria" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Crear
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: nuevo producto --}}
    <div id="modal-nuevo-producto" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div id="modal-nuevo-producto-backdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-xl dark:border-slate-800 dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Nuevo producto</h2>
                <button type="button" id="btn-cerrar-modal-producto" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Cerrar">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('productos.store') }}" class="max-h-[75vh] space-y-3 overflow-y-auto pr-1">
                @csrf
                <input type="hidden" name="_form" value="producto">

                <div>
                    <label for="producto_nombre" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="producto_nombre"
                        value="{{ old('_form') === 'producto' ? old('nombre') : '' }}"
                        autocomplete="off"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('nombre')
                        @if (old('_form') === 'producto')
                            <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                <div>
                    <label for="producto_unidad" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Unidad</label>
                    <input
                        type="text"
                        name="unidad"
                        id="producto_unidad"
                        value="{{ old('unidad') }}"
                        autocomplete="off"
                        placeholder="kg, unidad, litro..."
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('unidad')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="producto_categoria_id" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Categoría</label>
                    <select
                        name="categoria_id"
                        id="producto_categoria_id"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                        <option value="">Sin categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="producto_margen_pct" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Margen (%)</label>
                    <input
                        type="number"
                        name="margen_pct"
                        id="producto_margen_pct"
                        value="{{ old('margen_pct', 40) }}"
                        step="0.01"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('margen_pct')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="producto_proveedor_id" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Proveedor</label>
                    <select
                        name="proveedor_id"
                        id="producto_proveedor_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                        @foreach ($proveedores as $proveedor)
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
                    <label for="producto_precio" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Precio de este proveedor</label>
                    <input
                        type="number"
                        name="precio"
                        id="producto_precio"
                        value="{{ old('precio') }}"
                        step="0.01"
                        min="0.01"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('precio')
                        <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="btn-cancelar-producto" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Crear producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: nuevo proveedor --}}
    <div id="modal-nuevo-proveedor" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div id="modal-nuevo-proveedor-backdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-xl dark:border-slate-800 dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Nuevo proveedor</h2>
                <button type="button" id="btn-cerrar-modal-proveedor" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Cerrar">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('proveedores.store') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="_form" value="proveedor">
                <div>
                    <label for="proveedor_nombre" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="proveedor_nombre"
                        value="{{ old('_form') === 'proveedor' ? old('nombre') : '' }}"
                        autocomplete="off"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    >
                    @error('nombre')
                        @if (old('_form') === 'proveedor')
                            <p class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="btn-cancelar-proveedor" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Crear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const buscador = document.getElementById('buscador');
            const categorias = Array.from(document.querySelectorAll('.categoria-card'));
            const sinResultados = document.getElementById('sin-resultados');

            const btnFiltro = document.getElementById('btn-filtro');
            const btnFiltroDot = document.getElementById('btn-filtro-dot');
            const panelFiltro = document.getElementById('panel-filtro-categoria');
            const backdropFiltro = document.getElementById('backdrop-filtro');
            const categoriaItems = Array.from(document.querySelectorAll('.categoria-filtro-item'));
            const filtroActivo = document.getElementById('filtro-activo');
            const filtroActivoNombre = document.getElementById('filtro-activo-nombre');
            const quitarFiltroCategoria = document.getElementById('quitar-filtro-categoria');

            const btnFiltroProveedor = document.getElementById('btn-filtro-proveedor');
            const btnFiltroProveedorDot = document.getElementById('btn-filtro-proveedor-dot');
            const panelFiltroProveedor = document.getElementById('panel-filtro-proveedor');
            const backdropFiltroProveedor = document.getElementById('backdrop-filtro-proveedor');
            const proveedorItems = Array.from(document.querySelectorAll('.proveedor-filtro-item'));
            const filtroActivoProveedor = document.getElementById('filtro-activo-proveedor');
            const filtroActivoProveedorNombre = document.getElementById('filtro-activo-proveedor-nombre');
            const quitarFiltroProveedor = document.getElementById('quitar-filtro-proveedor');

            let categoriaSeleccionada = '';
            let proveedorSeleccionado = '';

            function escapeHtml(str) {
                return str.replace(/[&<>"']/g, function (c) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                });
            }

            function resaltar(span, query) {
                const original = span.dataset.original;
                if (!query) {
                    span.innerHTML = escapeHtml(original);
                    return;
                }
                const idx = original.toLowerCase().indexOf(query);
                if (idx === -1) {
                    span.innerHTML = escapeHtml(original);
                    return;
                }
                const antes = escapeHtml(original.slice(0, idx));
                const coincidencia = escapeHtml(original.slice(idx, idx + query.length));
                const despues = escapeHtml(original.slice(idx + query.length));
                span.innerHTML = antes + '<mark class="rounded bg-amber-200 px-0.5 text-amber-900 dark:bg-amber-500/40 dark:text-amber-100">' + coincidencia + '</mark>' + despues;
            }

            // Guarda el texto original de cada producto para poder resaltar/restaurar sin acumular <mark>.
            document.querySelectorAll('.producto-nombre').forEach(function (span) {
                span.dataset.original = span.textContent;
            });

            function aplicarFiltros() {
                const query = buscador.value.trim().toLowerCase();
                let algunaVisible = false;

                categorias.forEach(function (categoria) {
                    // La categoría solo participa si coincide con la elegida en el panel de filtro (o no hay ninguna elegida).
                    if (categoriaSeleccionada && categoria.dataset.categoriaId !== categoriaSeleccionada) {
                        categoria.classList.add('hidden');
                        return;
                    }

                    const esVacia = categoria.dataset.vacia === '1';
                    const filas = Array.from(categoria.querySelectorAll('.producto-row'));
                    let categoriaTieneMatch = false;

                    filas.forEach(function (fila) {
                        const coincideTexto = !query || fila.dataset.nombre.includes(query);
                        const proveedoresFila = (fila.dataset.proveedores || '').split(',').filter(Boolean);
                        const coincideProveedor = !proveedorSeleccionado || proveedoresFila.includes(proveedorSeleccionado);
                        const coincide = coincideTexto && coincideProveedor;
                        fila.classList.toggle('hidden', !coincide);
                        if (coincide) categoriaTieneMatch = true;

                        const nombreSpan = fila.querySelector('.producto-nombre');
                        if (nombreSpan) resaltar(nombreSpan, coincide ? query : '');
                    });

                    // Una categoría vacía de origen siempre se muestra. Una categoría con productos que se
                    // queda sin ningún match tras los filtros activos (texto y/o proveedor) se oculta.
                    const hayFiltrosActivos = !!query || !!proveedorSeleccionado;
                    const visible = esVacia || !hayFiltrosActivos || categoriaTieneMatch;
                    categoria.classList.toggle('hidden', !visible);
                    if (visible) algunaVisible = true;
                });

                sinResultados.classList.toggle('hidden', algunaVisible);
            }

            function primeraCategoriaConMatch() {
                return categorias.find(function (categoria) {
                    if (categoria.classList.contains('hidden')) return false;
                    return categoria.querySelector('.producto-row:not(.hidden)') !== null;
                });
            }

            // Debounced: si el usuario sigue escribiendo, se cancela el scroll pendiente y se reprograma,
            // para no pelear contra si misma la animacion de scroll en cada tecla.
            let scrollTimeoutId = null;

            function scrollAlPrimerResultado() {
                if (scrollTimeoutId) {
                    clearTimeout(scrollTimeoutId);
                    scrollTimeoutId = null;
                }

                const query = buscador.value.trim();
                if (!query) return;

                scrollTimeoutId = setTimeout(function () {
                    const objetivo = primeraCategoriaConMatch();
                    if (!objetivo) return;

                    const header = document.querySelector('header');
                    const headerAltura = header ? header.offsetHeight : 0;
                    const destino = objetivo.getBoundingClientRect().top + window.scrollY - headerAltura - 12;
                    window.scrollTo({ top: Math.max(destino, 0), behavior: 'smooth' });
                }, 250);
            }

            function abrirPanelFiltro() {
                cerrarPanelFiltroProveedor();
                panelFiltro.classList.remove('hidden');
                backdropFiltro.classList.remove('hidden');
                btnFiltro.setAttribute('aria-expanded', 'true');
            }

            function cerrarPanelFiltro() {
                panelFiltro.classList.add('hidden');
                backdropFiltro.classList.add('hidden');
                btnFiltro.setAttribute('aria-expanded', 'false');
            }

            function actualizarCategoriaActiva() {
                categoriaItems.forEach(function (item) {
                    const activo = (item.dataset.categoriaId || '') === categoriaSeleccionada;
                    item.classList.toggle('categoria-filtro-item-activo', activo);
                });
            }

            function seleccionarCategoria(id, nombre) {
                categoriaSeleccionada = id || '';
                actualizarCategoriaActiva();

                if (categoriaSeleccionada) {
                    filtroActivoNombre.textContent = nombre;
                    filtroActivo.classList.remove('hidden');
                    filtroActivo.classList.add('flex');
                    btnFiltroDot.classList.remove('hidden');
                } else {
                    filtroActivo.classList.add('hidden');
                    filtroActivo.classList.remove('flex');
                    btnFiltroDot.classList.add('hidden');
                }

                cerrarPanelFiltro();
                aplicarFiltros();
            }

            btnFiltro.addEventListener('click', function () {
                const abierto = btnFiltro.getAttribute('aria-expanded') === 'true';
                if (abierto) {
                    cerrarPanelFiltro();
                } else {
                    abrirPanelFiltro();
                }
            });

            backdropFiltro.addEventListener('click', cerrarPanelFiltro);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') cerrarPanelFiltro();
            });

            categoriaItems.forEach(function (item) {
                item.addEventListener('click', function () {
                    seleccionarCategoria(item.dataset.categoriaId, item.dataset.nombre);
                });
            });

            quitarFiltroCategoria.addEventListener('click', function () {
                seleccionarCategoria('', '');
            });

            // Filtro por proveedor (misma mecánica que el de categoría)
            function abrirPanelFiltroProveedor() {
                cerrarPanelFiltro();
                panelFiltroProveedor.classList.remove('hidden');
                backdropFiltroProveedor.classList.remove('hidden');
                btnFiltroProveedor.setAttribute('aria-expanded', 'true');
            }

            function cerrarPanelFiltroProveedor() {
                panelFiltroProveedor.classList.add('hidden');
                backdropFiltroProveedor.classList.add('hidden');
                btnFiltroProveedor.setAttribute('aria-expanded', 'false');
            }

            function actualizarProveedorActivo() {
                proveedorItems.forEach(function (item) {
                    const activo = (item.dataset.proveedorId || '') === proveedorSeleccionado;
                    item.classList.toggle('proveedor-filtro-item-activo', activo);
                });
            }

            function seleccionarProveedor(id, nombre) {
                proveedorSeleccionado = id || '';
                actualizarProveedorActivo();

                if (proveedorSeleccionado) {
                    filtroActivoProveedorNombre.textContent = nombre;
                    filtroActivoProveedor.classList.remove('hidden');
                    filtroActivoProveedor.classList.add('flex');
                    btnFiltroProveedorDot.classList.remove('hidden');
                } else {
                    filtroActivoProveedor.classList.add('hidden');
                    filtroActivoProveedor.classList.remove('flex');
                    btnFiltroProveedorDot.classList.add('hidden');
                }

                cerrarPanelFiltroProveedor();
                aplicarFiltros();
            }

            btnFiltroProveedor.addEventListener('click', function () {
                const abierto = btnFiltroProveedor.getAttribute('aria-expanded') === 'true';
                if (abierto) {
                    cerrarPanelFiltroProveedor();
                } else {
                    abrirPanelFiltroProveedor();
                }
            });

            backdropFiltroProveedor.addEventListener('click', cerrarPanelFiltroProveedor);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') cerrarPanelFiltroProveedor();
            });

            proveedorItems.forEach(function (item) {
                item.addEventListener('click', function () {
                    seleccionarProveedor(item.dataset.proveedorId, item.dataset.nombre);
                });
            });

            quitarFiltroProveedor.addEventListener('click', function () {
                seleccionarProveedor('', '');
            });

            buscador.addEventListener('input', function () {
                aplicarFiltros();
                scrollAlPrimerResultado();
            });

            // Modal: nueva categoría
            const btnNuevaCategoria = document.getElementById('btn-nueva-categoria');
            const modalCategoria = document.getElementById('modal-nueva-categoria');
            const modalCategoriaBackdrop = document.getElementById('modal-nueva-categoria-backdrop');
            const btnCerrarModalCategoria = document.getElementById('btn-cerrar-modal-categoria');
            const btnCancelarCategoria = document.getElementById('btn-cancelar-categoria');
            const inputNombreCategoria = document.getElementById('nombre');

            function abrirModalCategoria() {
                modalCategoria.classList.remove('hidden');
                modalCategoria.classList.add('flex');
                inputNombreCategoria.focus();
            }

            function cerrarModalCategoria() {
                modalCategoria.classList.add('hidden');
                modalCategoria.classList.remove('flex');
            }

            btnNuevaCategoria.addEventListener('click', abrirModalCategoria);
            modalCategoriaBackdrop.addEventListener('click', cerrarModalCategoria);
            btnCerrarModalCategoria.addEventListener('click', cerrarModalCategoria);
            btnCancelarCategoria.addEventListener('click', cerrarModalCategoria);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') cerrarModalCategoria();
            });

            // Modal: nuevo producto
            const btnNuevoProducto = document.getElementById('btn-nuevo-producto');
            const modalProducto = document.getElementById('modal-nuevo-producto');
            const modalProductoBackdrop = document.getElementById('modal-nuevo-producto-backdrop');
            const btnCerrarModalProducto = document.getElementById('btn-cerrar-modal-producto');
            const btnCancelarProducto = document.getElementById('btn-cancelar-producto');
            const inputNombreProducto = document.getElementById('producto_nombre');

            function abrirModalProducto() {
                modalProducto.classList.remove('hidden');
                modalProducto.classList.add('flex');
                inputNombreProducto.focus();
            }

            function cerrarModalProducto() {
                modalProducto.classList.add('hidden');
                modalProducto.classList.remove('flex');
            }

            btnNuevoProducto.addEventListener('click', abrirModalProducto);
            modalProductoBackdrop.addEventListener('click', cerrarModalProducto);
            btnCerrarModalProducto.addEventListener('click', cerrarModalProducto);
            btnCancelarProducto.addEventListener('click', cerrarModalProducto);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') cerrarModalProducto();
            });

            // Modal: nuevo proveedor
            const btnNuevoProveedor = document.getElementById('btn-nuevo-proveedor');
            const modalProveedor = document.getElementById('modal-nuevo-proveedor');
            const modalProveedorBackdrop = document.getElementById('modal-nuevo-proveedor-backdrop');
            const btnCerrarModalProveedor = document.getElementById('btn-cerrar-modal-proveedor');
            const btnCancelarProveedor = document.getElementById('btn-cancelar-proveedor');
            const inputNombreProveedor = document.getElementById('proveedor_nombre');

            function abrirModalProveedor() {
                modalProveedor.classList.remove('hidden');
                modalProveedor.classList.add('flex');
                inputNombreProveedor.focus();
            }

            function cerrarModalProveedor() {
                modalProveedor.classList.add('hidden');
                modalProveedor.classList.remove('flex');
            }

            btnNuevoProveedor.addEventListener('click', abrirModalProveedor);
            modalProveedorBackdrop.addEventListener('click', cerrarModalProveedor);
            btnCerrarModalProveedor.addEventListener('click', cerrarModalProveedor);
            btnCancelarProveedor.addEventListener('click', cerrarModalProveedor);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') cerrarModalProveedor();
            });

            @if (old('_form') === 'categoria' && $errors->any())
                abrirModalCategoria();
            @elseif (old('_form') === 'producto' && $errors->any())
                abrirModalProducto();
            @elseif (old('_form') === 'proveedor' && $errors->any())
                abrirModalProveedor();
            @endif

            document.querySelectorAll('.form-borrar-categoria').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    if (!confirm('¿Borrar esta categoría? Esta acción no se puede deshacer.')) {
                        e.preventDefault();
                    }
                });
            });

            document.querySelectorAll('.form-borrar-producto').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    if (!confirm('¿Borrar este producto? Esta acción no se puede deshacer.')) {
                        e.preventDefault();
                    }
                });
            });
        })();
    </script>

</body>
</html>
