@php use App\Common\Helpers\Html\HtmlHelper; @endphp

@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
@parent
    <p>This is appended to the master sidebar. {{$name}} </p>
    {{HtmlHelper::tag("p", "aaaaaaaaaaaaaaaa")}}
    {{HtmlHelper::tag("p", "bbbbbbbbbbbbbbbbb")}}
    {!! HtmlHelper::tag("p", "ccccccc") !!}
    @php        echo HtmlHelper::tag("p", "cddddddddd")    @endphp
@endsection
@section('content')
    <p>This is my body content.</p>
@endsection
