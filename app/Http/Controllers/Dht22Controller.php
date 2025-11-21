<?php

namespace App\Http\Controllers;

use App\Models\Dht22;
use App\Models\SensorData;
use Illuminate\Http\Request;

class Dht22Controller extends Controller
{
    public function __construct()
    {
        $dht = Dht22::count();
        if ($dht == 0) {
            Dht22::create([
                'temperature' => 0,
                'humidity' => 0
            ]);
        }
    }


    // public function updateData($tmp, $hmd){
    //     $dht = Dht22::first();
    //     $dht->temperature = $tmp;
    //     $dht->humidity = $hmd;
    //     $dht->save();

    //     return response()->json(['message' => 'Data updated succesfully']);
    // }

    public function updateData($tmp, $hmd)
    {
        $sensor = new Dht22();
        $sensor->temperature = $tmp;
        $sensor->humidity = $hmd;
        $sensor->save();

        return response()->json(['message' => 'Data updated successfully']);
    }

    public function getData()
    {
        $dht = Dht22::first();
        return response()->json($dht);
    }

    // public function getLatest()
    // {
    //     $data = SensorData::latest()->first();

    //     return response()->json([
    //         'temperature' => $data->temperature,
    //         'humidity' => $data->humidity,
    //     ]);
    // }

    public function getLatest()
{
    $data = Dht22::latest()->first();

    if (!$data) {
        return response()->json(['error' => 'No data found'], 404);
    }

    $buzzer = $data->temperature > 32 ? 1 : 0;

    return response()->json([
        'temperature' => $data->temperature,
        'humidity' => $data->humidity,
        'buzzer' => $buzzer
    ]);
}


}
