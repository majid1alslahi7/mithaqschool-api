<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin');
    }

    /**
     * Display a listing of all sessions with user details.
     */
    public function index()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.*',
                'users.username',
                'users.email',
                'users.last_login_at',
                'users.last_logout_at'
            )
            ->orderBy('sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->is_active = $session->last_activity > now()->subMinutes(30)->timestamp;
                $session->last_activity_human = date('Y-m-d H:i:s', $session->last_activity);
                return $session;
            });

        return response()->json([
            'sessions' => $sessions,
            'active_sessions_count' => $sessions->where('is_active', true)->count(),
            'total_sessions_count' => $sessions->count(),
        ]);
    }

    /**
     * Get current user sessions (multiple sessions per user).
     */
    public function userSessions(Request $request)
    {
        $user = $request->user();
        
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->is_active = $session->last_activity > now()->subMinutes(30)->timestamp;
                $session->last_activity_human = date('Y-m-d H:i:s', $session->last_activity);
                return $session;
            });

        return response()->json([
            'sessions' => $sessions,
            'active_count' => $sessions->where('is_active', true)->count(),
            'current_session_id' => $request->session()->getId(),
        ]);
    }

    /**
     * Remove the specified session.
     */
    public function destroy($id)
    {
        // لا يمكن حذف الجلسة الحالية
        if ($id === session()->getId()) {
            return response()->json(['message' => 'لا يمكن حذف جلسة الدخول الحالية'], 400);
        }
        
        DB::table('sessions')->where('id', $id)->delete();
        
        return response()->json(['message' => 'تم حذف الجلسة بنجاح']);
    }

    /**
     * Terminate all sessions for a user (except current).
     */
    public function terminateUserSessions(Request $request, $userId)
    {
        $this->authorize('terminateAll', \App\Models\Session::class);
        
        DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $request->session()->getId())
            ->delete();
        
        return response()->json(['message' => 'تم إنهاء جميع جلسات المستخدم بنجاح']);
    }
}