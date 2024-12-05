<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    
@include('partials._head') <!-- cabeçalho -->
<body>

        @include('partials._nav')
        
        @include('partials._msg')
        
        @yield('adstop')
        @yield('conteudo')
        
        
        @yield('adsbottom')
        @include('partials._footer')
        @include('partials._jsincludes')
        
        @yield('scripts')
        
        @yield('styles')
        @include('partials._modalconf')
        
</body>
    
</html>