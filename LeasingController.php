<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Car;
use App\Models\Worker;
use App\Models\Contract;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class LeasingController extends Controller
{
    public function index()
    {
        return Inertia::render('Leasing/Index', [
            'filters' => Request::only(['search']),
            'clients' => Client::query()
                ->when(Request::input('search'), function($query, $search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->paginate(100)
                ->withQueryString()
                ->through(fn ($client) => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'nip' => $client->nip,
                    'phone_number' => $client->phone_number,
                    'email' => $client->email,
                    'acquisition_date' => $client->acquisition_date,
                    'acquisition_type' => $client->acquisition_type,
                    'offer_date' => $client->offer_date,
                    'offer_type' => $client->offer_type,
                    'application_date' => $client->application_date,
                    'application_type' => $client->application_type,                                        
                    'note' => $client->note,                                        
                    'cars' => $client->cars,
                    'contracts' => $client->contracts,
                    'workers' => $client->workers,
                ]),
        ]);
    }

    public function store()
    {
        $client = new Client();        
        $client->company_id = Auth::user()->company_id;        
        $client->save();        
        return Redirect::back();   
    }
    
    public function update($client_id)
    {
        $client = Client::find($client_id);
        $request = Request::collect();
        $client->name = $request['name'];
        $client->company_id = Auth::user()->company_id;
        $client->nip = $request['nip'];
        $client->phone_number = $request['phone'];
        $client->email = $request['email'];
        $client->acquisition_date = $request['acquisitionDate'];
        $client->acquisition_type = $request['acquisitionType'];
        $client->offer_date = $request['offerDate'];
        $client->offer_type = $request['offerType'];        
        $client->application_date = $request['applicationDate'];
        $client->application_type = $request['applicationType'];
        $client->note = $request['note'];
        $client->save();            
        
        $workerName= $request['workerName'];
        $workerSurname= $request['workerSurname'];
        // check if client has workers    
        if (isset($client->workers[0])) 
        {
            // if client has workers, check if worker name and surname are the same
            if ($client->workers[0]->name == $workerName && $client->workers[0]->surname == $workerSurname) 
            {
                // if worker name and surname are the same, do nothing
            } 
            else 
            {
                // if worker name and surname are different, update first worker
                $client->workers[0]->name = $workerName;
                $client->workers[0]->surname = $workerSurname;
                // save changes
                $client->workers[0]->save();
            }
        } 
        else 
        {
            // if client has no workers, create new worker
            $worker = new Worker();
            $worker->name = $workerName;
            $worker->surname = $workerSurname;
            $worker->client_id = $client->id;
            $worker->save();
        }        
        return Redirect::back();
    }

    public function destroy(Client $client)
    {
        $client->delete();      
        return Redirect::back();  
    }

    public function newWorker(Client $client)
    {
        $worker = new Worker();        
        $worker->client_id = $client->id;
        $worker->save();
        return Redirect::back();
    }    

    public function newContract(Client $client)
    {
        $contract = new Contract();        
        $contract->client_id = $client->id;
        $contract->save();
        return Redirect::back();
    }

    public function newCar(Client $client)
    {
        $car = new Car();        
        $car->client_id = $client->id;
        $car->company_id = Auth::user()->company_id;
        $car->save();
        return Redirect::back();
    }
    
    public function updateWorker($client, $worker)    
    {
        $worker = Worker::find($worker);
        $request = Request::collect();
        $worker->name = $request['name'];
        $worker->surname = $request['surname'];
        $worker->phone_number = $request['phone'];
        $worker->email = $request['email'];
        $worker->pesel = $request['pesel'];
        $worker->property_community = $request['propertyCommunity'];
        $worker->save();        
        return Redirect::back();  
    }

    public function updateContract($client, $contract)    
    {
        $contract = Contract::find($contract);
        $request = Request::collect();
        $contract->number = $request['number'];
        $contract->date = $request['date'];
        $contract->item = $request['item'];
        $contract->volume = $request['volume'];
        $contract->leasing = $request['leasing'];
        $contract->insurance = $request['insurance'];
        $contract->gap = $request['gap'];
        $contract->credit = $request['credit'];    
        $contract->save();        
        return Redirect::back();  
    }
    
    public function updateCar($client, $car)    
    {
        $car = Car::find($car);
        $request = Request::collect();
        $name = $request['name'];        
        // split name 
        $name = explode(" ", $name);

        $car->company_id = Auth::user()->company_id;
        $car->brand = $name[0];
        $car->model = $name[1];
        $car->plates = $request['plates'];    
        $car->interior = $request['interior'];    
        $car->save();        
        
        return Redirect::back();          
    }    
}
