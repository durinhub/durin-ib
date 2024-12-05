var addNovoInputFile = function(elem, max){
    fileInputs = $('.form-post-file-input');
    for(var i=0; i < fileInputs.length; i++)
    {
        if(fileInputs[i].files.length === 0)
            return;
    }
    
    if(fileInputs.length >= max || elem.files.length === 0)
        return;
    
    var novoInput = "<div class=\"form-post-file-input-box\">";
    novoInput += "<input class=\"novo-post-form-item form-post-file-input\" name=\"arquivos[]\" type=\"file\" onchange=\"addNovoInputFile(this, " + max + ")\">";
    novoInput += "Spoiler <input name=\"arquivos-spoiler-" + (fileInputs.length + 1) + "\" type=\"checkbox\" value=\"spoiler\">"
    novoInput += "</div>"
    
    $(novoInput).appendTo(".form-post #form-post-file-input-div");
    setaTema(localStorage.getItem('tema'));
};

var criaÇandom = function()
{
    $('body').css('background-image', 'url(/storage/res/çandom.gif)')
    var $audioElement = $("<audio>");
    $audioElement.attr({
        'src': '/storage/res/çandom.mp3',
        'autoplay':'autoplay',
        'loop':'loop'
    });
}

var aplicaRegexStringPost = function(conteudo){
    var res = conteudo.replace(/\*{2}(.*)\*{2}/g, '<span class="spoiler">$1</span>'); // add spoiler
    res = res.replace(/~{2}(.*)~{2}/g, '<s>$1</s>'); // add traço
    res = res.replace(/'{3}(.*)'{3}/g, '<b>$1</b>'); // add negrito
    res = res.replace(/'{2}(.*)'{2}/g, '<i>$1</i>'); // add itálico
    res = res.replace(/={2}(.*)={2}/g, '<span class="vermelhotexto">$1</span>'); // add texto vermelho
    res = res.replace(/!{2}(.*)!{2}/g, '<span class="rainbow">$1</span>'); // add texto vermelho
    res = res.replace(/&gt;(.+)\n?/g, '<span class="green-text">&gt;$1</span><br>'); // add texto verde
    res = res.replace(/\^\^(.+)\^\^\n?/g, '<span class="orange-text">$1</span><br>'); // add texto verde
    res = res.replace(/&lt;(.+)\n?/g, '<span class="pink-text">&lt;$1</span><br>'); // add texto rosa
    res = res.replace(/&gt;&gt;([0-9]+)/g, '<a class="nro-post-ref" href="#$1" data-target-post="$1">&gt;&gt;$1</a>'); // add ref-posts
    res = res.replace(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/g, '<a href="https://href.li/?$1" ref="nofollow" target="_blank">$1</a>'); // add <a> nos links
    res = res.replace(/\n/g, '<br>'); // salta linhas
    return res;
}

var trataTexto = function()
{
    $('.post-conteudo, .post-conteudo-full').each(function(index){
        var conteudo = $(this).html();
        $(this).html(aplicaRegexStringPost(conteudo));
    });
};

var setaTema = function(tema){
    localStorage.setItem('tema', tema);

    $('.post-conteudo, .post-conteudo-full, i, b, h1,h2,h3,h4,h5,h6,label, small, strong, u, .free-text, .form-adm, .ibnav').css('color', `var(--${localStorage.getItem('tema')}-post-conteudo)`);
    if(tema === 'yotsuba' || tema === 'yotsubab'){
        if(!window.location.pathname.includes("/boards/%C3%A7")){
            $('body, .modal-content').css('background', `var(--${localStorage.getItem('tema')}-bgscale)`);
        }
        $('body, .modal-content').css('background-color', `var(--${localStorage.getItem('tema')}-bgcolor)`);
    } else {
        if(!window.location.pathname.includes("/boards/%C3%A7")){
            $('body, .modal-content').css('background', ``);
        }
        $('body, .modal-content').css('background-color', `var(--${localStorage.getItem('tema')}-bgcolor)`);
    }

    $('.assunto').css('color', `var(--${localStorage.getItem('tema')}-assunto)`);
    $('.fio-subpost').css('background-color', `var(--${localStorage.getItem('tema')}-fio-subpost)`);

    $('.fio-img-div').css('background-color', `var(--${localStorage.getItem('tema')}-fio-img-div-bgcolor)`);
    $('.fio-img-div').css('border', `var(--${localStorage.getItem('tema')}-fio-img-div-border)`);

    $('.a-responder').css('color', `var(--${localStorage.getItem('tema')}-a-responder)`);
    $('.a-responder-hover').css('color', `var(--${localStorage.getItem('tema')}-a-responder-hover)`);

    $('.green-text').css('color', `var(--${localStorage.getItem('tema')}-green-text)`);
    $('.pink-text').css('color', `var(--${localStorage.getItem('tema')}-pink-text)`);
    $('.orange-text').css('color', `var(--${localStorage.getItem('tema')}-orange-text)`);

    $('.modpost').css('color', `var(--${localStorage.getItem('tema')}-modpost)`);
    $('.ban-msg').css('color', `var(--${localStorage.getItem('tema')}-ban-msg)`);

    $('.div-indice').css('background-color', `var(--${localStorage.getItem('tema')}-div-indice-bgcolor)`);
    $('.div-indice').css('border', `var(--${localStorage.getItem('tema')}-div-indice-border)`);
    $('.catalogo-post-div').css('border', `var(--${localStorage.getItem('tema')}-catalogo-post-div-border)`);

    $('.anonpost-title').css('color', `var(--${localStorage.getItem('tema')}-anonpost-title)`);
    $('.mini-btn').css('color', `var(--${localStorage.getItem('tema')}-mini-btn)`);
    $('.mini-btn').css('background-color', `var(--${localStorage.getItem('tema')}-mini-btn-bgcolor)`);

    $('.mini-btn-revert').css('color', `var(--${localStorage.getItem('tema')}-mini-btn-bgcolor)`);
    $('.mini-btn-revert').css('background-color', `var(--${localStorage.getItem('tema')}-mini-btn)`);

    $('.form-post-file-input-box').css('background-color', `var(--${localStorage.getItem('tema')}-form-post-file-input-box-bgcolor)`);
    $('.form-post-file-input-box').css('border', `var(--${localStorage.getItem('tema')}-form-post-file-input-box-border)`);
    $('.sage-text').css('color', `var(--${localStorage.getItem('tema')}-sage-text)`);

    $('span.vermelhotexto').css('color', `var(--${localStorage.getItem('tema')}-span-vermelhotexto)`);

    $('.msg-strong').css('color', `var(--${localStorage.getItem('tema')}-msg-strong)`);
    $('.modal-close-btn').css('color', `var(--${localStorage.getItem('tema')}-modal-close-btn)`);

    $('.a-nro-post').css('color', `var(--${localStorage.getItem('tema')}-a-nro-post)`);
}

var criaPostFlutuante = function(event,remoteData=false){

    idPostCitado = $(event.target).data('target-post');

    targetPostElement = $(`#${idPostCitado}`);
    idPostCitador = $(event.relatedTarget).parent().parent().attr("id");

    var novaDiv = (remoteData ? $(remoteData) :  targetPostElement.clone());

    $(novaDiv).attr('id', `mini-subpost-${idPostCitado}`)
    $(novaDiv).addClass('fio-subpost-mini')
    novaDiv.appendTo(`#${idPostCitador}`);


    $(`#mini-subpost-${idPostCitado}`).css('position', 'fixed');
    $(`#mini-subpost-${idPostCitado}`).css('top', '10%');
    $(`#mini-subpost-${idPostCitado}`).css('right', '60%');
    $(`#mini-subpost-${idPostCitado}`).css('padding', '5px');
    setaTema(localStorage.getItem('tema'));
}

var deletaPostSto = function(postId){
    localStorage.removeItem(`data-post-${postId}`);
}

$(document).ready(function(){
    $('.btn-ban').on('click', function(){
        $('#idPostInput').val($(this).data('id-post'));
        
    });
    
    $('.btn-report').on('click', function(){
        $('#idPostReportInput').val($(this).data('id-post'));
        
    });
    
    $('.btn-mover-post').on('click', function(){
        $('.idPostMover').val($(this).data('id-post'));
        
    });
    
    $('.a-nro-post').on('click', function(){
        document.getElementById('novo-post-conteudo').value += ">>" + $(this).text() + "\n";
    });
    
    $('#select-board-catalogo').on('change', function(e){
        boardMostrar = $(this).find(":checked").val();
        
        $('.catalogo-post-div').css("display", "inline-table");
        if(boardMostrar !== 'todas')
        {
            $('.catalogo-post-div').not('.catalogo-post-div-board-' + boardMostrar).css("display", "none");   
        }
    });

    if( $('#id-board-ç').length ){
        criaÇandom()
    }
    
    trataTexto();
    if(localStorage.getItem('tema')){
        setaTema(localStorage.getItem('tema'));
    } else {
        setaTema('iblight');
    }

    window.addEventListener('paste', e => {
        var el = document.activeElement;
        if(el && (el.name === "assunto" || el.name === "linkyoutube" || el.name === "conteudo")){
            var isFirefox = typeof InstallTrigger !== 'undefined';
            const inputs = document.getElementsByName('arquivos[]');
            var inputToChange = inputs[inputs.length-1];

            if(e.clipboardData.files.length > 0){
                if(isFirefox)
                    inputToChange.files = structuredClone(e.clipboardData.files);
                else 
                    inputToChange.files = e.clipboardData.files;
                addNovoInputFile(inputToChange, 5);
            }
        }
    });

    $('.nro-post-ref').hover(function(event){
        idPostCitado = $(event.target).data('target-post');
        ehOp = $(`#${idPostCitado}`).hasClass("fio");
        targetPostElement = $(`#${idPostCitado}`);
        siglaBoard = $(event.relatedTarget).parent().parent().parent().data("sigla-board");

        if(targetPostElement.length > 0 && !ehOp){
            criaPostFlutuante(event);
        } 
        else if(localStorage.getItem(`data-post-${idPostCitado}`)){
            criaPostFlutuante(event,localStorage.getItem(`data-post-${idPostCitado}`));
        }
        else {
            $.get( `/post/${siglaBoard}/${idPostCitado}`, function( data ) {
                if(data){
                    conteudoInterno = $($(data).find('.post-conteudo')[0]).html();
                    conteudoTratado = aplicaRegexStringPost(conteudoInterno);
                    data = $(data);
                    data.find('.post-conteudo').eq(0).html(conteudoTratado);
                    data = data.prop('outerHTML');

                    localStorage.setItem(`data-post-${idPostCitado}`, data);
                    criaPostFlutuante(event,localStorage.getItem(`data-post-${idPostCitado}`));
                }
            });
        }
    }, function(event){
        idDelete = `mini-subpost-${$(event.target).data('target-post')}`
        $(`#${idDelete}`).remove();

    });
});
