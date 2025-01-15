<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //  Fetch all users.
    public function get()
    {
        $users = User::all();

        return response()->json([
            'message' => 'All users fetched successfully',
            'users' => $users,
        ], 200);
    }

    // Fetch users based on role.
    public function getBasedonRole($role)
    {
        $users = User::where('role', $role)->get();

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found for the specified role',
            ], 404);
        }

        return response()->json([
            'message' => "Users with role '{$role}' fetched successfully",
            'users' => $users,
        ], 200);
    }

    // Register a new user. 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:50',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }


    // Delete a user by ID
    public function delete($id)
    {
        // Check if the user exists
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }


    // Update a user
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Validate only the fields that are being updated
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'role' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8',
        ]);

        // Prepare data for update
        $updateData = $request->only(['name', 'email', 'role', 'password']);

        if (isset($updateData['password'])) {
            $updateData['password'] = bcrypt($updateData['password']);
        }

        $user->update(array_filter($updateData));

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    //  --------------------
    // | Order api function |
    //  --------------------

    
    //  total number of orders.
    public function getTotalOrders()
    {
        $totalOrders = Order::count();

        return response()->json([
            'message' => 'Total orders fetched successfully',
            'total_orders' => $totalOrders,
        ], 200);
    }

    
    // total price of all orders 
    public function getTotalPrice()
    {
        $totalPrice = Order::sum('total_price');

        return response()->json([
            'message' => 'Total price fetched successfully',
            'total_price' => $totalPrice,
        ], 200);
    }

    
    // total number of orders created in a specific year    
    public function getTotalOrdersByYear($year)
    {
        $totalOrders = Order::whereYear('created_at', $year)->count();

        return response()->json([
            'message' => "Total orders for the year {$year} fetched successfully",
            'year' => $year,
            'total_orders' => $totalOrders,
        ], 200);
    }

    
    // total number of orders made by a specific user.
    public function getOrdersByUser($userId)
    {
        $totalOrders = Order::where('user_id', $userId)->count();

        return response()->json([
            'message' => "Total orders for user ID {$userId} fetched successfully",
            'user_id' => $userId,
            'total_orders' => $totalOrders,
        ], 200);
    }
}
