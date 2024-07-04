<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    public function list(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        // $meetings = Meeting::with('called_by', 'created_by', 'topics', 'status')->orderBy('status', 'asc')->paginate($perPage);

        $user = Auth::user();

        return response()->json( $user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meeting_date' => 'required|string',
            'start_hour' => 'nullable|string',
            'called_by' => 'required|integer',
            'placement' => 'nullable|string',
            'meeting_description' => 'required|string',
            'empty_field' => 'nullable|string',
            'topics' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $user = Auth::user();

            $meeting = new Meeting();
            $meeting->meeting_date = $request->meeting_date;
            $meeting->start_hour = $request->start_hour;
            $meeting->called_by = $request->called_by;
            $meeting->placement = $request->placement;
            $meeting->meeting_description = $request->meeting_description;
            $meeting->empty_field = $request->empty_field;
            $meeting->topics = $request->topics;
            $meeting->created_by = $user->id;
            $meeting->creation_date = \Carbon\Carbon::now();
            $meeting->status = 2;

            $meeting->save();

            $meeting->load('called_by', 'created_by', 'topics', 'status');

            return response()->json(['message' => 'meeting creado correctamente.', 'meeting' => $meeting], 201);
        } catch (\Exception $e) {

            \Log::error($e->getMessage());
            return response()->json(['error' => 'Error al intentar guardar meeting.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function view($meeting_id)
    {
        $meeting = Meeting::with('called_by', 'created_by', 'topics', 'status')->findORfail($meeting_id);
        return response()->json(['meeting' => $meeting]);
    }

    public function update(Request $request, $meeting_id)
    {

        $validator = Validator::make($request->all(), [
            'meeting_date' => 'required|string',
            'start_hour' => 'nullable|string',
            'placement' => 'nullable|string',
            'meeting_description' => 'required|string',
            'empty_field' => 'nullable|string',
            'topics' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $meeting = Meeting::findORfail($meeting_id);
            $meeting->update([
                'meeting_date' => $request->meeting_date,
                'start_hour' => $request->start_hour,
                'placement' => $request->placement,
                'meeting_description' => $request->meeting_description,
                'empty_field' => $request->empty_field,
                'topics' => $request->topics,
            ]);
            return response()->json(['message' => 'Meeting actualizado correctamente.']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Error al intentar actualizar meeting.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function delete($meeting_id)
    {
        $meeting = Meeting::findORfail($meeting_id);
        $meeting->update([
            'status' => 1
        ]);
        return response()->json(['message' => 'Meeting eliminado correctamente.']);
    }
}