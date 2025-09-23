@extends('template')

@section('title', 'Marcas')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

@if (session('success'))
<script>
    let message = "{{ session('success') }}";
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: message,
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Marcas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Marcas</li>
    </ol>

    <div class="mb-4">
        <a href="{{ route('marcas.create') }}">
            <button class="btn btn-primary">Añadir nuevo registro</button>
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla Marcas
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $marcas as $marca)
                    <tr>
                        <td>{{ $marca->caracteristica->nombre }}</td>
                        <td>{{ $marca->caracteristica->descripcion }}</td>
                        <td>
                            @if ( $marca->caracteristica->estado == 1 )
                                <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>
                            @else
                                 <span class="fw-bolder p-1 rounded bg-danger text-white">Eliminado</span>
                            @endif
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <form action="{{ route('marcas.edit', ['marca' => $marca]) }}" method="get">
                                    <button type="submit" class="btn btn-warning">Editar</button>
                                </form>
                                @if ($marca->caracteristica->estado == 1)
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $marca->id }}">Eliminar</button>
                                @else
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $marca->id }}">Restaurar</button>
                                @endif
                                
                                
                            </div>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="confirmModal-{{ $marca->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $marca->caracteristica->estado == 1 ? '¿está seguro de eliminar este registro?' : '¿está seguro de restaurar este registro?' }} -
                                    {{ $marca->id }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('marcas.destroy', ['marca' => $marca->id]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                    </form>  
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush