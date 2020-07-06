<?php

namespace App\Http\Controllers\Api;

use App\Checkuptransaction;
use App\Http\Controllers\Controller;
use App\Patientcheckup;
use App\Transaction;
use Illuminate\Http\Request;

class CheckuptransactionController extends Controller
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
           'transaction_id' => 'required| numeric',
            'patientcheckup_id' => 'required| numeric',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        $patientcheckup = Patientcheckup::findOrFail($request->patientcheckup_id);

        $newCheckupTransaction = new Checkuptransaction();
        $newCheckupTransaction->transaction_id = $transaction->id;
        $newCheckupTransaction->patientcheckup_id = $patientcheckup->id;
        $newCheckupTransaction->save();

        return response()->json($newCheckupTransaction, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Checkuptransaction  $checkuptransaction
     * @return \Illuminate\Http\Response
     */
    public function show(Checkuptransaction $checkuptransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checkuptransaction  $checkuptransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkuptransaction $checkuptransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checkuptransaction  $checkuptransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkuptransaction $checkuptransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checkuptransaction  $checkuptransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkuptransaction $checkuptransaction)
    {
        //
    }
}
