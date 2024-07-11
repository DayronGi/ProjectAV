<?php

namespace App\Http\Controllers;

use App\Models\Obligation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ObligationController extends Controller
{
    public function list(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $obligations = Obligation::with('reviewed_by', 'created_by', 'status')->orderBy('status', 'asc')->paginate($perPage);

        return response()->json($obligations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obligation_description' => 'required|string',
            'quantity' => 'required|integer',
            'period' => 'required|string',
            'alert_time' => 'required|integer',
            'created_by' => 'nullable|integer',
            'last_payment' => 'nullable|numeric',
            'expiration_date' => 'nullable|string',
            'observations' => 'required|string',
            'reviewed_by' => 'nullable|integer',
            'review_date' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $user = Auth::user();

            $obligation = new Obligation();
            $obligation->obligation_id = $request->obligation_id;
            $obligation->obligation_description = $request->obligation_description;
            $obligation->quantity = $request->quantity;
            $obligation->period = $request->period;
            $obligation->alert_time = $request->alert_time;
            $obligation->created_by = $user->id;
            $obligation->last_payment = $request->last_payment;
            $obligation->expiration_date = $request->expiration_date;
            $obligation->observations = $request->observations;
            $obligation->reviewed_by = $request->reviewed_by;
            $obligation->review_date = $request->review_date;
            $obligation->status = 10;

            $obligation->save();

            $obligation->load('reviewed_by', 'created_by', 'status');

            return response()->json(['message' => 'Obligation creado correctamente.', 'obligation' => $obligation], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Error al intentar guardar obligation.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function view($obligation_id)
    {
        $obligation = Obligation::with('reviewed_by', 'created_by', 'status')->findORfail($obligation_id);
        return response()->json(['obligation' => $obligation]);
    }

    public function update(Request $request, $obligation_id)
    {

        $validator = Validator::make($request->all(), [
            'obligation_description' => 'required|string',
            'quantity' => 'required|integer',
            'period' => 'required|string',
            'alert_time' => 'required|integer',
            'last_payment' => 'nullable|numeric',
            'expiration_date' => 'nullable|string',
            'observations' => 'required|string',
            'reviewed_by' => 'nullable|integer',
            'review_date' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $obligation = Obligation::findORfail($obligation_id);
            $obligation->update([
                'obligation_description' => $request->obligation_description,
                'quantity' => $request->quantity,
                'period' => $request->period,
                'alert_time' => $request->alert_time,
                'last_payment' => $request->last_payment,
                'expiration_date' => $request->expiration_date,
                'observations' => $request->observations,
                'reviewed_by' => $request->reviewed_by,
                'review_date' => $request->review_date,
            ]);
            return response()->json(['message' => 'Obligation actualizado correctamente.']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Error al intentar actualizar obligation.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function delete($obligation_id)
    {
        $obligation = Obligation::findORfail($obligation_id);
        $obligation->update([
            'status' => 9
        ]);
        return response()->json(['message' => 'Obligation eliminado correctamente.']);
    }

    public function store_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_ini' => 'required|date',
            'date_end' => 'nullable|date',
            'paid' => 'required|float',
            'observations' => 'required|string',
            'created_by' => 'required|integer',
            'creation_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $payment = new Payment();
            $payment->date_ini = $request->date_ini;
            $payment->date_end = $request->date_end;
            $payment->paid = $request->paid;
            $payment->observations = $request->observations;
            $payment->created_by = $request->created_by;
            $payment->creation_date = $request->creation_date;
            $payment->status = 2;

            $payment->save();

            return response()->json(['message' => 'Payment creado correctamente.', 'payment' => $payment], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Error al intentar guardar payment.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function list_payments()
    {
        $payment = Payment::all();
        return response()->json($payment);
    }
}