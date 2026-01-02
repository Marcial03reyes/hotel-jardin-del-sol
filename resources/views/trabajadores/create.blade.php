@extends('layouts.app')

@section('title', 'Agregar trabajador - Hotel Jardín del Sol')

@section('content')

<style>

    /* Paleta de colores Hotel Jardín del Sol */
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
    
    .btn-romance {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        color: white;
        transition: all 0.3s ease;
    }

    .btn-romance:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(233, 134, 114, 0.3);
        color: white;
    }

    input:focus, select:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(233, 134, 114, 0.1) !important;
        outline: none;
    }

</style>

<div class="max-w-xl space-y-4">
    <h1 class="text-xl font-semibold">Agregar trabajador</h1>

    @if ($errors->any())
        <div class="rounded-lg border border-red-300 bg-red-50 p-3 text-red-800">
            <ul class="list-disc ps-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trabajadores.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">DNI/CE</label>
            <input name="DNI" type="text" value="{{ old('DNI') }}" class="w-full rounded-lg border px-3 py-2" required maxlength="20">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nombre y Apellido</label>
            <input name="nombre_apellido" type="text" value="{{ old('nombre_apellido') }}" class="w-full rounded-lg border px-3 py-2" required maxlength="100">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Sueldo</label>
                <input name="sueldo" type="number" step="0.01" value="{{ old('sueldo') }}" class="w-full rounded-lg border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha inicio</label>
                <input name="Fecha_inicio" type="date" value="{{ old('Fecha_inicio') }}" class="w-full rounded-lg border px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                <i class='bx bx-cake mr-1'></i>
                Fecha de Cumpleaños
            </label>
            <input name="fecha_cumple" type="date" value="{{ old('fecha_cumple') }}" class="w-full rounded-lg border px-3 py-2">
            <small class="text-gray-500 text-xs mt-1">Campo opcional</small>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Teléfono</label>
            <input name="Telef" type="text" value="{{ old('Telef') }}" class="w-full rounded-lg border px-3 py-2" required maxlength="9">
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('trabajadores.index') }}" 
            class="rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-2 text-sm transition-colors">
                Cancelar
            </a>
            <button type="submit" class="btn-romance rounded-lg px-3 py-2 text-sm">
                Guardar
            </button>
        </div>

    </form>
</div>

@endsection