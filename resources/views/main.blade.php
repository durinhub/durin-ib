<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    
@include('partials._head') <!-- cabeçalho -->
<body>
<div class="snow-container"> 
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
</div>        
</body>
    
</html>