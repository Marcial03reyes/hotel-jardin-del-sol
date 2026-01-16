@extends('layouts.app')

@section('title', 'Historial - ' . $producto->nombre)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('productos-bodega.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class='bx bx-package mr-2'></i>
                        Productos Bodega
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class='bx bx-chevron-right text-gray-400'></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $producto->nombre }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $producto->nombre }}</h1>
                    <p class="text-gray-600 mt-1">Historial de compras y movimientos</p>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="openStockModal()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-purple-700 transition-colors">
                        <i class='bx bx-edit mr-2'></i>
                        Ajustar Stock Inicial
                    </button>
                    
                    <a href="{{ route('productos-bodega.create-compra', $producto->id_prod_bod) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 transition-colors">
                        <i class='bx bx-plus mr-2'></i>
                        Registrar Compra
                    </a>

                    <a href="{{ route('productos-bodega.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class='bx bx-arrow-back mr-2'></i>
                        Volver
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Stock Actual</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stockActual }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-package text-blue-600 text-xl'></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-600">Rotación</p>
                            <p class="text-2xl font-bold text-orange-900">{{ $rotacionMensual ?? '---' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-time text-orange-600 text-xl'></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Ganancia Mensual</p>
                            <p class="text-2xl font-bold text-green-900">S/ {{ number_format($gananciaMensual ?? 0, 2) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-dollar text-green-600 text-xl'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Historial de Compras</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th> {{-- Nueva columna --}}
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($historialCompras as $compra)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $compra->fecha_compra->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $compra->cantidad }} uds
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">S/ {{ number_format($compra->precio_unitario, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                    S/ {{ number_format($compra->cantidad * $compra->precio_unitario, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $compra->proveedor ?: 'No especificado' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('productos-bodega.edit-compra', [$producto->id_prod_bod, $compra->id_compra_bodega]) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        <form action="{{ route('productos-bodega.destroy-compra', [$producto->id_prod_bod, $compra->id_compra_bodega]) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Eliminar esta compra?')">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <i class='bx bx-receipt text-4xl mb-2'></i>
                                    <p>No hay compras registradas aún.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="stockModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium mb-4">Ajustar Stock Inicial</h3>
            <form method="POST" action="{{ route('productos-bodega.update-producto', $producto->id_prod_bod) }}">
                @csrf @method('PUT')
                <input type="hidden" name="nombre" value="{{ $producto->nombre }}">
                <input type="hidden" name="precio_actual" value="{{ $producto->precio_actual }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Stock Inicial</label>
                    <input type="number" name="stock_inicial" value="{{ $producto->stock_inicial }}" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStockModal()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openStockModal() { document.getElementById('stockModal').classList.remove('hidden'); }
    function closeStockModal() { document.getElementById('stockModal').classList.add('hidden'); }
</script>
@endsection