<ul id="collapse-{{$id}}" class="collapse show">
        @foreach ($objects as $object)
            <li id="object-{{$object->objid}}" class="object {{count($object->relations)?'parent collapsed':''}}" data-object-id="{{$object->objid}}"
                data-bs-toggle="collapse" data-bs-target="#collapse-{{$object->objid}}"
                aria-controls="collapse-{{$object->objid}}"
                title="Период&nbsp;существования&nbsp;объекта:&#10;{{$object->obj_begda}}&nbsp;-&nbsp;{{$object->obj_endda}}&#10;Период&nbsp;принадлежности&nbsp;объекта:&#10;{{$object->rel_begda}}&nbsp;-&nbsp;{{$object->rel_endda}}"><span
                    style="color: green">#{{$object->objid}}&nbsp;</span>{{ $object->name }}</li>
        @endforeach
</ul>
