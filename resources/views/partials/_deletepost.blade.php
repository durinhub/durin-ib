@if($viewOnly)
<a href="" data-toggle="tooltip" data-placement="top" title="Deletar post" type="submit" class="mini-btn"><span class="glyphicon glyphicon-trash"></span></a> 
@else 
<form action="{{ route('posts.destroy') }}" method="post" id="deletepost{{$postIdDel}}" class="mini-btn-form">
    {{ csrf_field() }}
    <input type="hidden" name="siglaBoard" value="{{ $siglaBoard }}">
    <input type="hidden" name="postId" value="{{ $postIdDel }}">
    <a href="javascript:if(deletaPostSto({{$postIdDel}})){$('#deletepost{{$postIdDel}}').submit();}" data-toggle="tooltip" data-placement="top" title="Deletar post" type="submit" class="mini-btn"><span class="glyphicon glyphicon-trash"></span></a> 
</form>
@endif