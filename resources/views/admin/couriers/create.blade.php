@php
    $pageTitle = 'Novo Entregador';
@endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.couriers.store') }}">
        @csrf
        @php
            $submitLabel = 'Cadastrar entregador';
        @endphp
        @include('admin.couriers._form')
    </form>
@endsection
