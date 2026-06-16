<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Models\Media;
use HasinHayder\TyroDashboard\Models\StarredImportImage;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Color;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class MediaController extends BaseController {
    private const MEDIA_PER_PAGE = 12;

    private function getUnsplashAccessKey(): ?string {
        if (class_exists(\App\Support\SiteSettings::class) && method_exists(\App\Support\SiteSettings::class, 'unsplashAccessKey')) {
            return \App\Support\SiteSettings::unsplashAccessKey();
        }
        return config('tyro-dashboard.media.api_keys.unsplash');
    }

    private function getPixabayApiKey(): ?string {
        if (class_exists(\App\Support\SiteSettings::class) && method_exists(\App\Support\SiteSettings::class, 'pixabayApiKey')) {
            return \App\Support\SiteSettings::pixabayApiKey();
        }
        return config('tyro-dashboard.media.api_keys.pixabay');
    }

    private function getFreepikApiKey(): ?string {
        if (class_exists(\App\Support\SiteSettings::class) && method_exists(\App\Support\SiteSettings::class, 'freepikApiKey')) {
            return \App\Support\SiteSettings::freepikApiKey();
        }
        return config('tyro-dashboard.media.api_keys.freepik');
    }

    private function getPexelsApiKey(): ?string {
        if (class_exists(\App\Support\SiteSettings::class) && method_exists(\App\Support\SiteSettings::class, 'pexelsApiKey')) {
            return \App\Support\SiteSettings::pexelsApiKey();
        }
        return config('tyro-dashboard.media.api_keys.pexels');
    }

    private function flushMediaCache(): void {
        if (class_exists(\App\Support\BlogCache::class) && method_exists(\App\Support\BlogCache::class, 'flushMedia')) {
            \App\Support\BlogCache::flushMedia();
        }
    }

    public function index(Request $request) {
        $user = auth()->user();
        $isAdmin = ! empty(array_intersect(
            config('tyro-dashboard.admin_roles', ['admin', 'super-admin']),
            $user?->tyroRoleSlugs() ?? []
        ));

        $isImpersonating = session()->has('impersonator_id');

        $query = Media::latest();

        if ($isImpersonating || ! $isAdmin) {
            $query->where('user_id', $user->id);
        }

        $requestedView = $request->query('view');
        $mediaView = $requestedView === 'list' || $requestedView === 'grid'
            ? $requestedView
            : ($request->cookie('dashboard_media_view') === 'list' ? 'list' : 'grid');

        $requestedPerPage = $request->query('per_page');
        $mediaPerPage = in_array((int) $requestedPerPage, [12, 24, 48, 96], true)
            ? (int) $requestedPerPage
            : (in_array((int) $request->cookie('dashboard_media_per_page'), [12, 24, 48, 96], true)
                ? (int) $request->cookie('dashboard_media_per_page')
                : self::MEDIA_PER_PAGE);

        if ($request->filled('type')) {
            $query->where('mime_type', 'like', $request->type.'/%');
        }

        if ($request->filled('search')) {
            $query->where('filename', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $media = $query->paginate($mediaPerPage)->withQueryString();

        $statsQuery = Media::query();
        if ($isImpersonating || ! $isAdmin) {
            $statsQuery->where('user_id', $user->id);
        }
        $totalCount = $statsQuery->count();
        $totalSize = $statsQuery->sum('size');

        $uploadDates = (clone $statsQuery)
            ->selectRaw('DATE(created_at) as upload_date')
            ->groupBy('upload_date')
            ->orderByDesc('upload_date')
            ->pluck('upload_date')
            ->map(fn ($d) => \Carbon\Carbon::parse($d));

        $importerKeys = [
            'unsplash' => (bool) $this->getUnsplashAccessKey(),
            'pixabay' => (bool) $this->getPixabayApiKey(),
            'freepik' => (bool) $this->getFreepikApiKey(),
            'pexels' => (bool) $this->getPexelsApiKey(),
        ];

        $starredImages = $this->starredImagesForCurrentUser();

        $response = response()->view('tyro-dashboard::media.index', compact('media', 'totalCount', 'totalSize', 'importerKeys', 'uploadDates', 'mediaView', 'starredImages', 'mediaPerPage', 'isAdmin'));

        if ($requestedView === 'list' || $requestedView === 'grid') {
            $response->cookie(cookie(
                'dashboard_media_view',
                $mediaView,
                60 * 24 * 365,
                '/',
                null,
                $request->isSecure(),
                false,
                false,
                'lax'
            ));
        }

        if ($requestedPerPage && in_array((int) $requestedPerPage, [12, 24, 48, 96], true)) {
            $response->cookie(cookie(
                'dashboard_media_per_page',
                $mediaPerPage,
                60 * 24 * 365,
                '/',
                null,
                $request->isSecure(),
                false,
                false,
                'lax'
            ));
        }

        return $response;
    }

    public function picker(Request $request): JsonResponse {
        $user = auth()->user();
        $isAdmin = ! empty(array_intersect(
            config('tyro-dashboard.admin_roles', ['admin', 'super-admin']),
            $user?->tyroRoleSlugs() ?? []
        ));

        $isImpersonating = session()->has('impersonator_id');

        $query = Media::latest();

        if ($isImpersonating || ! $isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('type')) {
            $query->where('mime_type', 'like', $request->type.'/%');
        }

        if ($request->filled('search')) {
            $query->where('filename', 'like', '%'.$request->search.'%');
        }

        $media = $query->paginate(30);

        return response()->json([
            'data' => $media->map(fn ($m) => [
                'id' => $m->id,
                'url' => $m->url,
                'thumbnail_url' => $m->thumbnail_url,
                'webp_url' => $m->webp_url,
                'filename' => $m->filename,
                'mime_type' => $m->mime_type,
                'is_image' => $m->is_image,
                'size' => $m->formatted_size,
                'original_size' => $m->formatted_size,
                'webp_size' => $m->webp_path ? $this->formatStorageSize($m->disk, $m->webp_path) : null,
                'alt_text' => $m->alt_text,
                'source_url' => $m->source_url,
            ]),
            'next_page_url' => $media->nextPageUrl(),
            'total' => $media->total(),
        ]);
    }

    public function store(Request $request): JsonResponse {
        $request->validate([
            'file' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,doc,docx,mp4,mp3,zip',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $extension = strtolower($file->getClientOriginalExtension());

        $hash = md5(uniqid('', true));
        $safeName = $hash.'.'.$extension;
        $path = $file->storeAs('media', $safeName, 'public');

        $webpPath = null;
        $webpConvertibleMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff'];
        if (in_array($mimeType, $webpConvertibleMimes)) {
            $webpDiskPath = 'media/'.$hash.'.webp';
            $webpFullPath = Storage::disk('public')->path($webpDiskPath);
            try {
                $manager = new ImageManager(new GdDriver);
                $webpImage = $manager->decode($file->getRealPath());
                if ($mimeType === 'image/png') {
                    $webpImage->setBackgroundColor(Color::transparent());
                }
                $webpImage->save($webpFullPath);
                $webpPath = $webpDiskPath;
            } catch (\Exception $e) {
                // WebP generation is best-effort
            }
        }

        $thumbnailPath = $this->generateThumbnail($path, $hash, 'public', $mimeType);

        $media = Media::create([
            'user_id' => auth()->id(),
            'filename' => $originalName,
            'path' => $path,
            'webp_path' => $webpPath,
            'thumbnail_path' => $thumbnailPath,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'size' => $size,
            'alt_text' => null,
            'source_url' => null,
        ]);

        $this->flushMediaCache();

        return response()->json([
            'id' => $media->id,
            'url' => $media->url,
            'thumbnail_url' => $media->thumbnail_url,
            'webp_url' => $media->webp_url,
            'filename' => $media->filename,
            'mime_type' => $media->mime_type,
            'is_image' => $media->is_image,
            'formatted_size' => $media->formatted_size,
            'original_size' => $media->formatted_size,
            'webp_size' => $media->webp_path ? $this->formatStorageSize($media->disk, $media->webp_path) : null,
        ]);
    }

    private function formatStorageSize(string $disk, string $path): ?string {
        if (! Storage::disk($disk)->exists($path)) {
            return null;
        }

        $bytes = Storage::disk($disk)->size($path);

        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1048576, 1).' MB';
    }

    public function rename(Request $request, Media $media): JsonResponse {
        $request->validate([
            'filename' => 'required|string|max:200',
        ]);

        $media->update(['filename' => $request->filename]);

        $this->flushMediaCache();

        return response()->json(['success' => true, 'filename' => $media->filename]);
    }

    public function updateAlt(Request $request, Media $media): JsonResponse {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $media->update(['alt_text' => $request->alt_text]);

        $this->flushMediaCache();

        return response()->json(['success' => true]);
    }

    public function cropResize(Request $request, Media $media): JsonResponse {
        $isReplace = filter_var($request->input('replace', false), FILTER_VALIDATE_BOOLEAN);

        $request->validate([
            'mode' => 'required|in:crop,resize',
            'new_filename' => ($isReplace ? 'nullable' : 'required').'|string|max:200',
            'crop_x' => 'required_if:mode,crop|numeric|min:0',
            'crop_y' => 'required_if:mode,crop|numeric|min:0',
            'crop_width' => 'required_if:mode,crop|numeric|min:1',
            'crop_height' => 'required_if:mode,crop|numeric|min:1',
            'resize_width' => 'nullable|integer|min:1|max:10000',
            'resize_height' => 'nullable|integer|min:1|max:10000',
        ]);

        if (! $media->is_image) {
            return response()->json(['message' => 'Only images can be cropped or resized.'], 422);
        }

        $sourcePath = Storage::disk($media->disk)->path($media->path);
        $manager = new ImageManager(new GdDriver);
        $image = $manager->decode($sourcePath);

        if ($request->mode === 'crop') {
            $image->crop(
                (int) $request->crop_width,
                (int) $request->crop_height,
                (int) $request->crop_x,
                (int) $request->crop_y,
            );
        } else {
            $w = $request->filled('resize_width') ? (int) $request->resize_width : null;
            $h = $request->filled('resize_height') ? (int) $request->resize_height : null;
            if ($w !== null && $h !== null) {
                $image->scale($w, $h);
            } elseif ($w !== null) {
                $image->scale(width: $w);
            } else {
                $image->scale(height: $h);
            }
        }

        if ($media->mime_type === 'image/png') {
            $image->setBackgroundColor(Color::transparent());
        }

        $webpConvertibleMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff'];

        if ($isReplace) {
            $targetFullPath = Storage::disk($media->disk)->path($media->path);
            $image->save($targetFullPath);

            if ($media->webp_path && in_array($media->mime_type, $webpConvertibleMimes)) {
                $webpFullPath = Storage::disk($media->disk)->path($media->webp_path);
                $webpImage = $manager->decode($targetFullPath);
                if ($media->mime_type === 'image/png') {
                    $webpImage->setBackgroundColor(Color::transparent());
                }
                $webpImage->save($webpFullPath);
            }

            $originalHash = pathinfo($media->path, PATHINFO_FILENAME);
            $thumbPath = $this->generateThumbnail($media->path, $originalHash, $media->disk, $media->mime_type);
            $media->update(['size' => filesize($targetFullPath), 'thumbnail_path' => $thumbPath]);

            $this->flushMediaCache();

            return response()->json([
                'id' => $media->id,
                'url' => $media->url,
                'webp_url' => $media->webp_url,
                'thumbnail_url' => $media->thumbnail_url,
                'filename' => $media->fresh()->filename,
                'mime_type' => $media->mime_type,
                'formatted_size' => $media->fresh()->formatted_size,
            ]);
        }

        $extension = strtolower(pathinfo($media->path, PATHINFO_EXTENSION));
        $hash = md5(uniqid('', true));
        $newDiskPath = 'media/'.$hash.'.'.$extension;
        $newFullPath = Storage::disk($media->disk)->path($newDiskPath);
        $image->save($newFullPath);

        $webpPath = null;
        if (in_array($media->mime_type, $webpConvertibleMimes)) {
            try {
                $webpDiskPath = 'media/'.$hash.'.webp';
                $webpFullPath = Storage::disk($media->disk)->path($webpDiskPath);
                $webpImage = $manager->decode($newFullPath);
                if ($media->mime_type === 'image/png') {
                    $webpImage->setBackgroundColor(Color::transparent());
                }
                $webpImage->save($webpFullPath);
                $webpPath = $webpDiskPath;
            } catch (\Exception $e) {
                // WebP generation is best-effort
            }
        }

        $newMedia = Media::create([
            'user_id' => auth()->id(),
            'filename' => $request->new_filename,
            'path' => $newDiskPath,
            'webp_path' => $webpPath,
            'thumbnail_path' => $this->generateThumbnail($newDiskPath, $hash, $media->disk, $media->mime_type),
            'disk' => $media->disk,
            'mime_type' => $media->mime_type,
            'size' => filesize($newFullPath),
            'alt_text' => $media->alt_text,
            'source_url' => $media->source_url,
        ]);

        $this->flushMediaCache();

        return response()->json([
            'id' => $newMedia->id,
            'url' => $newMedia->url,
            'webp_url' => $newMedia->webp_url,
            'filename' => $newMedia->filename,
            'mime_type' => $newMedia->mime_type,
            'formatted_size' => $newMedia->formatted_size,
        ]);
    }

    public function destroy(Media $media): JsonResponse {
        $user = auth()->user();
        if (! $this->canDeleteMedia($media, $user)) {
            abort(403, 'You can only delete media you have uploaded.');
        }

        $this->deleteMediaRecord($media);
        $this->flushMediaCache();

        return response()->json(['success' => true]);
    }

    public function bulkDestroy(Request $request): RedirectResponse {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer'],
        ]);

        $user = auth()->user();
        $query = Media::query()->whereIn('id', $validated['selected_ids']);

        if (! $this->canDeleteAnyMedia($user)) {
            $query->where('user_id', $user->id);
        }

        $deletedCount = 0;
        $query->get()->each(function (Media $media) use (&$deletedCount) {
            $this->deleteMediaRecord($media);
            $deletedCount++;
        });

        if ($deletedCount > 0) {
            $this->flushMediaCache();
        }

        return redirect()
            ->route(DashboardRoute::name('media'), $request->except(['_token', '_method', 'selected_ids']))
            ->with('success', "Deleted {$deletedCount} media ".($deletedCount === 1 ? 'file' : 'files').'.');
    }

    private function canDeleteMedia(Media $media, $user): bool {
        return $this->canDeleteAnyMedia($user) || $media->user_id === $user->id;
    }

    private function canDeleteAnyMedia($user): bool {
        return ! session()->has('impersonator_id') && ! empty(array_intersect(
            ['admin', 'super-admin', 'editor'],
            $user?->tyroRoleSlugs() ?? []
        ));
    }

    private function deleteMediaRecord(Media $media): void {
        Storage::disk($media->disk)->delete($media->path);
        if ($media->webp_path) {
            Storage::disk($media->disk)->delete($media->webp_path);
        }
        if ($media->thumbnail_path) {
            Storage::disk($media->disk)->delete($media->thumbnail_path);
        }
        $media->delete();
    }

    public function storeStarredImage(Request $request): JsonResponse {
        $payload = $this->validateStarredImagePayload($request, true);
        $attributes = $this->mapStarredImageAttributes($payload);

        $starredImage = StarredImportImage::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'star_key' => $attributes['star_key'],
            ],
            $attributes,
        );

        return response()->json([
            'success' => true,
            'data' => $starredImage->toImporterArray(),
        ]);
    }

    public function destroyStarredImage(Request $request): JsonResponse {
        $payload = $this->validateStarredImagePayload($request, false);
        $starKey = $payload['star_key'] ?? $this->makeStarKey($payload);

        StarredImportImage::query()
            ->where('user_id', auth()->id())
            ->where('star_key', $starKey)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function imageSearch(Request $request): JsonResponse {
        $request->validate([
            'provider' => 'required|in:unsplash,pixabay,freepik,pexels',
            'q' => 'required|string|max:200',
            'page' => 'integer|min:1|max:100',
        ]);

        $provider = $request->provider;
        $q = trim($request->q);
        $page = (int) ($request->page ?? 1);

        return match ($provider) {
            'unsplash' => $this->searchUnsplash($q, $page),
            'pixabay' => $this->searchPixabay($q, $page),
            'freepik' => $this->searchFreepik($q, $page),
            'pexels' => $this->searchPexels($q, $page),
        };
    }

    public function imageImport(Request $request): JsonResponse {
        $request->validate([
            'url' => 'required|url|max:2048',
            'filename' => 'nullable|string|max:200',
            'source' => 'required|in:unsplash,pixabay,freepik,pexels',
            'download_location' => 'nullable|string|max:2048',
            'source_url' => 'nullable|url|max:2048',
        ]);

        $url = $request->input('url');
        $source = $request->input('source');
        $sourceUrl = $request->input('source_url');

        $allowedDomains = [
            'unsplash.com',
            'pixabay.com',
            'freepik.com',
            'ftcdn.net',
            'b2bpic.net',
            'freepikcompany.com',
            'images.pexels.com',
        ];
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $allowed = false;
        foreach ($allowedDomains as $domain) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                $allowed = true;
                break;
            }
        }
        if (! $allowed) {
            return response()->json(['message' => 'Download from this host is not permitted.'], 422);
        }

        if ($source === 'freepik' && $request->filled('download_location')) {
            $dlLoc = $request->input('download_location');
            if (str_starts_with($dlLoc, 'freepik:')) {
                $resourceId = substr($dlLoc, strlen('freepik:'));
                $freepikKey = $this->getFreepikApiKey();
                if ($resourceId && $freepikKey) {
                    try {
                        $dlRes = Http::withHeaders([
                            'Accept' => 'application/json',
                            'X-Freepik-API-Key' => $freepikKey,
                        ])->timeout(15)->get("https://api.freepik.com/v1/resources/{$resourceId}/download", ['image_size' => 'original']);
                        if ($dlRes->successful()) {
                            $resolvedUrl = $dlRes->json('url') ?? $dlRes->json('data.url') ?? null;
                            if ($resolvedUrl) {
                                $resolvedHost = strtolower(parse_url($resolvedUrl, PHP_URL_HOST) ?? '');
                                $resolvedAllowed = false;
                                foreach ($allowedDomains as $domain) {
                                    if ($resolvedHost === $domain || str_ends_with($resolvedHost, '.'.$domain)) {
                                        $resolvedAllowed = true;
                                        break;
                                    }
                                }
                                if ($resolvedAllowed) {
                                    $url = $resolvedUrl;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Fall through and use the original URL
                    }
                }
            }
        }

        if ($source === 'unsplash' && $request->filled('download_location')) {
            $key = $this->getUnsplashAccessKey();
            if ($key) {
                Http::withHeaders(['Authorization' => 'Client-ID '.$key])
                    ->get($request->download_location);
            }
        }

        try {
            $downloadHeaders = [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Cache-Control' => 'no-cache',
            ];
            if ($source === 'pixabay') {
                $downloadHeaders['Referer'] = 'https://pixabay.com/';
                $downloadHeaders['Sec-Fetch-Dest'] = 'image';
                $downloadHeaders['Sec-Fetch-Mode'] = 'no-cors';
                $downloadHeaders['Sec-Fetch-Site'] = 'same-site';
            }
            if ($source === 'freepik') {
                $freepikKey = $this->getFreepikApiKey();
                if ($freepikKey) {
                    $downloadHeaders['X-Freepik-API-Key'] = $freepikKey;
                }
                $downloadHeaders['Referer'] = 'https://www.freepik.com/';
            }
            if ($source === 'pexels') {
                $pexelsKey = $this->getPexelsApiKey();
                if ($pexelsKey) {
                    $downloadHeaders['Authorization'] = $pexelsKey;
                }
                $downloadHeaders['Referer'] = 'https://www.pexels.com/';
            }

            $response = null;
            $maxTries = 3;
            for ($try = 1; $try <= $maxTries; $try++) {
                $response = Http::withHeaders($downloadHeaders)->timeout(30)->get($url);
                if ($response->status() !== 429) {
                    break;
                }
                if ($try < $maxTries) {
                    sleep($try);
                }
            }

            if (! $response->successful()) {
                $status = $response->status();
                $hint = $status === 429 ? ' The provider is rate-limiting downloads — please wait a moment and try again.' : '';

                return response()->json(['message' => "Failed to download image from provider (HTTP {$status}).{$hint}"], 502);
            }
            $imageData = $response->body();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Network error while downloading image.'], 502);
        }

        $contentType = strtok($response->header('Content-Type') ?? 'image/jpeg', ';');
        $mimeType = trim($contentType) ?: 'image/jpeg';
        $extension = match ($mimeType) {
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        $hash = md5(uniqid('', true));
        $diskPath = 'media/'.$hash.'.'.$extension;

        Storage::disk('public')->put($diskPath, $imageData);

        $webpPath = null;
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            try {
                $webpDiskPath = 'media/'.$hash.'.webp';
                $webpFullPath = Storage::disk('public')->path($webpDiskPath);
                $manager = new ImageManager(new GdDriver);
                $webpImage = $manager->decode($imageData);
                if ($mimeType === 'image/png') {
                    $webpImage->setBackgroundColor(Color::transparent());
                }
                $webpImage->save($webpFullPath);
                $webpPath = $webpDiskPath;
            } catch (\Exception $e) {
                // WebP generation is best-effort; continue without it
            }
        }

        $originalName = $request->input('filename') ?: ($source.'-'.$hash.'.'.$extension);
        if (! str_contains($originalName, '.')) {
            $originalName .= '.'.$extension;
        }

        $media = Media::create([
            'user_id' => auth()->id(),
            'filename' => $originalName,
            'path' => $diskPath,
            'webp_path' => $webpPath,
            'thumbnail_path' => $this->generateThumbnail($diskPath, $hash, 'public', $mimeType),
            'disk' => 'public',
            'mime_type' => $mimeType,
            'size' => strlen($imageData),
            'alt_text' => null,
            'source_url' => $sourceUrl,
        ]);

        $this->flushMediaCache();

        return response()->json([
            'id' => $media->id,
            'url' => $media->url,
            'filename' => $media->filename,
            'formatted_size' => $media->formatted_size,
        ]);
    }

    private function generateThumbnail(string $diskPath, string $hash, string $disk, string $mimeType): ?string {
        $supportedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff', 'image/webp'];
        if (! in_array($mimeType, $supportedMimes)) {
            return null;
        }

        try {
            $thumbDiskPath = 'media/'.$hash.'_thumb.webp';
            $thumbFullPath = Storage::disk($disk)->path($thumbDiskPath);
            $sourceFullPath = Storage::disk($disk)->path($diskPath);

            $manager = new ImageManager(new GdDriver);
            $img = $manager->decode($sourceFullPath);

            if ($mimeType === 'image/png') {
                $img->setBackgroundColor(Color::transparent());
            }

            if ($img->width() > 600) {
                $img->scale(width: 600);
            }

            $img->save($thumbFullPath);

            return $thumbDiskPath;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function starredImagesForCurrentUser(): array {
        return StarredImportImage::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('starred_at')
            ->get()
            ->map(fn (StarredImportImage $image) => $image->toImporterArray())
            ->values()
            ->all();
    }

    private function validateStarredImagePayload(Request $request, bool $requireProvider): array {
        $rules = [
            'star_key' => 'nullable|string|size:64',
            'provider' => ($requireProvider ? 'required' : 'nullable').'|in:unsplash,pixabay,freepik,pexels',
            'id' => 'nullable|string|max:255',
            'alt' => 'nullable|string|max:2000',
            'author' => 'nullable|string|max:255',
            'thumb' => 'nullable|string|max:2048',
            'preview' => 'nullable|string|max:2048',
            'download_url' => 'nullable|string|max:2048',
            'download_location' => 'nullable|string|max:2048',
            'source_url' => 'nullable|string|max:2048',
            'raw' => 'nullable|array',
            'starred_at' => 'nullable|date',
        ];

        $payload = $request->validate($rules);

        $hasKeyMaterial = filled($payload['id'] ?? null)
            || filled($payload['download_url'] ?? null)
            || filled($payload['download_location'] ?? null)
            || filled($payload['star_key'] ?? null);

        if (! $hasKeyMaterial) {
            abort(response()->json([
                'message' => 'A starred image requires an ID or download URL.',
            ], 422));
        }

        if (! $requireProvider && blank($payload['provider'] ?? null) && blank($payload['star_key'] ?? null)) {
            abort(response()->json([
                'message' => 'A starred image provider is required.',
            ], 422));
        }

        return $payload;
    }

    private function mapStarredImageAttributes(array $payload): array {
        return [
            'user_id' => auth()->id(),
            'star_key' => $payload['star_key'] ?? $this->makeStarKey($payload),
            'provider' => $payload['provider'],
            'external_id' => $payload['id'] ?? null,
            'alt' => $payload['alt'] ?? null,
            'author' => $payload['author'] ?? null,
            'thumb_url' => $payload['thumb'] ?? null,
            'preview_url' => $payload['preview'] ?? null,
            'download_url' => $payload['download_url'] ?? null,
            'download_location' => $payload['download_location'] ?? null,
            'source_url' => $payload['source_url'] ?? null,
            'payload' => $payload['raw'] ?? null,
            'starred_at' => isset($payload['starred_at'])
                ? Carbon::parse($payload['starred_at'])
                : now(),
        ];
    }

    private function makeStarKey(array $payload): string {
        return hash('sha256', implode('|', [
            $payload['provider'] ?? '',
            $payload['download_location'] ?? '',
            $payload['download_url'] ?? '',
            $payload['id'] ?? '',
        ]));
    }

    private function searchUnsplash(string $q, int $page): JsonResponse {
        $key = $this->getUnsplashAccessKey();
        if (! $key) {
            return response()->json(['error' => 'no_key']);
        }

        try {
            $res = Http::withHeaders(['Authorization' => 'Client-ID '.$key])
                ->timeout(15)
                ->get('https://api.unsplash.com/search/photos', [
                    'query' => $q,
                    'page' => $page,
                    'per_page' => 20,
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'api_error', 'message' => 'Could not reach Unsplash.'], 502);
        }

        if (! $res->successful()) {
            return response()->json(['error' => 'api_error', 'message' => 'Unsplash search failed.'], 502);
        }

        $data = $res->json();
        $images = collect($data['results'] ?? [])->map(fn ($img) => [
            'id' => $img['id'],
            'thumb' => $img['urls']['small'] ?? '',
            'preview' => $img['urls']['regular'] ?? '',
            'download_url' => $img['urls']['full'] ?? '',
            'download_location' => $img['links']['download_location'] ?? null,
            'source_url' => $img['links']['html'] ?? '',
            'alt' => $img['alt_description'] ?? ($img['description'] ?? ''),
            'author' => $img['user']['name'] ?? '',
        ]);

        return response()->json([
            'images' => $images,
            'total' => $data['total'] ?? 0,
            'total_pages' => $data['total_pages'] ?? 1,
        ]);
    }

    private function searchPixabay(string $q, int $page): JsonResponse {
        $key = $this->getPixabayApiKey();
        if (! $key) {
            return response()->json(['error' => 'no_key']);
        }

        try {
            $res = Http::timeout(15)->get('https://pixabay.com/api/', [
                'key' => $key,
                'q' => $q,
                'image_type' => 'photo',
                'page' => $page,
                'per_page' => 20,
                'safesearch' => 'true',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'api_error', 'message' => 'Could not reach Pixabay.'], 502);
        }

        if (! $res->successful()) {
            return response()->json(['error' => 'api_error', 'message' => 'Pixabay search failed.'], 502);
        }

        $data = $res->json();
        $images = collect($data['hits'] ?? [])->map(function ($img) {
            $thumbUrl = $img['previewURL'] ?? '';
            $previewUrl = $img['webformatURL'] ?? $thumbUrl;
            $downloadUrl = $img['largeImageURL']
                ?? $img['fullHDURL']
                ?? $img['imageURL']
                ?? $img['webformatURL']
                ?? $thumbUrl;

            return [
                'id' => (string) $img['id'],
                'thumb' => $thumbUrl,
                'preview' => $previewUrl,
                'download_url' => $downloadUrl,
                'download_location' => null,
                'source_url' => $img['pageURL'] ?? '',
                'alt' => $img['tags'] ?? '',
                'author' => $img['user'] ?? '',
            ];
        });

        $total = $data['totalHits'] ?? count($images);

        return response()->json([
            'images' => $images,
            'total' => $total,
            'total_pages' => (int) ceil($total / 20),
        ]);
    }

    private function searchFreepik(string $q, int $page): JsonResponse {
        $key = $this->getFreepikApiKey();
        if (! $key) {
            return response()->json(['error' => 'no_key']);
        }

        try {
            $res = Http::withHeaders([
                'Accept-Language' => 'en-US',
                'Accept' => 'application/json',
                'X-Freepik-API-Key' => $key,
            ])->timeout(15)->get('https://api.freepik.com/v1/resources', [
                'locale' => 'en-US',
                'page' => $page,
                'limit' => 20,
                'term' => $q,
                'filters[content_type][photo]' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'api_error', 'message' => 'Could not reach Freepik.'], 502);
        }

        if (! $res->successful()) {
            return response()->json(['error' => 'api_error', 'message' => 'Freepik search failed.'], 502);
        }

        $data = $res->json();
        $images = collect($data['data'] ?? [])->map(fn ($img) => [
            'id' => (string) ($img['id'] ?? ''),
            'thumb' => $img['image']['source']['url'] ?? ($img['preview']['url'] ?? ''),
            'preview' => $img['image']['source']['url'] ?? ($img['preview']['url'] ?? ''),
            'download_url' => $img['image']['source']['url'] ?? '',
            'download_location' => 'freepik:'.($img['id'] ?? ''),
            'source_url' => $img['url'] ?? ($img['page_url'] ?? ($img['share_url'] ?? '')),
            'alt' => $img['title'] ?? '',
            'author' => '',
        ]);

        $meta = $data['meta'] ?? [];
        $total = $meta['total'] ?? count($images);

        return response()->json([
            'images' => $images,
            'total' => $total,
            'total_pages' => $meta['last_page'] ?? (int) ceil($total / 20),
        ]);
    }

    private function searchPexels(string $q, int $page): JsonResponse {
        $key = $this->getPexelsApiKey();
        if (! $key) {
            return response()->json(['error' => 'no_key']);
        }

        try {
            $res = Http::withHeaders([
                'Authorization' => $key,
            ])->timeout(15)->get('https://api.pexels.com/v1/search', [
                'query' => $q,
                'page' => $page,
                'per_page' => 20,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'api_error', 'message' => 'Could not reach Pexels.'], 502);
        }

        if (! $res->successful()) {
            return response()->json(['error' => 'api_error', 'message' => 'Pexels search failed.'], 502);
        }

        $data = $res->json();
        $images = collect($data['photos'] ?? [])->map(fn ($img) => [
            'id' => (string) $img['id'],
            'thumb' => $img['src']['medium'] ?? '',
            'preview' => $img['src']['large'] ?? '',
            'download_url' => strtok($img['src']['original'] ?? $img['src']['large2x'] ?? '', '?'),
            'download_location' => null,
            'source_url' => $img['url'] ?? '',
            'alt' => $img['alt'] ?? '',
            'author' => $img['photographer'] ?? '',
        ]);

        $total = $data['total_results'] ?? count($images);

        return response()->json([
            'images' => $images,
            'total' => $total,
            'total_pages' => (int) ceil($total / 20),
        ]);
    }
}
