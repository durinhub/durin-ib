@extends('boardbase')


@section('titulo', 'Admin')

@section('conteudo')
    <h1>&nbsp;&nbsp;&nbsp;Bem vindo, {{ Auth::user()->name }}</h1><br>
    <div class="container-fluid">
        @foreach($reports as $report)
            <div class="alert alert-success" role="alert">
                <a class="btn btn-danger" style="float: right" href="/deletereport/{{ $report->id }}">Deletar</a><br>
                <strong>Report número: </strong>{{ $report->id }}<br>
                @if($report->lead_id)
                    <strong>Referência: </strong><a href="/boards/{{ $report->board }}/{{ $report->lead_id }}#{{ $report->post_id }}">{{ $report->post_id }}</a><br>
                @else
                    <strong>Referência: </strong><a href="/boards/{{ $report->board }}/{{ $report->post_id }}">{{ $report->post_id }}</a><br>
                @endif
                <strong>Motivo: </strong><p>{{ $report->motivo }}</p>
            </div>
            <hr>
        @endforeach

        @if(isset($configuracaos))
            <div class="admin-page">
                <div class="row">
                    <div class="col-sm-2">
                        
                    <ul id="admin-buttons">
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::SeedDb))
                        <li><a href="/seedar"><button type="button" class="btn btn-success">Seedar</button></a></li>
            @endif
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::LimpaCache))
                        <li><a href="/limparcache"><button type="button" class="btn btn-default">Limpar Cache</button></a></li>
            @endif                        
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::MigrateDb))
                        <li><a href="/migrate"><button type="button" class="btn btn-warning">Migrate</button></a></li>
            @endif            
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ViewPhpInfo))            
                        <li><a href="/phpinfo" target="_blank"><button type="button" class="btn btn-primary">PhpInfo</button></a></li>
            @endif            
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ToggleAdmCookie) && $configuracaos->biscoito_admin_off)
                        <li><a href="/adm_cookie_onoff/0"><button type="button" class="btn btn-success">Ativar biscoito admin</button></a></li>
            @elseif(Auth::user()->canDo(App\Enums\AdminRightsEnum::ToggleAdmCookie) && !$configuracaos->biscoito_admin_off)
                        <li><a href="/adm_cookie_onoff/1"><button type="button" class="btn btn-danger">Desativar biscoito admin</button></a></li>
            @endif
                        <!--<li><a href="/migrate/refresh"><button type="button" class="btn btn-danger">Migrate:refresh + seed</button></a></li>-->

            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ToggleCaptcha))
                @if($configuracaos->captcha_ativado)
                            <li><a href="/togglecaptcha/0"><button type="button" class="btn btn-danger">Desativar captcha</button></a></li>
                @elseif(!$configuracaos->captcha_ativado)
                            <li><a href="/togglecaptcha/1"><button type="button" class="btn btn-primary">Ativar captcha</button></a></li>
                @endif
            @endif
            
            @if($configuracaos->posts_block)
                        <li><a href="/togglepostsblock/0"><button type="button" class="btn btn-primary">Desbloquear novas postagens</button></a></li>
            @elseif(!$configuracaos->posts_block)
                        <li><a href="/togglepostsblock/1"><button type="button" class="btn btn-danger">Bloquear novas postagens</button></a></li>
            @endif                        
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::RegisterAdmin))
                        <li><a href="/register"><button type="button" class="btn btn-primary">Registrar Novo Adm</button></a></li>
            @endif                         
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ManageAds))
                        <li><a href="/gerenciaads"><button type="button" class="btn btn-primary">Gerenciar ads</button></a></li>
            @endif                         
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::SeeActivityLogs))
                        <li><a href="{{ route('activitylogs.list') }}"><button type="button" class="btn btn-success">Logs de atividade</button></a></li>
            @endif  
                        <li>
                        <li><a href="/logout"><button type="button" class="btn btn-danger">Logout</button></a></li>
                        </li>
                    </ul>
                    </div>
                    <div class="col-sm-10"></div>
                </div>
            </div>
        @endif
        <br>
        <div class="row">
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::NoticiasCrud))
            <div class="col-sm-6 div-indice">
                @if(isset($noticiaEditar) && $noticiaEditar != null)
                    <b>Editar noticia</b>
                    <form class="form-post" role="form" method="POST" action="{{ route('noticias.update_noticia') }}">
                        {{ csrf_field() }}
                        <span class="free-text">Título:</span><br>
                        <input type="text" name="assunto" maxlength="256" 
                            value="{{ $noticiaEditar->assunto }}" required><br><br>
                        <span class="free-text">Notícia:</span><br>
                        <textarea rows="6" cols="70" 
                            name="conteudo" maxlength="65535" required>{{ $noticiaEditar->conteudo }}</textarea><br>
                        
                        <input type="hidden" name="id" value="{{ $noticiaEditar->id }}"><br><br>
                        <input type="submit" class="btn btn-primary" value="Editar"><br><br>
                    </form>
                @else
                    <b>Divulgar noticia</b>
                    <form class="form-post" role="form" method="POST" action="{{ route('noticias.nova_noticia') }}">
                        {{ csrf_field() }}
                        <span class="free-text">Título:</span><br>
                        <input type="text" name="assunto" maxlength="256" required><br><br>
                        <span class="free-text">Notícia:</span><br>
                        <textarea rows="6" cols="70" name="conteudo" maxlength="65535" required></textarea><br>
                        <input type="submit" class="btn btn-primary" value="Divulgar"><br><br>
                    </form>
                @endif
            </div>
            @endif
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::CreateBoards))
            <div class="col-sm-6 div-indice">
                <b>Criar nova board</b>
                <form class="form-post" role="form" method="POST" action="{{ route('boards.store') }}">
                    {{ csrf_field() }}
                    <span class="free-text">Nome da board:</span><br>
                    <input type="text" name="nome" maxlength="50" required><br><br>
                    <span class="free-text">/sigla/:</span><br>
                    <input type="text" name="sigla" maxlength="10" required><br><br>
                    <span class="free-text">Descrição:</span><br>
                    <input type="text" name="descricao" maxlength="300" required><br><br>
                    <span class="free-text">Secreta:</span><br>
                    <input type="checkbox" name="secreta" value="secreta"><br><br>
                    <span class="free-text">Ordem:</span><br>
                    <input type="number" name="ordem" max="32767" min="-32767" required><br><br>
                    <input type="submit" class="btn btn-primary" value="Criar board"><br><br>
                </form>
            </div>
            @endif
        </div>
        <div class="row" >
            <div class="col-sm-6 div-indice">
                <b>Definir nova regra</b>
                <form class="form-post" role="form" method="POST" action="{{ route('regras.regra') }}">
                    {{ csrf_field() }}
                    <span class="free-text">Descrição:</span><br>
                    <input type="text" name="descricao" maxlength="256" required><br><br>
                    <span class="free-text">Board:</span><br>
                    <select name="board_name">
                        <option value="todas" selected>todas</option>
                        @foreach($boards as $board)
                        <option value="{{ $board->sigla }}"> {{ $board->sigla }}</option>
                        @endforeach
                    </select>
                    <br>
                    <br>
                    <input type="submit" class="btn btn-primary" value="Criar regra"><br><br>
                </form>
            </div>
            <div class="col-sm-6 div-indice">
                <b>Alterar senha</b>
                <form class="form-post" role="form" method="POST" action="{{ route('users.update_password') }}">
                    {{ csrf_field() }}
                    <span class="free-text">Senha antiga:</span><br>
                    <input type="password" name="old_password" maxlength="25" required><br><br>
                    <span class="free-text">Nova senha:</span><br>
                    <input type="password" name="password" maxlength="25" required><br><br>
                    <span class="free-text">Confirmação nova senha:</span><br>
                    <input type="password" name="confirm_password" maxlength="25" required><br><br>
                    <br>
                    <br>
                    <input type="submit" class="btn btn-primary" value="Alterar senha"><br><br>
                </form>
            </div>
        </div>
        <br>
        <div class="row" >
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ChangeRights) && isset($users))
            <div class="col-sm-6 div-indice">
                <form class="form-post" role="form" method="POST" action="{{ route('users.updatedireitos') }}">
                <h3>Atualiza direitos de admin</h3>
                    {{ csrf_field() }}
                    <span class="free-text">Admin:</span><br>
                        <select name="admin" id="selectadminrights">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}"> {{ $user->name }}</option>
                            @endforeach
                        </select><br>
                        @foreach(App\Enums\AdminRightsEnum::cases() as $right)
                        <span class="free-text">{{ $right->name }}:</span> <input 
                                                class="adminrightcheckbox"
                                                type="checkbox" 
                                                name="checkbox-right-{{ $right->value }}" 
                                                value="{{ $right->value }}"/><br>
                        @endforeach
                        <input type="submit" class="btn btn-primary" value="Atualizar usuário" id="submit-selectadminrights"><br><br>
                </form>
            </div>
            @endif
            @if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ToggleLockUsers) && isset($users))
                <div class="col-sm-6 div-indice">
                    <form class="form-post" role="form" method="POST" action="{{ route('users.togglelockuser') }}">
                    <h3>Bloqueia ou desbloqueia login de admin</h3>
                        {{ csrf_field() }}
                        <span class="free-text">Admin:</span><br>
                            <select name="admin" id="selectadminlock">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}"> {{ $user->name }}</option>
                                @endforeach
                            </select><br>
                            <input name="val" type="hidden" id="hidden-selectadminlock"/><br>
                            <input type="submit" class="btn btn-primary" value="" id="submit-selectadminlock"><br><br>
                    </form>
                </div>
            @endif
        </div>

        @if (Auth::user()->canDo(App\Enums\AdminRightsEnum::ApplyFiltersPastPosts))
            <br>
            <div class="row">
                <div class="col-sm-12 div-indice">
                    <form action="{{ route('posts.filters') }}" method="post">
                        <h3>Aplica filtros nos posts entre IDs</h3>
                        {{ csrf_field() }}
                        Inicio:
                        <input type="number" name="startId" id="startId" min="1" max="999999999999999">
                        Fim:
                        <input type="number" name="endId" id="endId" min="1" max="999999999999999">
                        <input type="submit" class="btn btn-warning" value="Aplicar"><br><br>
                    </form>
                </div>
            </div>
        @endif

    </div> <!-- container-fluid -->
@endsection

@section('scripts')
@if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ChangeRights) )
    <script>
        let updateRightsCheckboxes = function(){
            $("#submit-selectadminrights").prop("disabled",true);
            let adminid = $('#selectadminrights').find(":selected").val();
            $.getJSON(`/getdireitos/${adminid}`, function(data){
                $('.adminrightcheckbox').each(function(index){
                    $(this).prop("checked",  data.includes(Number($(this).val())));
                });
            });

            $("#submit-selectadminrights").prop("disabled",false);
        }
        $('#selectadminrights').on('change', updateRightsCheckboxes);
        $(document).ready(updateRightsCheckboxes);
    </script>
@endif
@if(Auth::user()->canDo(App\Enums\AdminRightsEnum::ToggleLockUsers) )
    <script>
        let updateLockVal = function(){
            $("#submit-selectadminlock").prop("disabled",true);
            let adminid = $('#selectadminlock').find(":selected").val();
            $.getJSON(`/userlocked/${adminid}`, function(data){
                $('#hidden-selectadminlock').prop("checked", Number(data) === 1)
                $('#hidden-selectadminlock').val((Number(data) === 1 ? 0 : 1))
                $('#submit-selectadminlock').val((Number(data) === 1 ? "Desbloquear usuário" : "Bloquear usuário"))
                if(Number(data) === 1)
                    $('#submit-selectadminlock').addClass('btn-primary').removeClass('btn-danger');
                else
                    $('#submit-selectadminlock').addClass('btn-danger').removeClass('btn-primary');
                
            });
            $("#submit-selectadminlock").prop("disabled",false);
        };
        $('#selectadminlock').on('change', updateLockVal);
        $(document).ready(updateLockVal);
    </script>
@endif
@endsection