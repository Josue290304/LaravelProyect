<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos
     * GET /api/productos
     */
    public function index()
    {
        try {
            $productos = Producto::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Lista de productos',
                'data' => $productos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo producto
     * POST /api/productos
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:200',
            'precio' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0|max:100'
        ], [
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'El código ya existe',
            'nombre.required' => 'El nombre es obligatorio',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser numérico',
            'impuesto.required' => 'El impuesto es obligatorio',
            'impuesto.numeric' => 'El impuesto debe ser numérico',
            'impuesto.max' => 'El impuesto no puede ser mayor a 100%'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $producto = Producto::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un producto específico
     * GET /api/productos/{id}
     */
    public function show($id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detalle del producto',
                'data' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un producto
     * PUT/PATCH /api/productos/{id}
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'codigo' => 'sometimes|required|string|max:50|unique:productos,codigo,' . $id,
            'nombre' => 'sometimes|required|string|max:200',
            'precio' => 'sometimes|required|numeric|min:0',
            'impuesto' => 'sometimes|required|numeric|min:0|max:100'
        ], [
            'codigo.unique' => 'El código ya existe',
            'nombre.required' => 'El nombre es obligatorio',
            'precio.numeric' => 'El precio debe ser numérico',
            'impuesto.numeric' => 'El impuesto debe ser numérico',
            'impuesto.max' => 'El impuesto no puede ser mayor a 100%'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $producto->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un producto
     * DELETE /api/productos/{id}
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $producto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}