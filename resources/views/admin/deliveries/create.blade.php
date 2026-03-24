@php
    $pageTitle = 'Nova Entrega';
@endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.deliveries.store') }}">
        @csrf
        @php
            $submitLabel = 'Cadastrar entrega';
        @endphp
        @include('admin.deliveries._form')
    </form>
@endsection
