<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    public function index(Request $request)
    {
        $filter['id'] = $request->query('id') > 0 ? $request->query('id') : 1;
        $filter['begda'] = $request->query('begda') ? $request->query('begda') : '';
        $filter['endda'] = $request->query('endda') ? $request->query('endda') : '';
        $objects = [];
        $mainParent = '';
        if (count($request->query())) {

            $mainParent = DB::table('objects')
                ->where('objid', '=', $filter['id'])
                ->where('begda', '<=', $filter['endda'])
                ->where('endda', '>=', $filter['endda'])
                ->first();
            $objects = $this->getObjectsByFilter($filter);
        }
        return view('main', compact(['objects', 'filter', 'mainParent']));
    }

    public function getChildObjectsByParentId(Request $request)
    {
        $filter = [];
        $filter['id'] = $request->post('id');
        $filter['begda'] = $request->post('begda');
        $filter['endda'] = $request->post('endda');
        $objects = $this->getObjectsByFilter($filter);
        $id = $filter['id'];
        return view('view_objects', compact(['objects', 'filter', 'id']))->render();
    }

    private function getObjectsByFilter($filter)
    {
        return DB::table('objects')
            ->where('parent_objid', '=', $filter['id'])
            ->join('relations', 'objects.objid', '=', 'relations.objid')
            ->where('objects.begda', '<=', $filter['endda'])
            ->where('objects.endda', '>=', $filter['endda'])
            ->where('relations.begda', '<=', $filter['endda'])
            ->where('relations.endda', '>=', $filter['endda'])
            ->orderBy('npp')
            ->get();
    }
}
