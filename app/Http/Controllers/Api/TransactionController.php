<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function loadAllUserTransactions(User $user){
        $userCompletedTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 1)->paginate(10);
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


    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required| numeric',
            'type' => 'required| numeric',
            'amount' => 'required| numeric',
            'status' => 'sometimes| numeric',
            'agent_commission' => 'sometimes| numeric',
        ]);

        $user = User::findOrFail($request->user_id);

        $newTransaction = new Transaction();
        $newTransaction->user_id = $user->id;
        $newTransaction->amount = $request->amount;
        $newTransaction->type = ($request->user)? 0: $request->type;
        if($request->has('status')) {
            $newTransaction->status = $request->status;
        }
        do
        {
            $code = Str::random(16);
            $transaction = Transaction::where('code', $code)->first();
        }
        while($transaction);
        $newTransaction->code = $code;
//        if($request->has('agent_commission')) {
//            $newTransaction->agent_commission = $request->agent_commission;
//        }
        $newTransaction->save();
        return response()->json($newTransaction, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
