<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use RajaOngkir;
use App\Models\Cost;
use Excel;
use App\Exports\FromCollectionWithViewExport;
use App\Exports\FromCollectionWithViewMultipleSheetsExport;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::get();
        return view('cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        $time = 60 * 5;
        ini_set('max_execution_time', $time);

        $courier = request('courier') ? request('courier') : 'jne';

        $page = request('page') ? request('page') : 1;
        $limit = 100;
        $skip = ($page - 1) * $limit;

        $origin = $city;
        $cities = City::take($limit)->skip($skip)->get();

        if (count($cities)) {
            foreach ($cities->chunk(10) as $chunk) {
                foreach ($chunk as $item) {
                    $destination = $item;
                    $exist = Cost::isCityCostExist($origin->id, $destination->id, $courier);

                    if (!$exist) {
                        $cost = Cost::calculateCityCost($origin->id, $destination->id, $courier);
                        dump([
                            'from' => $origin->id,
                            'to' => $destination->id
                        ]);
                        Cost::saveCityCost($origin->id, $destination->id, $courier, $cost);
                    }
                }
            }

            return redirect()->route('cities.show', [$city->id, 'page' => $page+1]);
        } else {
            $newCity = $city->id + 1;
            dump('No destination');
            return redirect()->route('cities.show', [$newCity]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }

    public function export(City $city)
    {
        $excelData = [];
        $filename = str_slug($city->type . ' ' . $city->name);

        // get data
        $city->load('costs');
        $costs = $city->costs()->with('originCity', 'destinationCity')->get();
        $services = $costs->unique('service')->pluck('service');

        $groupedCosts = [];
        foreach ($costs->chunk(50) as $chunk) {
            foreach ($chunk as $item) {
                $id = $item['origin'] . '-' . $item['destination'];
                $data = [
                    'origin' => $item['originCity'],
                    'destination' => $item['destinationCity'],
                ];
                $service = $item['service'];
                $groupedCosts[$id]['data'] = $data;
                $groupedCosts[$id]['costs'][$service] = [
                    'cost' => $item['cost'],
                    'estimation' => $item['estimation'],
                ];
            }
        }

        $costs = collect($groupedCosts);

        // display data
        $view = 'cities.export';
        $data = compact('city', 'costs', 'services');
        // return view($view, $data);

        // add to excel data
        $excelData[$filename] = [
            'sheetname' => $filename,
            'view' => $view,
            'data' => $data,
        ];

        $date = date('Ymd');
        $randomString = str_random(8).'-'.time();
        $filename = 'export-cost-'.$filename.'-'.$date.'-'.$randomString.'.xlsx';
        return Excel::download(new FromCollectionWithViewMultipleSheetsExport($excelData), $filename);
    }
}
