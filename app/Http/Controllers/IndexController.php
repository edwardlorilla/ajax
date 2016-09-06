<?php

namespace App\Http\Controllers;

use App\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;

class IndexController extends Controller
{
    public function addItem(Request $request)
    {
        $rules = array(
            'name' => 'required|alpha_num'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
            return Response::json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ));
        else {
            $data = new Data ();
            $data->name = $request->name;
            $data->save();
            return response()->json($data);
        }
    }
    public function readItems(Request $req) {
        $data = Data::all ();
        return view ( 'welcome' )->withData ( $data );
    }
    public function editItem(Request $req) {
        $data = Data::find ( $req->id );
        $data->name = $req->name;
        $data->save ();
        return response ()->json ( $data );
    }
    public function deleteItem(Request $req) {
        Data::find ( $req->id )->delete ();
        return response ()->json ();
    }
}
