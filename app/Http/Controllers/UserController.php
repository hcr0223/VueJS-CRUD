<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller {

    /**
     * Get all users
     * @param Request
     * @return JsonResponse
     */
    public function all(Request $request) {
        $users = User::all();

        return response()->json($users, 201);
    }
    

    /**
     * Creating user
     * @param Request
     * @return JsonResponse
     */
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ],[
            'name.required' => 'The Name field is required',
            'email.required' => 'The Email field is required',
            'email.unique' => 'The email is already registered',
            'email.email' => 'The email must be a valid email',
            'password.required' => 'The Password field is required',
            'password.min' => 'The Password must contained at least 8 characters'
        ]);
        
        $user = User::create($validatedData);

        return response()->json($user, 201);
    }

    /**
     * Getting user by id
     * @params $id - id of the resource
     * @return JsonResponse
     */
    public function show($id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * updating user with new data
     * @param $id - id of the resource to be updated & request containing updated info
     * @return JsonResponse
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string',
            'email' => [
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'password' => 'string|min:8'
        ]);

        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * Deleting user by id
     * @param $id - id of the resource to be deleted
     * @return JsonResponse
     */
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }

}
