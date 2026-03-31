<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Notification::with('user');

        $query->filter($request->all());

        return NotificationResource::collection($query->paginate(20));
    }

    /**
     * Display notifications for the authenticated user and mark them as read.
     */
    public function myNotifications(Request $request)
    {
        $user = $request->user();
        $query = Notification::where('user_id', $user->id)->with('user');

        if ($request->boolean('mark_read', true)) {
            Notification::where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('is_read')->orWhere('is_read', false);
                })
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        $query->filter($request->all());

        return NotificationResource::collection(
            $query->orderByDesc('created_at')->paginate(20)
        );
    }

    /**
     * Display unread notifications.
     */
    public function unread(Request $request)
    {
        $query = Notification::with('user')
            ->where(function ($q) {
                $q->whereNull('is_read')->orWhere('is_read', false);
            });

        $filters = $request->all();
        unset($filters['is_read']);

        $query->filter($filters);

        return NotificationResource::collection(
            $query->orderByDesc('created_at')->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        $data = $request->validated();

        if (!array_key_exists('is_read', $data)) {
            $data['is_read'] = false;
        }

        if ($data['is_read']) {
            $data['read_at'] = $data['read_at'] ?? now();
        } else {
            $data['read_at'] = null;
        }

        $notification = Notification::create($data);

        return (new NotificationResource($notification->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new NotificationResource(
            Notification::with('user')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, string $id)
    {
        $notification = Notification::findOrFail($id);
        $data = $request->validated();

        if (array_key_exists('is_read', $data)) {
            if ($data['is_read']) {
                $data['read_at'] = $data['read_at'] ?? now();
            } else {
                $data['read_at'] = null;
            }
        }

        $notification->update($data);

        return new NotificationResource($notification->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Notification::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
