<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kanban;
use App\Models\KanbanPermission;
use App\Models\KanbanInvite;
use App\Models\User;
use Illuminate\Support\Str;

class KanbanShareController extends Controller
{
    /**
     * Menampilkan halaman share untuk proyek Kanban.
     */
    public function index($kanbanId)
    {
        $kanban = Kanban::findOrFail($kanbanId);
        // Ambil semua permission (anggota) beserta data user-nya
        $permissions = KanbanPermission::with('user')->where('kanban_id', $kanbanId)->get();
        // Ambil undangan yang sudah dibuat
        $invites = KanbanInvite::where('kanban_id', $kanbanId)->orderBy('created_at', 'desc')->get();

        return view('kanban.share', compact('kanban', 'permissions', 'invites'));
    }

    /**
     * Update permission anggota (misalnya, mengganti role: editor, viewer, blocked).
     */
    public function updatePermission(Request $request, $kanbanId, $userId)
    {
        $request->validate([
            'role' => 'required|in:editor,viewer,blocked'
        ]);

        $permission = KanbanPermission::where('kanban_id', $kanbanId)
            ->where('user_id', $userId)
            ->first();

        if ($permission) {
            $permission->update(['role' => $request->role]);
        } else {
            // Jika belum ada, buat record baru (opsional)
            KanbanPermission::create([
                'kanban_id' => $kanbanId,
                'user_id'   => $userId,
                'role'      => $request->role
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Hapus permission anggota dari proyek.
     */
    public function deletePermission($kanbanId, $userId)
    {
        KanbanPermission::where('kanban_id', $kanbanId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Buat undangan (invite) ke proyek berdasarkan input invite_type.
     * Jika invite_type = 'id', langsung tambahkan user ke permission.
     * Jika invite_type = 'email', periksa apakah user ada; jika tidak, buat invite.
     */
    public function createInvite(Request $request, $kanbanId)
    {
        $inviteType = $request->input('invite_type');
        $role = $request->input('role');

        if ($inviteType === 'id') {
            // Mengundang dengan User ID
            $userId = $request->input('user_id');
            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'User ID tidak valid'], 422);
            }
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'User tidak ditemukan'], 404);
            }
            $existing = KanbanPermission::where('kanban_id', $kanbanId)->where('user_id', $userId)->first();
            if (!$existing) {
                KanbanPermission::create([
                    'kanban_id' => $kanbanId,
                    'user_id'   => $userId,
                    'role'      => $role
                ]);
            } else {
                // Update role jika sudah ada
                $existing->update(['role' => $role]);
            }
            return response()->json(['success' => true, 'message' => 'User telah ditambahkan ke proyek']);
        } else {
            // Mengundang dengan Email
            $request->validate([
                'email' => 'required|email'
            ]);
            $email = $request->input('email');
            $user = User::where('email', $email)->first();
            if ($user) {
                // Jika user ada, tambahkan langsung ke permission
                $existing = KanbanPermission::where('kanban_id', $kanbanId)->where('user_id', $user->id)->first();
                if (!$existing) {
                    KanbanPermission::create([
                        'kanban_id' => $kanbanId,
                        'user_id'   => $user->id,
                        'role'      => $role
                    ]);
                } else {
                    $existing->update(['role' => $role]);
                }
                return response()->json(['success' => true, 'message' => 'User telah ditambahkan ke proyek']);
            } else {
                // Jika user tidak ada, buat record undangan
                $token = Str::uuid()->toString();
                $invite = KanbanInvite::create([
                    'kanban_id'    => $kanbanId,
                    'invite_token' => $token,
                    'role'         => $role,
                    'email'        => $email,
                ]);

                // Di sini Anda bisa mengirim email undangan dengan token (opsional)
                return response()->json([
                    'success' => true,
                    'message' => 'Undangan telah dikirim melalui email',
                    'invite'  => $invite
                ]);
            }
        }
    }

    /**
     * Hapus undangan.
     */
    public function deleteInvite($kanbanId, $inviteId)
    {
        KanbanInvite::where('kanban_id', $kanbanId)
            ->where('id', $inviteId)
            ->delete();
        return response()->json(['success' => true]);
    }
}
