@php
    $pageTitle = 'Editar Parceiro';
@endphp
@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.partners.update', $partner) }}">
        @csrf
        @method('PUT')
        @php
            $submitLabel = 'Salvar alterações';
        @endphp
        @include('admin.partners._form')
    </form>
@endsection
