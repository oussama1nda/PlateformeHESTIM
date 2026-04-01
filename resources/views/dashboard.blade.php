@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard ' . ucfirst(auth()->user()->role))

@section('content')
@if(auth()->user()->isAdmin())
    @include('admin.dashboard')
@elseif(auth()->user()->isEnseignant())
    @include('enseignant.dashboard')
@elseif(auth()->user()->isEtudiant())
    @include('etudiant.dashboard')
@endif
@endsection
