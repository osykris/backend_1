<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Empty_;

class EmployeeController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Data',
            'code' => 200,
            'error' => false,
            'data' =>  Employee::orderBy('name', 'asc')->get()
        ], 200);
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $this->data['employees'] = Employee::create($request->all());

            DB::commit();
            return response()->json([
                'message' => 'Data created',
                'code' => 200,
                'error' => false,
                'data' => $this->data
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 500
            ], 500);
        }
    }

    public function show(string $id)
    {
        $employee = Employee::find($id);
        if(!$employee) return response()->json(['message' => 'No data found'], 404);

        return response()->json([
            'message' => 'Data detail',
            'code' => 200,
            'error' => false,
            'results' => $employee
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::find($id);
            if (!$employee) return response()->json(['message' => 'No data found'], 404);

            $employee->update($request->all());
            $this->data['employees'] = $employee;

            DB::commit();
            return response()->json([
                'message' => 'Data updated',
                'code' => 200,
                'error' => false,
                'results' => $this->data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 500
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::find($id);
            if (!$employee) return response()->json(['message' => 'No data found'], 404);
            $employee->delete();

            DB::commit();
            return response()->json([
                'message' => 'Data deleted',
                'code' => 200,
                'error' => false,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 500
            ], 500);
        }
    }
}
