@extends('layouts.admin')

@section('content')
    <h1>Moficar rol: {{$rol->name}} </h1>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Llene los campos del formulario
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('/admin/rol/'.$rol->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nombre del Rol (*)</label>
                                    <div class="input-group"></div>
                                    <input type="text" name="name" id="name" value="{{ $rol->name }}" class="form-control"
                                        placeholder="Nombre del Rol">
                                </div>

                                @error('name')
                                    <small class="text-danger"> {{ $message }} </small>
                                @enderror
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ url('/admin/roles') }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Actualizar</button>

                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
