<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use HasinHayder\TyroDashboard\Models\SmtpPreset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SmtpController extends BaseController {
    public function index() {
        $current = $this->gatherCurrentSmtp();
        $presets = SmtpPreset::orderBy('name')->get();

        return view('tyro-dashboard::smtp.index', $this->getViewData([
            'current' => $current,
            'presets' => $presets,
        ]));
    }

    public function update(Request $request): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        $validated = $request->validate([
            'MAIL_MAILER' => 'required|string|in:smtp,sendmail,log,array,mailgun,ses,postmark,resend,failover,roundrobin',
            'MAIL_HOST' => 'required_if:MAIL_MAILER,smtp|string|max:255',
            'MAIL_PORT' => 'required_if:MAIL_MAILER,smtp|nullable|integer|min:1|max:65535',
            'MAIL_SCHEME' => 'nullable|string|in:tls,ssl,',
            'MAIL_USERNAME' => 'nullable|string|max:500',
            'MAIL_PASSWORD' => 'nullable|string|max:1000',
            'MAIL_FROM_ADDRESS' => 'nullable|email|max:255',
            'MAIL_FROM_NAME' => 'nullable|string|max:255',
        ]);

        $this->writeEnv($validated);

        try { Artisan::call('config:clear'); } catch (\Throwable $e) {}

        return response()->json(['success' => true, 'message' => 'SMTP settings saved.']);
    }

    public function storePreset(Request $request): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tyro_smtp_presets,name',
            'mailer' => 'required|string|in:smtp,sendmail,log,array,mailgun,ses,postmark,resend',
            'host' => 'required_if:mailer,smtp|string|max:255',
            'port' => 'required_if:mailer,smtp|nullable|integer|min:1|max:65535',
            'encryption' => 'nullable|string|in:tls,ssl',
            'username' => 'nullable|string|max:500',
            'password' => 'nullable|string|max:1000',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = encrypt($validated['password']);
        }

        $preset = SmtpPreset::create($validated);

        return response()->json(['success' => true, 'message' => 'Preset created.', 'preset' => $preset]);
    }

    public function updatePreset(Request $request, int $id): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        $preset = SmtpPreset::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tyro_smtp_presets,name,'.$id,
            'mailer' => 'required|string|in:smtp,sendmail,log,array,mailgun,ses,postmark,resend',
            'host' => 'required_if:mailer,smtp|string|max:255',
            'port' => 'required_if:mailer,smtp|nullable|integer|min:1|max:65535',
            'encryption' => 'nullable|string|in:tls,ssl',
            'username' => 'nullable|string|max:500',
            'password' => 'nullable|string|max:1000',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
        ]);

        if (array_key_exists('password', $validated)) {
            if ($validated['password'] === '' || $validated['password'] === null) {
                unset($validated['password']);
            } else {
                $validated['password'] = encrypt($validated['password']);
            }
        }

        $preset->update($validated);

        return response()->json(['success' => true, 'message' => 'Preset updated.', 'preset' => $preset->fresh()]);
    }

    public function destroyPreset(Request $request, int $id): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        SmtpPreset::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Preset deleted.']);
    }

    public function applyPreset(Request $request, int $id): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        $preset = SmtpPreset::findOrFail($id);

        $payload = [
            'MAIL_MAILER' => $preset->mailer,
            'MAIL_HOST' => $preset->host,
            'MAIL_PORT' => (string) $preset->port,
            'MAIL_SCHEME' => $preset->encryption ?? '',
            'MAIL_USERNAME' => $preset->username ?? '',
            'MAIL_PASSWORD' => $preset->password ? decrypt($preset->password) : '',
            'MAIL_FROM_ADDRESS' => $preset->from_address ?? '',
            'MAIL_FROM_NAME' => $preset->from_name ?? '',
        ];

        $filtered = [];
        foreach ($payload as $k => $v) {
            if ($v !== '' && $v !== null) {
                $filtered[$k] = $v;
            } elseif (in_array($k, ['MAIL_SCHEME', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME'], true)) {
                $filtered[$k] = null;
            } else {
                $filtered[$k] = $v;
            }
        }

        $this->writeEnv($filtered);

        try { Artisan::call('config:clear'); } catch (\Throwable $e) {}

        return response()->json(['success' => true, 'message' => 'Preset "'.$preset->name.'" applied.']);
    }

    public function sendTest(Request $request): JsonResponse {
        abort_unless($request->ajax() && $request->wantsJson(), 403);

        $validated = $request->validate([
            'to' => 'required|email|max:255',
        ]);

        try {
            Mail::raw('This is a test email from Tyro Dashboard SMTP settings. If you received this, your mail configuration is working.', function ($message) use ($validated) {
                $message->to($validated['to'])->subject('Tyro Dashboard — SMTP Test');
            });
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send: '.$e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Test email sent to '.$validated['to'].'.']);
    }

    public function clearConfigCache(): JsonResponse {
        try {
            Artisan::call('config:clear');
            return response()->json(['success' => true, 'message' => 'Config cache cleared.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Config clear skipped.'], 200);
        }
    }

    protected function gatherCurrentSmtp(): array {
        return [
            'MAIL_MAILER' => config('mail.default', env('MAIL_MAILER', 'log')),
            'MAIL_HOST' => env('MAIL_HOST', config('mail.mailers.smtp.host', '')),
            'MAIL_PORT' => env('MAIL_PORT', config('mail.mailers.smtp.port', 587)),
            'MAIL_SCHEME' => env('MAIL_SCHEME', config('mail.mailers.smtp.scheme', '')),
            'MAIL_USERNAME' => env('MAIL_USERNAME', config('mail.mailers.smtp.username', '')),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD', config('mail.mailers.smtp.password', '')),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS', config('mail.from.address', '')),
            'MAIL_FROM_NAME' => env('MAIL_FROM_NAME', config('mail.from.name', '')),
        ];
    }

    protected function writeEnv(array $values): void {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            abort(response()->json(['success' => false, 'message' => '.env file not found.'], 500));
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            if ($value === null || $value === '') {
                $content = $this->removeEnvLine($content, $key);
                continue;
            }

            $sanitized = str_replace('"', "'", (string) $value);
            $serialized = "\"{$sanitized}\"";

            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$serialized}", $content);
            } else {
                $content = rtrim($content)."\n{$key}={$serialized}\n";
            }
        }

        file_put_contents($envPath, $content);
    }

    protected function removeEnvLine(string $content, string $key): string {
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*\n?/m", '', $content);
            return rtrim($content)."\n";
        }
        return $content;
    }
}
