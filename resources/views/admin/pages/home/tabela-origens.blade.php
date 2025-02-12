@foreach($dados as $d)
    <p style="margin:0;padding:0;display:flex;flex-basis:100%;justify-content:space-between;" id="tabela_origens_{{$d->id}}">
        <span style="color:#FFF;margin:0;padding:0;font-size:0.72em;">{{$d->nome}}</span>
        <span style="font-size:0.8em;color:#FFF;"><i class="fas fa-times fa-xs deletar_tabela_origens" data-id="{{$d->id}}"></i></span>
    </p>
@endforeach
