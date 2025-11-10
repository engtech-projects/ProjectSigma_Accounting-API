<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\ApiServices\InventorySecretkeyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiInventorySyncJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    protected string $method;

    /**
     * Create a new job instance.
     */
    public function __construct(string $method)
    {
        $this->method = $method;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $service = app(InventorySecretkeyService::class);
            if (!method_exists($service, $this->method)) {
                Log::warning("ApiInventorySyncJob: Method {$this->method} does not exist.");
                return;
            }
            DB::transaction(function () use ($service) {
                $service->{$this->method}();
            });
            Log::info("ApiInventorySyncJob successfully synced with [{$this->method}]");
        } catch (\Throwable $e) {
            Log::error("ApiInventorySyncJob failed [{$this->method}]: " . $e->getMessage());
            throw $e;
        }
    }
}
