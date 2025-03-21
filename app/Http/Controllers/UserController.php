<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\editUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Testing API Documentation",
 * description="API documentation for the Testing application",
 * @OA\Contact(
 * email="support@Testing.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="https://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Tag(
 * name="Users",
 * description="API Endpoints for managing users"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     * path="/users",
     * operationId="getUsersList",
     * tags={"Users"},
     * summary="Retrieve a list of users",
     * description="Returns a paginated list of registered users",
     * @OA\Parameter(
     * name="status",
     * in="query",
     * description="Filter users by status",
     * required=false,
     * @OA\Schema(type="string", example="active")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/User")
     * )
     * )
     * )
     */
    public function index(Request $request)
    {
        $query = User::withTrashed();
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        $users = $query->simplePaginate(3);
        return view('users', compact('users'));
    }

    /**
     * @OA\Post(
     * path="/users/create",
     * operationId="storeUser",
     * tags={"Users"},
     * summary="Create a new user",
     * description="Creates a new user record",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "email", "password"},
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", example="john@example.com"),
     * @OA\Property(property="password", type="string", example="password123"),
     * @OA\Property(property="phone_number", type="string", example="0123456789"),
     * @OA\Property(property="status", type="string", example="active")
     * )
     * ),
     * @OA\Response(response=201, description="User created successfully"),
     * @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? 'active',
        ]);
        return redirect()->back()->with('success', 'User created successfully!');
    }

    /**
     * @OA\Put(
     * path="/users/update/{id}",
     * operationId="updateUser",
     * tags={"Users"},
     * summary="Update an existing user",
     * description="Updates user information by ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="User ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "email"},
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", example="john@example.com"),
     * @OA\Property(property="phone_number", type="string", example="0123456789"),
     * @OA\Property(property="status", type="string", example="active")
     * )
     * ),
     * @OA\Response(response=200, description="User updated successfully"),
     * @OA\Response(response=404, description="User not found")
     * )
     */
    public function update(editUserRequest $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? 'active',
        ]);
        $request->status == "inactive" ? $user->delete() : $user->restore();
        return redirect()->back()->with('success', 'User updated successfully!');
    }

    /**
     * @OA\Post(
     * path="/users/destroy/{id}",
     * operationId="deleteUser",
     * tags={"Users"},
     * summary="Delete a user",
     * description="Soft deletes a user by updating their status to inactive",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="User ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=200, description="User deleted successfully"),
     * @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'inactive']);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    /**
     * @OA\Post(
     * path="/users/bulk-delete",
     * operationId="bulkDeleteUsers",
     * tags={"Users"},
     * summary="Bulk delete users",
     * description="Deletes multiple users by ID",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"selected_users"},
     * @OA\Property(property="selected_users", type="array", @OA\Items(type="integer"), example={1,2,3})
     * )
     * ),
     * @OA\Response(response=200, description="Users deleted successfully"),
     * @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function bulkDelete(Request $request)
    {
        $userIds = $request->input('selected_users');
        User::whereIn('id', $userIds)->update(['status' => 'inactive']);
        User::whereIn('id', $userIds)->delete();
        return redirect()->back()->with('success', count($userIds) . ' users deleted successfully.');
    }

    /**
     * @OA\Get(
     * path="/users/export",
     * operationId="exportUsers",
     * tags={"Users"},
     * summary="Export users to an Excel file",
     * description="Exports the list of users to an Excel file",
     * @OA\Response(
     * response=200,
     * description="File downloaded successfully",
     * @OA\MediaType(
     * mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
     * )
     * )
     * )
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
