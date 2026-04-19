@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Daily Dashboard')

@section('content')
{{-- Immediately redirect to daily log for today --}}
<script>window.location = "{{ route('logs.daily') }}";</script>
@endsection
