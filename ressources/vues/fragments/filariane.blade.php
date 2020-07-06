@foreach($filAriane as $lien)
    @if(isset($lien["lien"]))
        <a href="{{$lien["lien"]}}" class="filAriane__lien">
            {{$lien["titre"]}}
        </a>
    @else
        {{$lien["titre"]}}
    @endif
    @if ($loop->last)
        <span> &nbsp; </span>
    @else
        <span class="filAriane__separation"> > </span>
    @endif
@endforeach