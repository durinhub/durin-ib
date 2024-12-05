@if($anao->countrycode && $anao->countrycode !== "br")
    <img src="/storage/res/flags/{{ $anao->countrycode }}.png" alt="{{ $anao->countrycode }}">
@elseif($anao->countrycode && $anao->countrycode === "br" && $anao->regioncode)
    <img src="/storage/res/flags/{{ $anao->countrycode . $anao->regioncode}}.png" alt="{{ $anao->countrycode . $anao->regioncode }}">
@endif 