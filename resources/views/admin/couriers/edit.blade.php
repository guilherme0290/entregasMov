@php
    $pageTitle = 'Editar Entregador';
@endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.couriers.update', $courier) }}">
        @csrf
        @method('PUT')
        @php
            $submitLabel = 'Salvar alterações';
        @endphp
        @include('admin.couriers._form')
    </form>
@endsection
