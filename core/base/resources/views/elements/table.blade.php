@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', [get_defined_vars()['__data']])
@stop