<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Jobs\SendQueuedEmailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailerController extends BaseController {
    /**
     * Supported preset designs metadata
     */
    public static function getPresets(): array {
        return [
            'modern' => [
                'id' => 'modern',
                'name' => 'Modern Minimal',
                'badge' => 'Clean & Crisp',
                'description' => 'Clean layout with modern sans-serif typography, soft rounded card containers, and subtle accents.',
                'preview_bg' => '#f8fafc',
                'preview_accent' => '#000000',
            ],
            'corporate' => [
                'id' => 'corporate',
                'name' => 'Corporate Announcement',
                'badge' => 'Executive & Polished',
                'description' => 'Distinctive top brand header bar, structured divider, formal typography, and professional footer note.',
                'preview_bg' => '#f1f5f9',
                'preview_accent' => '#0f172a',
            ],
            'newsletter' => [
                'id' => 'newsletter',
                'name' => 'Marketing & Newsletter',
                'badge' => 'Bold & Modern',
                'description' => 'Hero-styled email container with clean dark header and card borders, optimized for announcements, updates, and CTA button blocks.',
                'preview_bg' => '#f4f4f5',
                'preview_accent' => '#18181b',
            ],
            'plain' => [
                'id' => 'plain',
                'name' => 'Plain & Direct',
                'badge' => 'Letterhead Style',
                'description' => 'Lightweight letterhead design without heavy cards, focusing 100% on the content with maximum deliverability aesthetic.',
                'preview_bg' => '#ffffff',
                'preview_accent' => '#475569',
            ],
        ];
    }

    /**
     * Display the email composer page.
     */
    public function index() {
        $presets = self::getPresets();

        return view('tyro-dashboard::emailer.index', $this->getViewData([
            'presets' => $presets,
            'defaultDesign' => 'modern',
        ]));
    }

    /**
     * Dispatch the email to the background queue.
     */
    public function send(Request $request): JsonResponse {
        $validated = $request->validate([
            'to' => 'required|string|max:1000',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'design' => 'nullable|string|in:modern,corporate,newsletter,plain',
            'cc' => 'nullable|string|max:1000',
            'bcc' => 'nullable|string|max:1000',
        ]);

        $parseEmails = function (?string $raw): array {
            if (empty($raw)) {
                return [];
            }
            $parts = preg_split('/[,\s]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);
            return array_filter($parts, fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL));
        };

        $toEmails = $parseEmails($validated['to']);
        if (empty($toEmails)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide at least one valid recipient email address.',
            ], 422);
        }

        $ccEmails = $parseEmails($validated['cc'] ?? null);
        $bccEmails = $parseEmails($validated['bcc'] ?? null);
        $design = $validated['design'] ?? 'modern';

        // Dispatch background job to the queue
        SendQueuedEmailJob::dispatch(
            $toEmails,
            $validated['subject'],
            $validated['body'],
            $design,
            $ccEmails,
            $bccEmails
        );

        return response()->json([
            'success' => true,
            'message' => 'Email queued successfully. The background worker will dispatch it.',
            'queued_to' => $toEmails,
            'count' => count($toEmails),
        ]);
    }

    /**
     * Render a live preview of the email with the chosen design preset.
     */
    public function preview(Request $request) {
        $design = $request->input('design', 'modern');
        $allowed = ['modern', 'corporate', 'newsletter', 'plain'];
        if (! in_array($design, $allowed, true)) {
            $design = 'modern';
        }

        $subject = $request->input('subject', 'Preview: Your Subject Line Here');
        $content = $request->input('body', '<p>Hello!</p><p>This is a live preview of your email template. You can write rich text with <strong>bold</strong>, <em>italic</em>, bullet lists, links, and more.</p><p>Best regards,<br>The Team</p>');

        return view("tyro-dashboard::emailer.templates.{$design}", [
            'subject' => $subject,
            'content' => $content,
            'appName' => config('tyro-dashboard.branding.app_name', config('app.name', 'Tyro Dashboard')),
        ]);
    }
}
