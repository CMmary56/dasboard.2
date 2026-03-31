<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    // GET /api/cuentas
    public function index()
    {
        // Se usa 1 temporalmente en lugar de auth()->id()
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
            'user_id' => 1 // Se usa 1 temporalmente
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
            ->where('user_id', 1) // Se usa 1 temporalmente
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $cuenta
        ]);
    }

    // PUT /api/cuentas/{id}
    public function update(Request $request, $id)
    {
        $cuenta = Cuenta::where('id', $id)
            ->where('user_id', 1) // Se usa 1 temporalmente
            ->firstOrFail();

        $datos = $request->validate([
            'tipo' => 'sometimes|in:corriente,ahorro',
            'numero_cuenta' => 'sometimes|string|max:20',
            'saldo' => 'sometimes|numeric|min:0',
            'moneda' => 'sometimes|in:PEN,USD',
        ]);

        $cuenta->update($datos);

        return response()->json([
            'success' => true,
            'data' => $cuenta
        ]);
    }

    // DELETE /api/cuentas/{id}
    public function destroy($id)
    {
        $cuenta = Cuenta::where('id', $id)
            ->where('user_id', 1) // Se usa 1 temporalmente
            ->firstOrFail();

        $cuenta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cuenta eliminada correctamente'
        ]);
    }
}