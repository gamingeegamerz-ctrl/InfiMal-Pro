<?php

namespace App\Services;

use App\Jobs\SendAdminEmailJob;

class AdminEmailDispatcher
{
    public function dispatch(array $payload): void
    {
        SendAdminEmailJob::dispatch($payload)->onQueue('admin_email_jobs');
    }
}
