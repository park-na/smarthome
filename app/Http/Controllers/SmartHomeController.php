<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Smarthome;

class SmarthomeController extends Controller
{
    public function index() {
        $data = Smarthome::all();
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

}