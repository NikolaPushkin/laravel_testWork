<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Тестовое задание</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    @vite(['resources/css/app.css'])

</head>
<body>
<div class="container mt-8">
    <form action="{{route('main')}}" method="get">
        @csrf
        <div class="row mb-2">
            <div class="col-6 ">
                <div class="form-group">
                    <label for="id">Идентификатор</label>
                    <input class="form-control" name="id" type="number"
                           value="{{isset($filter['id'])?$filter['id']:''}}">
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-auto">
                <div class="form-group">
                    <label for="begda">Дата с</label>
                    <input class="form-control " name="begda" type="date"
                           value="{{isset($filter['begda'])?$filter['begda']:''}}" required>
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group">
                    <label for="endda">Дата по</label>
                    <input class="form-control" type="date" name="endda"
                           value="{{isset($filter['endda'])?$filter['endda']:''}}" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <input class="btn btn-primary mt-2" type="submit">
                </div>
            </div>
        </div>
    </form>

    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <ul id="objectList">
                    @if (isset($mainParent->objid))
                        <li id="object-{{$mainParent->objid}}" class="object parent" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{$mainParent->objid}}"
                            aria-controls="collapse-{{$mainParent->objid}}"><span
                                style="color: green">#{{$mainParent->objid}}&nbsp;</span>{{ $mainParent->stext }}</li>
                        @isset($objects)
                            @include('view_objects',['objects'=>$objects,'id'=>$mainParent->objid])
                        @endisset
                    @else
                        <p>Ничего не найдено</p>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /*    let token = document.querySelector('meta[name="csrftoken"]');*/
    let begda = "{{$filter['begda']}}";
    let endda = "{{$filter['endda']}}";

    objectList.onclick = function (event) {
        let object = event.target
        let obj_id = event.target.getAttribute('data-object-id')

        if (obj_id > 0 && object.classList.contains('parent') && !object.classList.contains('appended')) {
            object.classList.add('appended')
            object.classList.toggle('collapsed')
            getChildObjectsById(obj_id, begda, endda)
        }
    }

    function getChildObjectsById(id, begda, endda) {

        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/x-www-form-urlencoded");
        myHeaders.append("X-CSRF-TOKEN", "{{csrf_token()}}");

        var urlencoded = new URLSearchParams();
        urlencoded.append("id", id);
        urlencoded.append("begda", begda);
        urlencoded.append("endda", endda);

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: urlencoded,
            redirect: 'follow'
        };
        document.querySelector('#object-' + id).style.opacity = 0.5
        return fetch("/getobjects", requestOptions)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        document.querySelector('#object-' + id).style.opacity = 1
                        document.querySelector('#object-' + id).innerHTML += data
                    });
                }
            })
            .catch(error => console.log('error', error));
    }
</script>
</body>
</html>
