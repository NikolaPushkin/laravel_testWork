<ul id="collapse-{{$id}}" class="collapse show">
    @if (count($objects))
    @foreach ($objects as $object)
        <li id="object-{{$object->objid}}" class="object collapsed" data-object-id="{{$object->objid}}"
            data-bs-toggle="collapse" data-bs-target="#collapse-{{$object->objid}}"
            aria-controls="collapse-{{$object->objid}}">{{ $object->stext }}</li>
    @endforeach
    @else
    <li>Нет объектов</li>
    @endif
</ul>
