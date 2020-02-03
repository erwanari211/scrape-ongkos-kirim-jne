<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RajaOngkir;
use App\Models\City;

class Cost extends Model
{
    protected $fillable = [
        'origin', 'origin_type',
        'destination', 'destination_type',
        'weight',
        'courier', 'service',
        'cost', 'estimation',
    ];

    public function originCity()
    {
        return $this->belongsTo('App\Models\City', 'origin');
    }

    public function destinationCity()
    {
        return $this->belongsTo('App\Models\City', 'destination');
    }

    public static function isCityCostExist($origin, $destination, $courier)
    {
        return static::where([
            'origin'           => $origin,
            'destination'      => $destination,
            'courier'          => $courier,
            'origin_type'      => 'city',
            'destination_type' => 'city',
        ])->exists();
    }

    public static function calculateCityCost($origin, $destination, $courier)
    {
        $cost = RajaOngkir::Cost([
            'origin'          => $origin,
            'destination'     => $destination,
            'courier'         => $courier,
            'originType'      => 'city',
            'destinationType' => 'city',
            'weight'          => 1000,
        ])->get();

        return $cost;
    }

    public static function saveCityCost($origin, $destination, $courier, $cost)
    {
        if (isset($cost[0]['costs'])) {
            foreach ($cost[0]['costs'] as $serviceItem) {
                $data = Cost::firstOrCreate([
                    'origin' => $origin,
                    'origin_type' => 'city',
                    'destination' => $destination,
                    'destination_type' => 'city',
                    'weight' => 1000,
                    'courier' => $courier,
                    'service' => $serviceItem['service'],
                    'cost' => $serviceItem['cost'][0]['value'],
                    'estimation' => $serviceItem['cost'][0]['etd'],
                ]);
                dump('cost saved');
            }
        }
    }
}
