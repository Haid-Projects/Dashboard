<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Models\StateManager;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function state_managers(Request $request){
        if($request->query('name')){
            $state_managers = StateManager::where('name', 'LIKE', "%".$request->query('name')."%")->paginate(2);
        }else{
            $state_managers = StateManager::paginate(2);
        }
        return view('dashboard.employees.state_manager_list', ['state_managers' => $state_managers]);
    }

    public function specialists(Request $request){
        if($request->query('name')){
           $specialists = Specialist::where('name', 'LIKE', "%".$request->query('name')."%")->paginate(2);
        }else{
            $specialists = Specialist::paginate(2);
        }
        return view('dashboard.employees.specialist_list', ['specialists' => $specialists]);
    }



    public function createStateManager(Request $request){
        \App\Models\StateManager::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);
        $state_managers = \App\Models\StateManager::all();
        return redirect()->route('state_managers');

       // return view('dashboard.employees.state_manager_list', ['state_managers' => $state_managers]);
    }


    public function editStateManager(Request $request, $state_manager_id){
        $state_manager = StateManager::find($state_manager_id);
        $state_manager->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);
        return redirect()->route('state_managers');
    }


    public function editSpecialist(Request $request, $specialist_id){
        $specialist = Specialist::find($specialist_id);
        $specialist->update([
            'name' => $request->name,
            'username' => $request->username,
            'phone_number' => $request->phone,
        ]);
        return redirect()->route('specialists');
    }

    public function createSpecialist(Request $request){
        \App\Models\Specialist::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone_number' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);
        $specialists = \App\Models\Specialist::all();
        return redirect()->route('specialists');

        //return view('dashboard.employees.specialist_list', ['specialists' => $specialists]);
    }

    public function deleteStateManager($state_manager_id){
        $state_manager = StateManager::find($state_manager_id);
        if($state_manager){
            $state_manager->delete();
        }
        $state_managers = \App\Models\StateManager::all();
        return redirect()->route('state_managers');

       // return view('dashboard.employees.state_manager_list', ['state_managers' => $state_managers]);
    }
    public function deleteSpecialist($specialist_id){
        $specialist = Specialist::find($specialist_id);
        if($specialist){
            $specialist->delete();
        }
        $specialists = \App\Models\Specialist::all();
        return redirect()->route('specialists');
       // return view('dashboard.employees.specialist_list', ['specialists' => $specialists]);
    }



}
