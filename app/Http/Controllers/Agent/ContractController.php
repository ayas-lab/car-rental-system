<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['reservation.vehicle', 'client', 'agent']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $contracts = $query->latest()->paginate(15)->withQueryString();

        return view('agent.contracts.index', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['reservation.vehicle', 'reservation.invoice', 'client', 'agent']);

        return view('agent.contracts.show', compact('contract'));
    }
}