@extends('main')

@section('stylesheets')
    <link rel="stylesheet" href="/css/style.css" >
@stop



@section('scripts')

<script>
function expandirImg(element){
    event.preventDefault();
    imgElement = $(element).children('.img-responsive')[0];
    isSpoilerImg = $(imgElement).data("spoiler") === 1;
    if(imgElement.hasAttribute("width") && imgElement.hasAttribute("height")){
        if(isSpoilerImg){
            spoilerSrc = $(imgElement).data("spoiler-src");
            $(imgElement).attr("src", spoilerSrc);
        } else {
            $(imgElement).attr("src", $(imgElement).attr("src").replace(".thumb.jpg", ""));
        }
        imgElement.removeAttribute("width");
        imgElement.removeAttribute("height");
    } else {
        if(isSpoilerImg){
            $(imgElement).attr("src", "{{ \Storage::url('res/spoiler.png') }}");
        } else {
            $(imgElement).attr("src", $(imgElement).attr("src") + ".thumb.jpg");
        }
        $(imgElement).attr("width","200px");
        $(imgElement).attr("height","200px");
    }
}


function expandirVid(element, divId){
    imgElement = $(element);
    mimeType = imgElement.data("type");
    isSpoiler = $(imgElement).data("spoiler");
    spoilerSrc = $(imgElement).data("spoiler-src");

    newElement = "<video controls>";

    newElement += "<source ";
    if(isSpoiler)
        newElement += ` src="${spoilerSrc}"`;
    else 
        newElement += ` src="${imgElement.attr("src").replace(".thumb.png", "")}"`;

    newElement += ` type="${mimeType}"`;
    newElement += " />";

    newElement += "</video>";
    imgElement.hide();
    $(newElement).appendTo(`#${divId}`);
}

function armazenaElemEscondido(tipo, idDiv){
    localStorage.setItem(tipo + idDiv, idDiv);
}

function removeElemEscondido(tipo, idDiv){
    localStorage.setItem(tipo + idDiv, 0);
}


function mostraEscondeDivArquivo(idDiv){
    divElem = $('#' + idDiv);
    ctrlBtn = $('#' + idDiv + '-ctrl');
    if(ctrlBtn.hasClass("glyphicon-minus")){
        ctrlBtn.removeClass("glyphicon-minus");
        divElem.hide();
        ctrlBtn.addClass("glyphicon-plus");
        armazenaElemEscondido('arq', idDiv);

        vidElem = divElem.children('video').get(0);
        if(vidElem){
            vidElem.pause();
        }

    } else {
        ctrlBtn.removeClass("glyphicon-plus");
        divElem.fadeIn();
        ctrlBtn.addClass("glyphicon-minus");
        removeElemEscondido('arq', idDiv);
    }

}

function atualizaEscondidos(){
    for (var i = 0; i < localStorage.length; i++){
        chave = localStorage.key(i);
        valor = localStorage.getItem(chave);
        tipoChave = chave.substring(0,3);
        if(tipoChave === 'arq'){
            if(valor != 0)  mostraEscondeDivArquivo(valor);
        }
        else if (tipoChave === 'fio'){
            if(valor != 0)  hidePost(valor);
            else showPost(valor);

        }
    }
}

function hidePost(postId){
    postElem = $('#' + postId);
    hidPostElem = $('#' + 'hid' + postId);
    postElem.hide();
    hidPostElem.fadeIn();
    armazenaElemEscondido('fio', postId);
}

function showPost(postId){
    postElem = $('#' + postId);
    hidPostElem = $('#' + 'hid' + postId);
    postElem.fadeIn();
    hidPostElem.hide();
    removeElemEscondido('fio', postId);
}

function expandirContrairConteudoPost(id){
    pcf = $('#' + 'pcf' + id);
    pc = $('#' + 'pc' + id);
    if(pcf.is(':hidden')){
        pcf.fadeIn();
        pc.hide();
        $(event.srcElement).html('[Contrair Conteudo]');
    } else {
        pcf.hide();
        pc.fadeIn();
        $(event.srcElement).html('[Expandir Conteudo]');

    }
}

$(document).ready(function() { 
    atualizaEscondidos();

    $('#form-novo-post').on('submit', function(event){
        event.preventDefault();
        valido = false;
        arquivos = $('.form-post-file-input');
        for(var i=0; i < arquivos.length; i++){
            if(arquivos[i].files.length > 0){
                valido = true;
            }   
        }
        ytAnexos = $('#linkyoutube').val();
        if(ytAnexos.trim() !== ''){
            valido = true;
        }

        if(valido){
            this.submit();
        }
        else{
            alert("É necessário postar pelo menos com um arquivo ou um link do youtube");
        }
    })

});

</script>

@endsection