@extends('layouts.portal')

@section('content')
    <div id="root"></div>
    <script>
        window.__SIMANDAT__ = @js($simandat);
    </script>
@endsection
