@extends('layouts.app')

@section('title', 'Registro de Bodega - Hotel Jardín del Sol')

@section('content')

<style>
    /* Paleta de colores azul Hotel Jardín del Sol */
    :root {
        --primary-color: #E98672;        /* Coral principal */
        --secondary-color: #D4735E;      /* Coral más oscuro */
        --tertiary-color: #F2A898;       /* Coral más claro */
        --accent-color: #C85A47;         /* Coral de acento oscuro */
        --light-blue: #FEF9CB;           /* Crema muy claro */
        --sidebar-bg: #FFFDF5;           /* Fondo sidebar crema suave */
        --hover-bg: #E98672;             /* Color hover */
        --gradient-start: #E98672;       /* Inicio gradiente */
        --gradient-end: #D4735E;         /* Fin gradiente */
    }

    .table-container {
        background: linear-gradient(135deg, #FFFDF5 0%, #FEF9CB 100%);
    }
    
    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(233, 134, 114, 0.1);
    }
    
    .btn-romance {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        transition: all 0.3s ease;
    }
    
    .btn-romance:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(233, 134, 114, 0.3);
    }
    
    .table-row:hover {
        background-color: #FFFDF5;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(233, 134, 114, 0.1);
        transition: all 0.2s ease;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .badge-turno-dia {
        background-color: #fef3c7;
        color: #d97706;
    }

    .badge-turno-noche {
        background-color: #FEF9CB;
        color: #C85A47;
    }

    .badge-comprobante-si {
        background-color: #dcfce7;
        color: #166534;
    }

    .badge-comprobante-no {
        background-color: #f3f4f6;
        color: #374151;
    }

    .stats-card {
        background: white;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(233, 134, 114, 0.2);
    }

    .table-header {
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
    }
</style>

<div class="container mx-auto py-6 px-4">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Registro de Bodega</h1>
            <p class="text-gray-600">Gestiona las ventas de productos por día y turno</p>
        </div>
        <a href="{{ route('pagos-productos.create') }}"
           class="btn-romance text-white px-6 py-3 rounded-lg font-medium shadow-lg">
            <i class='bx bx-plus mr-2'></i>
            Agregar venta
        </a>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="rounded-lg border border-green-300 bg-green-50 p-4 text-green-800 mb-6 shadow-sm">
            <div class="flex items-center">
                <i class='bx bx-check-circle mr-2'></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-300 bg-red-50 p-4 text-red-800 mb-6 shadow-sm">
            <div class="flex items-center mb-2">
                <i class='bx bx-error-circle mr-2'></i>
                Se encontraron errores:
            </div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Barra de filtros y estadísticas -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Filtros de fecha -->
        <div class="lg:col-span-5">
            <form method="GET" action="{{ route('pagos-productos.index') }}" id="filtroForm" class="flex flex-wrap gap-4 items-end">
                <!-- Botones de período rápido -->
                <div class="flex gap-2">
                    <!-- Botón "Hoy" -->
                    <button type="submit" name="filtro" value="hoy"
                        class="px-4 py-2 text-sm rounded-lg font-medium transition-colors duration-200 {{ request('filtro', 'todos') === 'hoy' ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                        style="{{ request('filtro', 'todos') === 'hoy' ? 'background-color: #E98672;' : '' }}">
                        Hoy
                    </button>

                    <!-- Botón "Esta Semana" -->
                    <button type="submit" name="filtro" value="semana"
                        class="px-4 py-2 text-sm rounded-lg font-medium transition-colors duration-200 {{ request('filtro') === 'semana' ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                        style="{{ request('filtro') === 'semana' ? 'background-color: #E98672;' : '' }}">
                        Esta Semana
                    </button>

                    <!-- Botón "Personalizado" -->
                    <button type="button" id="personalizadoBtn"
                        class="px-4 py-2 text-sm rounded-lg font-medium transition-colors duration-200 {{ request('filtro') === 'personalizado' ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                        style="{{ request('filtro') === 'personalizado' ? 'background-color: #E98672;' : '' }}">
                        Personalizado
                    </button>

                    <!-- Botón "Todos" -->
                    <button type="submit" name="filtro" value="todos"
                        class="px-4 py-2 text-sm rounded-lg font-medium transition-colors duration-200 {{ request('filtro', 'todos') === 'todos' ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                        style="{{ request('filtro', 'todos') === 'todos' ? 'background-color: #E98672;' : '' }}">
                        Todos
                    </button>
                </div>
                
                <!-- Campos de fecha personalizada -->
                <div id="fechasPersonalizadas" class="flex gap-2 items-end {{ request('filtro') !== 'personalizado' ? 'hidden' : '' }}">
                    <div>
                        <label for="fecha_inicio" class="block text-xs font-medium text-gray-600 mb-1">Desde</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" 
                            value="{{ request('fecha_inicio') }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-xs font-medium text-gray-600 mb-1">Hasta</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" 
                            value="{{ request('fecha_fin') }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <button type="submit" name="filtro" value="personalizado"
                        class="px-4 py-2 text-white rounded-lg text-sm transition-colors duration-200"
                        style="background-color: #E98672;"
                        onmouseover="this.style.backgroundColor='#D4735E'" 
                        onmouseout="this.style.backgroundColor='#E98672'">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Filtro de turno -->
        <div class="lg:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Turno</label>
            <form method="GET" action="{{ route('pagos-productos.index') }}" id="turnoForm">
                <input type="hidden" name="filtro" value="{{ request('filtro', 'todos') }}">
                <input type="hidden" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                <input type="hidden" name="fecha_fin" value="{{ request('fecha_fin') }}">
                <select name="turno" onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="0" {{ request('turno') === '0' ? 'selected' : '' }}>DÍA</option>
                    <option value="1" {{ request('turno') === '1' ? 'selected' : '' }}>NOCHE</option>
                </select>
            </form>
        </div>
        
        <!-- Búsqueda -->
        <div class="lg:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
            <div class="relative">
                <input type="search" id="searchInput"
                    class="search-input w-full pl-10 pr-4 py-2 text-sm rounded-lg border-2 border-gray-200 focus:outline-none transition-all"
                    placeholder="Producto...">
                <div class="absolute top-0 left-0 inline-flex items-center p-2">
                    <i class='bx bx-search text-gray-400'></i>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas compactas -->
        <div class="lg:col-span-3 grid grid-cols-2 gap-2">
            <div class="stats-card rounded-lg p-3 text-center">
                <div class="text-lg font-bold text-gray-800">{{ $estadisticas['total_ventas'] }}</div>
                <div class="text-xs text-gray-600">Ventas</div>
            </div>
            <div class="stats-card rounded-lg p-3 text-center">
                <div class="text-lg font-bold text-green-600">S/ {{ number_format($estadisticas['total_ingresos'], 2) }}</div>
                <div class="text-xs text-gray-600">Ingresos</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-container rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
            <table class="min-w-full bg-white">
                <thead class="table-header text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Acciones</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Turno</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Precio Unit.</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Método Pago</th>
                        <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Comprobante</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="tableBody">
                    @forelse($ventas as $venta)
                        <tr class="table-row border-l-4 border-transparent hover:border-blue-400" 
                            data-search="{{ strtolower($venta->producto_nombre) }}">
                            
                            <!-- ACCIONES -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('pagos-productos.edit', $venta->id_compra) }}"
                                        class="inline-flex items-center bg-yellow-100 text-yellow-700 px-3 py-1 text-xs rounded-full hover:bg-yellow-200 transition-colors">
                                        <i class='bx bx-edit mr-1'></i>
                                        Editar
                                    </a>
                                    
                                    <form method="POST" action="{{ route('pagos-productos.destroy', $venta->id_compra) }}" 
                                          onsubmit="return confirm('¿Eliminar esta venta?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center bg-red-100 text-red-700 px-3 py-1 text-xs rounded-full hover:bg-red-200 transition-colors">
                                            <i class='bx bx-trash mr-1'></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- FECHA -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $venta->fecha_formateada }}
                            </td>

                            <!-- TURNO -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge {{ $venta->turno == 0 ? 'badge-turno-dia' : 'badge-turno-noche' }}">
                                    <i class='bx {{ $venta->turno == 0 ? "bx-sun" : "bx-moon" }} mr-1'></i>
                                    {{ $venta->turno_nombre }}
                                </span>
                            </td>

                            <!-- PRODUCTO -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $venta->producto_nombre }}</div>
                            </td>

                            <!-- CANTIDAD -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-semibold">
                                {{ $venta->cantidad }}
                            </td>

                            <!-- PRECIO UNITARIO -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                S/ {{ number_format($venta->precio_unitario, 2) }}
                            </td>

                            <!-- TOTAL -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                S/ {{ number_format($venta->total, 2) }}
                            </td>

                            <!-- MÉTODO PAGO -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $venta->met_pago }}
                            </td>

                            <!-- COMPROBANTE -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge {{ $venta->comprobante === 'SI' ? 'badge-comprobante-si' : 'badge-comprobante-no' }}">
                                    {{ $venta->comprobante }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-package text-6xl text-gray-300 mb-4'></i>
                                    <span class="text-lg">No hay ventas registradas.</span>
                                    <p class="text-sm mt-2">Comienza agregando tu primera venta de bodega.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const personalizadoBtn = document.getElementById('personalizadoBtn');
    const fechasPersonalizadas = document.getElementById('fechasPersonalizadas');
    
    // Búsqueda en tiempo real
    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr[data-search]');
            
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (searchData && searchData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Botón personalizado para mostrar/ocultar campos de fecha
    if (personalizadoBtn && fechasPersonalizadas) {
        personalizadoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fechasPersonalizadas.classList.toggle('hidden');
            
            if (fechasPersonalizadas.classList.contains('hidden')) {
                this.classList.remove('bg-blue-600', 'text-white');
                this.classList.add('bg-gray-100', 'text-gray-700');
            } else {
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('bg-blue-600', 'text-white');
            }
        });
    }
});
</script>

@endsection