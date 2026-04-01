<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    // GET /api/cuentas
    public function index()
    {
        $userId = $this->resolveUserId();

        $query = Cuenta::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $cuentas = $query
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
        $userId = $this->resolveUserId();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'No existe un usuario de prueba en la tabla users.'
            ], 422);
        }

        $datos = $request->validate([
            'tipo' => 'required|in:corriente,ahorro',
            'numero_cuenta' => 'required|string|max:20',
            'saldo' => 'required|numeric|min:0',
            'moneda' => 'in:PEN,USD',
            'user_id' => 'sometimes|uuid',
        ]);

        $targetUserId = $userId ?? ($datos['user_id'] ?? null);

        if (!$targetUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Define TEST_USER_ID en .env o envia user_id en el body.'
            ], 422);
        }

        unset($datos['user_id']);

        $cuenta = Cuenta::create(array_merge($datos, [
            'user_id' => $targetUserId
        ]));

        return response()->json([
            'success' => true,
            'data' => $cuenta
        ], 201);
    }

    // GET /api/cuentas/{id}
    public function show($id)
    {
        $userId = $this->resolveUserId();

        $query = Cuenta::where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $cuenta = $query->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $cuenta
        ]);
    }

    // PUT /api/cuentas/{id}
    public function update(Request $request, $id)
    {
        $userId = $this->resolveUserId();

        $query = Cuenta::where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $cuenta = $query->firstOrFail();

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
        $userId = $this->resolveUserId();

        $query = Cuenta::where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $cuenta = $query->firstOrFail();

        $cuenta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cuenta eliminada correctamente'
        ]);
    }

    private function resolveUserId(): ?string
    {
        $testUserId = env('TEST_USER_ID');

        return $testUserId !== null && $testUserId !== '' ? (string) $testUserId : null;
    }
}