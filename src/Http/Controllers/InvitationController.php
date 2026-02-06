<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use HasinHayder\TyroLogin\Models\InvitationLink;
use HasinHayder\TyroLogin\Models\InvitationReferral;
use HasinHayder\TyroLogin\Helpers\InvitationHelper;

class InvitationController extends BaseController
{
    /**
     * Check if invitation system is enabled.
     */
    protected function ensureInvitationSystemEnabled()
    {
        if (!config('tyro-dashboard.features.invitation_system', true)) {
            abort(404, 'Invitation system is disabled.');
        }
    }

    /**
     * Check if invitation tables exist in database.
     */
    protected function ensureInvitationTablesExist()
    {
        if (!Schema::hasTable('invitation_links') || !Schema::hasTable('invitation_referrals')) {
            return view('tyro-dashboard::errors.missing-invitation-tables', $this->getViewData());
        }
        
        return null;
    }

    /**
     * Display admin invitation management page.
     */
    public function adminIndex(Request $request)
    {
        $this->ensureInvitationSystemEnabled();
        
        if ($view = $this->ensureInvitationTablesExist()) {
            return $view;
        }
        
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $query = InvitationLink::with(['user', 'referrals']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $userModel = $this->getUserModel();
            
            // Get user IDs that match the search
            $userIds = $userModel::where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->pluck('id');
            
            $query->whereIn('user_id', $userIds)
                  ->orWhere('hash', 'like', "%{$search}%");
        }

        $links = $query->orderBy('created_at', 'desc')
            ->paginate(config('tyro-dashboard.pagination.users', 15));

        return view('tyro-dashboard::invitations.admin-index', $this->getViewData([
            'links' => $links,
        ]));
    }

    /**
     * Show create invitation form for admin.
     */
    public function adminCreate()
    {
        $this->ensureInvitationSystemEnabled();
        
        if ($view = $this->ensureInvitationTablesExist()) {
            return $view;
        }
        
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $userModel = $this->getUserModel();
        $users = $userModel::orderBy('name')->get();

        return view('tyro-dashboard::invitations.admin-create', $this->getViewData([
            'users' => $users,
        ]));
    }

    /**
     * Store invitation link (admin).
     */
    public function adminStore(Request $request)
    {
        $this->ensureInvitationSystemEnabled();
        
        if ($view = $this->ensureInvitationTablesExist()) {
            return $view;
        }
        
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($request->user_id);

        // Check if user already has an invitation link
        $existingLink = InvitationLink::where('user_id', $user->id)->first();

        if ($existingLink) {
            return redirect()->route('tyro-dashboard.invitations.admin.index')
                ->with('error', "User {$user->name} already has an invitation link.");
        }

        // Create new invitation link
        $hash = Str::random(32);
        
        InvitationLink::create([
            'user_id' => $user->id,
            'hash' => $hash,
        ]);

        return redirect()->route('tyro-dashboard.invitations.admin.index')
            ->with('success', "Invitation link created successfully for {$user->name}.");
    }

    /**
     * Delete invitation link (admin).
     */
    public function adminDestroy($id)
    {
        $this->ensureInvitationSystemEnabled();
        
        if ($view = $this->ensureInvitationTablesExist()) {
            return $view;
        }
        
        if (!$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $link = InvitationLink::findOrFail($id);
        $referralCount = $link->referrals()->count();
        $userName = $link->user ? $link->user->name : 'Unknown User';

        $link->delete();

        $message = "Invitation link for {$userName} has been deleted.";
        if ($referralCount > 0) {
            $message .= " ({$referralCount} referral record(s) were also removed)";
        }

        return redirect()->route('tyro-dashboard.invitations.admin.index')
            ->with('success', $message);
    }

    /**
     * Display user invitation panel.
     */
    public function userIndex()
    {
        $this->ensureInvitationSystemEnabled();
        
        if ($view = $this->ensureInvitationTablesExist()) {
            return $view;
        }
        
        $user = auth()->user();
        $invitationLink = InvitationLink::where('user_id', $user->id)
            ->with('referrals.referredUser')
            ->first();

        $referrals = collect([]);
        if ($invitationLink) {
            $referrals = $invitationLink->referrals()
                ->with('referredUser')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('tyro-dashboard::invitations.user-index', $this->getViewData([
            'invitationLink' => $invitationLink,
            'referrals' => $referrals,
        ]));
    }

    /**
     * Create invitation link for current user.
     */
    public function userCreate()
    {
        $this->ensureInvitationSystemEnabled();
        
        if ($view = $this->ensureInvitationTablesExist()) {
            return $view;
        }
        
        $user = auth()->user();

        // Check if user already has an invitation link
        $existingLink = InvitationLink::where('user_id', $user->id)->first();

        if ($existingLink) {
            return redirect()->route('tyro-dashboard.invitations.index')
                ->with('error', 'You already have an invitation link.');
        }

        // Create new invitation link
        $hash = Str::random(32);
        
        InvitationLink::create([
            'user_id' => $user->id,
            'hash' => $hash,
        ]);

        return redirect()->route('tyro-dashboard.invitations.index')
            ->with('success', 'Your invitation link has been created successfully!');
    }
}
