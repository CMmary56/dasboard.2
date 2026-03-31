<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    // GET /api/cuentas
    public function index()
    {
        $cuentas = Cuenta::where('user_id', 1)
            ->orderBy('tipo')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cuentas
        ]);
    }

    // POST /api/cuentas
    public function store(Request $request)
    {
        $datos = $request->validate([
            'tipo' => 'required|in:corriente,ahorro',
            'numero_cuenta' => 'required|string|max:20',
            'saldo' => 'required|numeric|min:0',
            'moneda' => 'in:PEN,USD',
        ]);

        $cuenta = Cuenta::create(array_merge($datos, [
            'user_id' => 1
        ]));

        return response()->json([
            'success' => true,
            'data' => $cuenta
        ], 201);
    }

    // GET /api/cuentas/{id}
    public function show($id)
    {
        $cuenta = Cuenta::where('id', $id)
            ->where('user_id', 1)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $cuenta
        ]);
    }
}