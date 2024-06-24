<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StateController extends Controller
{
    public function tasks()
    {
        $status = [
            'Pendiente',
            'Asignada',
            'Completada',
            'Rechazada'
        ];

        return response()->json(['status' => $status]);
    }

    public function meetings()
    {
        $status = [
            'Eliminado',
            'Creado',
            'Aplazado',
            'Realizado'
        ];

        return response()->json(['status' => $status]);
    }

    public function obligations()
    {
        $status = [
            'Inactiva',
            'Activa',
            'Auditada',
            'Pendiente',
            'Vencida'
        ];

        return response()->json(['status' => $status]);
    }
}