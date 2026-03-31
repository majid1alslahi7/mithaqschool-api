<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuardianRequest;
use App\Http\Requests\UpdateGuardianRequest;
use App\Http\Requests\UpdateGuardianAvatarRequest;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GuardianResource::collection(Guardian::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuardianRequest $request)
    {
        $validatedData = $request->validated();
        $guardian = Guardian::create($validatedData);
        $guardianName = trim(($guardian->f_name ?? '') . ' ' . ($guardian->l_name ?? ''));
        $message = $guardianName !== '' ? "تمت إضافة ولي أمر جديد: {$guardianName}." : 'تمت إضافة ولي أمر جديد.';
        app(SystemNotificationService::class)->notifyAllUsers('إضافة ولي أمر', $message);
        return response()->json($guardian, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new GuardianResource(Guardian::with('user', 'students')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuardianRequest $request, Guardian $guardian)
    {
        $validatedData = $request->validated();
        $guardian->update($validatedData);
        return response()->json($guardian, 200);
    }

    /**
     * Get the avatar for the specified guardian.
     *
     * @param Guardian $guardian
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvatar(Guardian $guardian)
    {
        return response()->json([
            'avatar_url' => $guardian->avatar_url,
        ]);
    }

    /**
     * Update the avatar for the specified guardian.
     *
     * @param UpdateGuardianAvatarRequest $request
     * @param Guardian $guardian
     * @return GuardianResource
     */
    public function updateAvatar(UpdateGuardianAvatarRequest $request, Guardian $guardian)
    {
        // Delete the old avatar if it exists
        if ($guardian->avatar_path) {
            Storage::disk('public')->delete($guardian->avatar_path);
        }

        // Upload the new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update the guardian's avatar_path
        $guardian->update(['avatar_path' => $path]);

        return new GuardianResource($guardian);
    }

    /**
     * Delete the avatar for the specified guardian.
     *
     * @param Guardian $guardian
     * @return GuardianResource
     */
    public function deleteAvatar(Guardian $guardian)
    {
        // Delete the old avatar if it exists
        if ($guardian->avatar_path) {
            Storage::disk('public')->delete($guardian->avatar_path);
        }

        // Update the guardian's avatar_path to null
        $guardian->update(['avatar_path' => null]);

        return new GuardianResource($guardian);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Guardian::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
