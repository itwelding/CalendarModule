<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Visit;
use App\Models\Service;
use App\Models\Client;
use App\Models\Car;


class CalendarController extends Controller
{
    public function index()
    {          
        $visits = Visit::with('services')->get();
        $visitsWithCar = [];
        for($i=0; $i<$visits->count() ; $i++) {
            $visitsWithCar[$i] = array("visit"=>$visits[$i],"car"=>$visits[$i]->car);
        }
        return Inertia::render('Calendar/Index', [
            'visits' => $visitsWithCar,
            'services' => Service::all('id', 'name', 'term'),
            'clients' => Client::pluck('id', 'name'),
            'cars' => Car::all('id', 'brand', 'model', 'client_id')
        ]);     
    }

    // adding new visit from calendar
    public function store() {
        $request = Request::collect();
        $visit = new Visit();
        $visit->client_id = $request['client'];
        $visit->car_id = $request['car'];
        $visit->company_id = Auth::user()->company_id;
        $visit->user_id = Auth::user()->id;
        $visit->time_start = $request['time']['start'];
        $visit->time_end = $request['time']['end'];
        $visit->date = $request['date'];
        $visit->note = $request['note'];        
        $visit->save();
        $visit->services()->attach($request['services']);
        $visit->save();
        return Redirect::back()->with('success', 'Pomyślnie dodano wizytę.');
    }
}
