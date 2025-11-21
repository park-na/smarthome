<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmartHome;

class SmartHomeController extends Controller
{
    public function index() {
        $data = SmartHome::all();
        return view('smart-home', compact('data'));
    }

    public function updateStatus(Request $request, $id)
    {
        $device = SmartHome::findOrFail($id);
        $device->status = $request->status;   // 1 = ON, 0 = OFF
        $device->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status'  => $device->status
        ]);
    }

    public function update(Request $req)
    {
        SmartHome::where('id', $req->id)->update([
            'status' => $req->status
        ]);

        return response()->json(['success' => true]);
    }

    public function getLampu() {

        $smartHomes = SmartHome::all();

        if($smartHomes->isEmpty()) {
            return response()->json([
                'nama' => $smartHomes->name,
                'status' => $smartHomes->status,
                'message' => 'No Smart Home data found',
            ]);
        } 
        return response()->json($smartHomes);
    }

}