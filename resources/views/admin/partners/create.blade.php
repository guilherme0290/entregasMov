@php
    $pageTitle = 'Novo Parceiro';
@endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.partners.store') }}">
        @csrf
        @php
            $submitLabel = 'Cadastrar parceiro';
        @endphp
        @include('admin.partners._form')
    </form>
@endsection
