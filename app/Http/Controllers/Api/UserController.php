<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $users = User::search($search)
            ->latest()
            ->paginate();

        if ($users) {
            return ResponseFormatter::success($users);
        } else {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \App\Http\Requests\UserStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(UserStoreRequest::rules());

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            $user = User::where('id', $user->id)->first();

            $token = $user->createToken('auth-token');

            return response()->json([
                'data' => ['user' => $user, 'token' => $token->plainTextToken, 'profile' => $user->getProfilePhotoUrlAttribute()],
            ]);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        return new UserResource($user);
    }

    /**
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $validated = $request->validate(UserStoreRequest::perbarui());

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $fileName);
                $validated['image'] = $file->getClientOriginalName();
            }

            $validated['password'] = Hash::make($validated['password']);

            $user->update($validated);

            $token = $user->createToken('auth-token');

            return response()->json([
                'data' => ['user' => $user, 'token' => $token->plainTextToken, 'profile' => $user->getProfilePhotoUrlAttribute()],
            ]);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->first();

            $validated = $request->validate(UserStoreRequest::perbarui());

            if ($request->hasFile('profile_photo_path')) {
                $file = $request->file('profile_photo_path');
                $fileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/images';
                $file->move($destinationPath, $fileName);
                $validated['profile_photo_path'] = $file->getClientOriginalName();
            }

            $user->update($validated);

            $token = $user->createToken('auth-token');

            return response()->json([
                'data' => ['user' => $user, 'token' => $token->plainTextToken, 'profile' => $user->getProfilePhotoUrlAttribute()],
            ]);
        } catch (\Throwable $th) {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
