<?php

namespace App\Http\Controllers;

use App\Models\GoogleMarker;
use Illuminate\Http\Request;

class GoogleMarkerController extends Controller
{
    public function index()
    {
        $markers = GoogleMarker::get();

        return response()->json(compact('markers'));
    }

    public function store(Request $request)
    {
        $marker = GoogleMarker::create($request->all());

        return response()->json(compact('marker'));
    }

    public function update(Request $request, $id)
    {
        $marker = GoogleMarker::find($id);
        $marker->update($request->all());

        return response()->json(compact('marker'));
    }

    public function destroy ($id)
    {
        GoogleMarker::destroy($id);

        return response()->json(['message' => 'Marker deleted']);
    }
}
