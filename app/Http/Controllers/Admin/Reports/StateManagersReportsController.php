<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateManagersReportsController extends Controller
{
    public function reports(){
        $rejected_forms = DB::table('beneficiary_forms')
            ->join('state_managers', 'state_managers.id', '=', 'beneficiary_forms.state_manager_id')
            ->where('beneficiary_forms.deleted_at', '!=', null)
            ->select('state_managers.name', 'beneficiary_forms.state_manager_notes', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('state_managers.name', 'beneficiary_forms.state_manager_notes')
            ->orderBy('count', 'desc')
            ->get();

        $accepted_forms = DB::table('beneficiary_forms')
            ->join('state_managers', 'state_managers.id', '=', 'beneficiary_forms.state_manager_id')
            ->where('beneficiary_forms.deleted_at', '=', null)
            ->select('state_managers.name', 'beneficiary_forms.state_manager_notes', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('state_managers.name', 'beneficiary_forms.state_manager_notes')
            ->orderBy('count', 'desc')
            ->get();

        $events_created = DB::table('events')
            ->join('state_managers', 'state_managers.id', '=', 'events.state_manager_id')
            ->select('state_managers.name', DB::raw("count(events.id) as count"))
            ->groupBy('state_managers.name')
            ->orderBy('count', 'desc')
            ->get();
        return view('dashboard.reports.state_manager_reports', [
            'events_created' => $events_created,
            'rejected_forms' => $rejected_forms,
            'accepted_forms' => $accepted_forms,
        ]);
    }
}
