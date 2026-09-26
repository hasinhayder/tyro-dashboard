<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Models\Media;
use HasinHayder\TyroDashboard\Models\MediaCategory;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaCategoryController extends BaseController {
    private function flushMediaCache(): void {
        if (class_exists(\App\Support\BlogCache::class) && method_exists(\App\Support\BlogCache::class, 'flushMedia')) {
            \App\Support\BlogCache::flushMedia();
        }
    }

    private function isPrivilegedUser($user): bool {
        if (! $user) {
            return false;
        }
        $privilegedRoles = array_merge(
            config('tyro-dashboard.admin_roles', ['admin', 'super-admin']),
            ['editor']
        );

        return ! empty(array_intersect($privilegedRoles, $user->tyroRoleSlugs() ?? []));
    }

    private function canManageCategory(MediaCategory $category, $user): bool {
        if (! $user) {
            return false;
        }

        if (session()->has('impersonator_id')) {
            return $category->user_id === $user->id;
        }

        return $this->isPrivilegedUser($user) || $category->user_id === $user->id;
    }

    public function index(Request $request) {
        $user = auth()->user();
        $isAdmin = $this->isPrivilegedUser($user);
        $isImpersonating = session()->has('impersonator_id');

        $query = MediaCategory::query()->withCount('media')->with('creator');

        if ($isImpersonating || ! $isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $categories = $query->orderBy('name')->paginate(20)->withQueryString();

        $statsQuery = MediaCategory::query();
        if ($isImpersonating || ! $isAdmin) {
            $statsQuery->where('user_id', $user->id);
        }
        $totalCategories = $statsQuery->count();

        // Count distinct media items assigned to any visible category
        $categorizedMediaCount = Media::query()
            ->when($isImpersonating || ! $isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->whereHas('categories', function ($q) use ($isImpersonating, $isAdmin, $user) {
                if ($isImpersonating || ! $isAdmin) {
                    $q->where('tyro_media_categories.user_id', $user->id);
                }
            })
            ->count();

        return view('tyro-dashboard::media.categories.index', compact(
            'categories',
            'totalCategories',
            'categorizedMediaCount',
            'isAdmin'
        ));
    }

    public function store(Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:180',
            'description' => 'nullable|string|max:1000',
        ]);

        $slug = filled($validated['slug'] ?? null)
            ? Str::slug($validated['slug'])
            : MediaCategory::generateUniqueSlug($validated['name'], $user->id);

        // Ensure unique slug for this user
        if (MediaCategory::where('user_id', $user->id)->where('slug', $slug)->exists()) {
            $slug = MediaCategory::generateUniqueSlug($slug, $user->id);
        }

        $category = MediaCategory::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        $this->flushMediaCache();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Category created successfully.',
            ]);
        }

        return redirect()
            ->route(DashboardRoute::name('media.categories.index'))
            ->with('success', "Category '{$category->name}' created successfully.");
    }

    public function storeBulk(Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'categories' => 'required|string',
        ]);

        $rawNames = preg_split('/[,\r\n]+/', $validated['categories']);
        $created = [];
        $skipped = [];

        foreach ($rawNames as $raw) {
            $name = trim($raw);
            if ($name === '') {
                continue;
            }

            $name = mb_substr($name, 0, 150);

            // Check if user already has a category with this exact name (case-insensitive)
            $existing = MediaCategory::where('user_id', $user->id)
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->first();

            if ($existing) {
                $skipped[] = $name;
                continue;
            }

            $slug = MediaCategory::generateUniqueSlug($name, $user->id);

            $cat = MediaCategory::create([
                'user_id' => $user->id,
                'name' => $name,
                'slug' => $slug,
                'description' => null,
            ]);

            $created[] = $cat;
        }

        $this->flushMediaCache();

        $count = count($created);
        $message = "Added {$count} ".($count === 1 ? 'category' : 'categories')." successfully.";
        if (! empty($skipped)) {
            $skippedCount = count($skipped);
            $message .= " ({$skippedCount} duplicate ".($skippedCount === 1 ? 'category was' : 'categories were')." skipped).";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'created_count' => $count,
                'created' => $created,
                'skipped' => $skipped,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route(DashboardRoute::name('media.categories.index'))
            ->with($count > 0 ? 'success' : 'info', $message);
    }

    public function update(Request $request, MediaCategory $category) {
        $user = auth()->user();
        if (! $this->canManageCategory($category, $user)) {
            abort(403, 'You do not have permission to edit this category.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:180',
            'description' => 'nullable|string|max:1000',
        ]);

        $slug = filled($validated['slug'] ?? null)
            ? Str::slug($validated['slug'])
            : MediaCategory::generateUniqueSlug($validated['name'], $category->user_id, $category->id);

        if (MediaCategory::where('user_id', $category->user_id)->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = MediaCategory::generateUniqueSlug($slug, $category->user_id, $category->id);
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        $this->flushMediaCache();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Category updated successfully.',
            ]);
        }

        return redirect()
            ->route(DashboardRoute::name('media.categories.index'))
            ->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function destroy(Request $request, MediaCategory $category) {
        $user = auth()->user();
        if (! $this->canManageCategory($category, $user)) {
            abort(403, 'You do not have permission to delete this category.');
        }

        $categoryName = $category->name;
        $category->delete();

        $this->flushMediaCache();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Category '{$categoryName}' deleted successfully.",
            ]);
        }

        return redirect()
            ->route(DashboardRoute::name('media.categories.index'))
            ->with('success', "Category '{$categoryName}' deleted successfully.");
    }

    public function addMedia(Request $request, MediaCategory $category): JsonResponse {
        $user = auth()->user();
        if (! $this->canManageCategory($category, $user)) {
            abort(403, 'You do not have permission to modify this category.');
        }

        $validated = $request->validate([
            'media_ids' => 'required|array|min:1',
            'media_ids.*' => 'integer|exists:tyro_media,id',
        ]);

        $isAdmin = $this->isPrivilegedUser($user);
        $isImpersonating = session()->has('impersonator_id');

        $query = Media::query()->whereIn('id', $validated['media_ids']);
        if ($isImpersonating || ! $isAdmin) {
            $query->where('user_id', $user->id);
        }

        $allowedMediaIds = $query->pluck('id')->all();

        if (! empty($allowedMediaIds)) {
            $category->media()->syncWithoutDetaching($allowedMediaIds);
            $this->flushMediaCache();
        }

        $newCount = $category->media()->count();

        return response()->json([
            'success' => true,
            'attached_count' => count($allowedMediaIds),
            'total_media_count' => $newCount,
            'message' => 'Added '.count($allowedMediaIds).' media '.Str::plural('item', count($allowedMediaIds))." to '{$category->name}'.",
        ]);
    }
}
