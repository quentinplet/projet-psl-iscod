<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserService $userService)
    {
        // Récupérer les filtres de la requête
        $filters = $request->only([
            'search',
            'per_page',
            'sort_field',
            'sort_direction',
            'role_filter',
            'retail_store_id'
        ]);

        $currentUser = Auth::user();

        $users = $userService->getFilteredUsers($currentUser, $filters);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        // $data['is_admin'] = true;

        //use policies to determine if the user is admin or manager
        try {
            $this->authorize('create', [User::class, $data]);
        } catch (AuthorizationException $e) {
            throw ValidationException::withMessages([
                'role' => [$e->getMessage()],
            ]);
        }

        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::with('retailStore')->findOrFail($user->id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $data['updated_by'] = $request->user()->id;

        //use policies to determine if the user is admin or manager
        try {
            $this->authorize('update', [$user, $data]);
        } catch (AuthorizationException $e) {
            throw ValidationException::withMessages([
                'role' => [$e->getMessage()],
            ]);
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //use policies to determine if the user is admin or manager
        try {
            $this->authorize('delete', $user);
        } catch (AuthorizationException $e) {
            throw ValidationException::withMessages([
                'role' => [$e->getMessage()],
            ]);
        }
        $user->delete();

        return response()->noContent();
    }

    public function getUserRoles(): JsonResponse
    {
        $currentUser = Auth::user();
        $roles = User::getRolesForAdminUser($currentUser);
        return response()->json([
            'roles' => $roles,
        ]);
    }
}
