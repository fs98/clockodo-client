<?php

namespace Fs98\ClockodoClient\Clocks;

use Fs98\ClockodoClient\Services\ClockodoApiService;
use Illuminate\Support\Facades\Http;

class Clocks
{
    protected $clockodoApiService;

    public function __construct(ClockodoApiService $clockodoApiService)
    {
        $this->clockodoApiService = $clockodoApiService;
    }

    /**
     * Get currently running entries
     */
    public function currentlyRunning(): array
    {
        return Http::withHeaders($this->clockodoHeaders)
            ->get(
                $this->clockodoApiUrl . '/v2/clock'
            )->json();
    }

    /**
     * Start the clock
     *
     * @param  int  $customersId ID of the corresponding customer.
     * @param  int  $servicesId ID of the corresponding service.
     * @param  array  $optionalParameters Additional optional parameters:
     *        - billable: (int) Is the entry billable? If omitted, the default value of the customer or the project is used.
     *           0: not billable
     *           1: billable
     *           2: already billed
     *        - projects_id (int) ID of the corresponding project.
     *        - text (null|string) Description text. Only in list function with enhanced list mode enabled.
     *        - users_id (int) ID of the corresponding co-worker.
     */
    public function start(int $customersId, int $servicesId, $optionalParameters): array
    {
        return Http::withHeaders($this->clockodoHeaders)
            ->post(
                $this->clockodoApiUrl . '/v2/clock',
                [
                    'customers_id' => $customersId,
                    'services_id' => $servicesId,
                    ...$optionalParameters,
                ]
            )->json();
    }

    /**
     * Stop the clock
     *
     * @param  int  $clockId ID of the entry.
     * @param  int  $usersId (optional) ID of the corresponding co-worker.
     */
    public function stop(int $clockId, $usersId = null): array
    {
        return Http::withHeaders($this->clockodoHeaders)
            ->delete(
                $this->clockodoApiUrl . '/v2/clock/' . $clockId,
                [
                    'users_id' => $usersId,
                ]
            )->json();
    }
}
