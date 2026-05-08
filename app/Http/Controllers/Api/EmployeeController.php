<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index() { return Employee::with('user')->latest()->paginate(20); }
    public function show(Employee $employee) { return $employee->load('user', 'productions'); }

    public function store(Request $request)
    {
        return Employee::create($request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['required', 'string', 'max:120'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'hired_at' => ['nullable', 'date'],
        ]));
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['required', 'string', 'max:120'],
            'salary' => ['required', 'numeric', 'min:0'],
            'hired_at' => ['nullable', 'date'],
        ]));

        return $employee;
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->noContent();
    }
}
