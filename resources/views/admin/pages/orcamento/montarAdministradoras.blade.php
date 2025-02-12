@foreach($admins as $adm)
    <div class="d-flex administradoras_container justify-content-center" id="{{$adm->id}}">
        
        <img src="{{asset($adm->logo)}}" alt="{{$adm->nome}}" width="80%;" height="80%;"></p>
    </div>
@endforeach
