<?php

namespace App\Http\Controllers\Api;


use App\Agentpayments;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgentpaymentController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }


    public function getAgentPayments(User $agent)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user') &&
            !($this->user->hasRole('patient') && $this->user->id == $agent->id)
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $agentPayments = Agentpayments::where('agent_id', $agent->id)
            ->orderBy('date', 'DESC')
            ->paginate(10);
        return response()->json($agentPayments);
    }

    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'agent_id' => 'required| numeric',
            'amount' => 'required| numeric',
            'date' => 'required',
            'additional_info' => 'sometimes'
        ]);

        $agent = User::findOrFail($request->agent_id);

        $newAgentPayment = new Agentpayments();
        $newAgentPayment->agent_id = $agent->id;
        $newAgentPayment->amount = $request->amount;
        $newAgentPayment->date = Carbon::parse($request->date);
        if ($request->has('additional_info')) {
            $newAgentPayment->additional_info = $request->additional_info;
        }
        $newAgentPayment->save();

        $agent->pending_amount = $agent->pending_amount - $request->amount;
        $agent->save();

        return response()->json($newAgentPayment, 201);
    }
}
