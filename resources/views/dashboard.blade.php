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
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">

    <header class="sticky top-0 z-20 border-b-2 border-indigo-500/60 bg-white/95 backdrop-blur dark:border-indigo-500/30 dark:bg-slate-950/95">
        <div class="mx-auto max-w-6xl px-4 py-2.5">
            <div class="flex items-baseline gap-2">
                <h1 class="text-base font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                    Almacén Viejos
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Lista de precios</p>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl space-y-8 px-4 py-6">

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
            </div>

            {{-- Chip de categoría activa --}}
            <div id="filtro-activo" class="mt-2 hidden items-center gap-2 text-sm">
                <span class="text-slate-500 dark:text-slate-400">Categoría:</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 py-1 pl-2.5 pr-1.5 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                    <span id="filtro-activo-nombre"></span>
                    <button type="button" id="quitar-filtro-categoria" class="rounded-full p-0.5 text-indigo-500 hover:bg-indigo-100 hover:text-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-500/20 dark:hover:text-indigo-100" aria-label="Quitar filtro de categoría">
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
                        <span class="shrink-0 rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400">
                            {{ $categoria->productos->count() }}
                        </span>
                    </div>

                    @if ($categoria->productos->count() > 0)
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4 xl:grid-cols-5">
                            @foreach ($categoria->productos as $producto)
                                <div class="producto-row group flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900" data-nombre="{{ Str::lower($producto->nombre) }}">
                                    <div class="flex aspect-square items-center justify-center bg-slate-100 text-slate-300 dark:bg-slate-800 dark:text-slate-600">
                                        @if ($producto->foto)
                                            <img src="{{ Storage::url($producto->foto) }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <path d="M21 15l-5-5L5 21"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col gap-1.5 p-3">
                                        <p class="producto-nombre line-clamp-2 text-sm font-medium leading-snug text-slate-800 dark:text-slate-100">{{ $producto->nombre }}</p>
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

        <div>
            {{ $categorias->links() }}
        </div>

    </main>

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

            let categoriaSeleccionada = '';

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
                        const coincide = !query || fila.dataset.nombre.includes(query);
                        fila.classList.toggle('hidden', !coincide);
                        if (coincide) categoriaTieneMatch = true;

                        const nombreSpan = fila.querySelector('.producto-nombre');
                        if (nombreSpan) resaltar(nombreSpan, coincide ? query : '');
                    });

                    // Una categoría vacía de origen siempre se muestra. Una categoría con productos que se
                    // queda sin ningún match tras el filtro de texto se oculta.
                    const visible = esVacia || !query || categoriaTieneMatch;
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

            buscador.addEventListener('input', function () {
                aplicarFiltros();
                scrollAlPrimerResultado();
            });
        })();
    </script>

</body>
</html>
