@extends('helpers::errors.layout')

@section('title')
    {{ $title ?? __('Invalid License') }}
@endsection

@section('message')
    {{ $message ?? __('Invalid License') }}
@endsection
