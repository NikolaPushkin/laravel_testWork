<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ObjectRGD;

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
            $mainParent = ObjectRGD::where('objid', '=', $filter['id'])
                ->ObjectExists($filter['begda'], $filter['endda'])
                ->with(
                    [
                        'relations' => function ($query) use ($filter) {
                            $query->where('relations.endda', '>=', $filter['begda'])
                                ->RelationExists($filter['begda'], $filter['endda']);
                        }
                    ]
                )
                ->first();
            $objects = $this->getChildObjectsByFilter($filter);
        }
        return view('main', compact(['objects', 'filter', 'mainParent']));
    }

    public function getChildObjectsByParentId(Request $request)
    {
        $filter = [];
        $filter['id'] = $request->post('id');
        $filter['begda'] = $request->post('begda');
        $filter['endda'] = $request->post('endda');
        $objects = $this->getChildObjectsByFilter($filter);
        $id = $filter['id'];
        return view('view_objects', compact(['objects', 'filter', 'id']))->render();
    }

    private function getChildObjectsByFilter($filter)
    {
        $result = Relation::select(
            'relations.objid',
            'relations.parent_objid',
            'relations.begda as rel_begda',
            'relations.endda as rel_endda',
            'objects.begda as obj_begda',
            'objects.endda as obj_endda',
            'relations.npp as npp'
        )
            ->where('parent_objid', '=', $filter['id'])
            ->RelationExists($filter['begda'], $filter['endda'])
            ->where('objects.endda', '>=', $filter['begda'])
            ->where('objects.begda', '<=', $filter['endda'])
            ->join('objects', 'relations.objid', '=', 'objects.objid')
            ->addSelect(
                [
                    'name' => ObjectRGD::select('stext')
                        ->whereColumn('relations.objid', '=', 'objects.objid')
                        ->ObjectExists($filter['begda'], $filter['endda'])
                        ->limit(1)
                ]
            )
            ->orderBy('relations.npp', 'asc')
            ->get();

        return $result;
    }
}
