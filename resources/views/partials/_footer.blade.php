<hr style="margin-top: 150px;">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-center footer-text">
            <img src="/storage/res/logo-ib.png">
            @if($configuracaos->url_repo)
                <p>Este é um projeto de Código Aberto.</p>
                <p>Confira <a href="{{ $configuracaos->url_repo }}" target="_blank">aqui</a> e contribua!</p>
                <p>Tecnologias usadas: <a href="https://getbootstrap.com" target="_blank">Bootstrap</a> 
                - <a href="https://jquery.com/" target="_blank">JQuery</a> 
                - <a href="https://php.net/" target="_blank">PHP</a>
                - <a href="https://laravel.com/" target="_blank">Laravel</a>
                - <a href="https://www.mysql.com/" target="_blank">MySQL</a>
                </p><br>
            @else
            <br><br><br><br>
            @endif
        </div>
    </div>
</div>