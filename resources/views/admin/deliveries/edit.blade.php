@php
    $pageTitle = 'Editar Entrega';
@endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.deliveries.update', $delivery) }}">
        @csrf
        @method('PUT')
        @php
            $submitLabel = 'Salvar alterações';
        @endphp
        @include('admin.deliveries._form')
    </form>
@endsection
