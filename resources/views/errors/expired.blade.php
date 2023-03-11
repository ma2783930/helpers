@extends('helpers::errors.layout')

@section('title')
    {{ $title ?? __('Application License Expired') }}
@endsection

@section('message')
    {{ $message ?? __('Application License Expired') }}
@endsection
