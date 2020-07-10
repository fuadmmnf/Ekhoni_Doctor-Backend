<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group  Transaction management
 *
 * APIs related to Transactions resource
 *
 */
class TransactionController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware('auth:sanctum');
        $this->user = $request->user('sanctum');
        if (!$this->user->hasRole('patient')) {
            $this->middleware('role:super_admin|admin:transaction')->except('store');
        }
    }


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
     * _Fetch paginated user completed transactions_
     *
     * Fetch completed transaction of user, paginated response. !! token required | super_admin, admin:transaction, patient
     *
     * @urlParam  user required The ID of the user.
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 1,
     * "user_id": 6,
     * "type": 0,
     * "status": 1,
     * "code": "GAHk3jj4L1LPGNxH",
     * "amount": 100,
     * "created_at": "2020-07-10T07:24:46.000000Z",
     * "updated_at": "2020-07-10T07:24:46.000000Z"
     * },
     * ...
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/users/6/transactions/complete?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/users/6/transactions/complete?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/users/6/transactions/complete",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 2,
     * "total": 2
     * }
     */
    public function loadAllUserCompletedTransactions(User $user)
    {
        $userCompletedTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 1)->paginate(10);
        return response()->json($userCompletedTransactions, 200);
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
     * _Create transaction_
     *
     * Get transaction object after create. user_id of transaction will be set to the user binded with token !! token required | patient
     *
     *
     * @bodyParam type int required The type of transaction. 0 => debit, 1 => credit
     * @bodyParam amount int required The transaction amount.
     * @bodyParam status int The status of transaction, default 0. 0 => initialized(tracked), 1 => completed
     * @bodyParam type int required The type of transaction. 0 => debit, 1 => credit
     *
     *
     * @response  201 {
     * "user_id": 6,
     * "amount": 100,
     * "type": 0,
     * "status": 0,
     * "code": "y4AAAMm3ETJaAwMR",
     * "updated_at": "2020-07-10T07:25:02.000000Z",
     * "created_at": "2020-07-10T07:25:02.000000Z",
     * "id": 2
     * }
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required| numeric',
            'amount' => 'required| numeric| min: 0',
            'status' => 'sometimes| numeric',
        ]);

        $newTransaction = new Transaction();
        $newTransaction->user_id = $this->user->id;
        $newTransaction->amount = $request->amount;
        $newTransaction->type = $request->type;
        if ($request->has('status')) {
            $newTransaction->status = $request->status;
        }
        do {
            $code = Str::random(16);
            $transaction = Transaction::where('code', $code)->first();
        } while ($transaction);
        $newTransaction->code = $code;
//        if($request->has('agent_commission')) {
//            $newTransaction->agent_commission = $request->agent_commission;
//        }
        $newTransaction->save();
        return response()->json($newTransaction, 201);
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
     * _Update transaction status_
     *
     * Update transaction status to completed. user_id of transaction has to be same with the user binded with token !! token required | super_admin, admin:transaction, patient
     *
     *
     * @urlParam  transaction required The ID of the transaction.
     * @bodyParam status int The status of transaction, default 0. 0 => initialized(tracked), 1 => completed
     *
     *
     * @response  204
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            'status' => 'required| numeric'
        ]);
        if ($this->user->id != $transaction->user_id) {
            return response()->json('access forbidden', 403);
        }
        $transaction->status = $request->status;
        $transaction->save();
        return response()->noContent();
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
