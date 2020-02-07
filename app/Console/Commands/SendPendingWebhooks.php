<?php

namespace App\Console\Commands;

use App\Jobs\SendOrderPaidWebhook;
use App\Order;
use Illuminate\Console\Command;

class SendPendingWebhooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhooks:send {--F|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send any pending order webhooks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->periods = config('payment-system.webhooks.periods');
        $this->maxPeriods = count($this->periods);

        $pending = Order::query()
                        ->where('webhook_url', '!=', null)
                        ->where('webhooked_at', null)
                        ->where('webhook_attempts', '<', $this->maxPeriods)
                        ->cursor();

        foreach ($pending as $order) {
            $this->info("Checking order #$order->id");
            if ($this->forceSend() || $this->shouldWebhook($order)) {
                dispatch(new SendOrderPaidWebhook($order));
            }
        }
    }

    protected function shouldWebhook(Order $order)
    {
        $currentAttempt = $order->webhook_attempts;
        $expectedDelta = $this->periods[ $currentAttempt ];
        $lastAttempt = $order->webhook_attempted_at;

        $delta = now()->diffInSeconds($lastAttempt);

        $this->info("Current delta: $delta. Expected: $expectedDelta");

        return $delta >= $expectedDelta || !$lastAttempt;
    }

    protected function forceSend()
    {
        return $this->option('force');
    }
}
