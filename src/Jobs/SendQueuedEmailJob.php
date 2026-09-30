<?php

namespace HasinHayder\TyroDashboard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendQueuedEmailJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param string|array $to
     * @param string $subject
     * @param string $content
     * @param string $design
     * @param array $cc
     * @param array $bcc
     */
    public function __construct(
        public string|array $to,
        public string $subject,
        public string $content,
        public string $design = 'modern',
        public array $cc = [],
        public array $bcc = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void {
        $allowedDesigns = ['modern', 'corporate', 'newsletter', 'plain'];
        $designKey = in_array($this->design, $allowedDesigns, true) ? $this->design : 'modern';
        $view = "tyro-dashboard::emailer.templates.{$designKey}";

        $recipients = is_array($this->to) ? $this->to : array_filter(array_map('trim', explode(',', $this->to)));
        $ccRecipients = array_filter(array_map('trim', $this->cc));
        $bccRecipients = array_filter(array_map('trim', $this->bcc));

        Mail::send($view, [
            'subject' => $this->subject,
            'content' => $this->content,
            'appName' => config('tyro-dashboard.branding.app_name', config('app.name', 'Tyro Dashboard')),
        ], function ($message) use ($recipients, $ccRecipients, $bccRecipients) {
            $message->to($recipients)
                ->subject($this->subject);

            if (! empty($ccRecipients)) {
                $message->cc($ccRecipients);
            }

            if (! empty($bccRecipients)) {
                $message->bcc($bccRecipients);
            }
        });
    }
}
