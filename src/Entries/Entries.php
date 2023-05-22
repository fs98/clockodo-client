<?php

namespace Fs98\ClockodoClient\Entries;

use Fs98\ClockodoClient\Services\ClockodoApiService;

class Entries
{
    protected $clockodoApiService;

    public function __construct(ClockodoApiService $clockodoApiService)
    {
        $this->clockodoApiService = $clockodoApiService;
    }

    /**
     * List entries.
     *
     * @param  string  $timeSince In format ISO 8601 UTC, e.g. "2021-06-30T12:34:56Z".
     * @param  string  $timeUntil In format ISO 8601 UTC, e.g. "2021-06-30T12:34:56Z".
     * @param  array  $optionalParameters Additional optional parameters:
     *        - filter[users_id] (integer|null) ID of the corresponding coworker.
     *        - filter[customers_id] (integer|null) ID of the corresponding customer.
     *        - filter[projects_id] (integer|null) ID of the corresponding project.
     *        - filter[services_id] (integer|null) ID of the corresponding service.
     *        - filter[lumpsum_services_id] (integer|null) ID of the corresponding lumpsum coworker.
     *        - filter[billable] (integer|null) Is the entry billable?
     *          0: not billable, 1: billable, 2: already billed
     *        - filter[text] / filter[texts_id] (string|integer|null)
     *        - filter[budget_type] (string|null) strict, strict-completed, strict-incomplete, soft, soft-completed, soft-incomplete, without, without-strict
     *        - calc_also_revenues_for_projects_with_hard_budget (boolean|null) By default, revenues for projects with hard budgets will no be calculated. If you activate this option, the sum of all revenues to this project can be more than the project budget
     *        - enhanced_list (<boolean></boolean>|null) Enables the output of additional information
     *        - page (integer|null) Because the result can have many entries, the use of page-by-page output is enabled for this request.
     */
    public function get(string $timeSince, string $timeUntil, array $optionalParameters = []): array
    {
        $data = [
            'time_since' => $timeSince,
            'time_until' => $timeUntil,
            ...$optionalParameters,
        ];

        return $this->clockodoApiService->performGetRequest('v2/entries', $data);
    }

    /**
     * Search for entries.
     *
     * @param  int  $entryId ID of the corresponding entry.
     */
    public function getOne(int $entryId): array
    {
        return $this->clockodoApiService->performGetRequest('v2/entries/'.$entryId);
    }

    /**
     * Add entry
     *
     * @param  int  $customersId ID of the corresponding customer.
     * @param  int  $servicesId ID of the corresponding service.
     * @param  int  $billable Is the entry billable?
     *        0: not billable, 1: billable, 2: already billed
     * @param  string  $time_since Starting time in format ISO 8601 UTC, e.g. "2021-06-30T12:34:56Z".
     * @param  string|null  $time_until End time, NULL if entry is running.
     * @param  array  $optionalParameters Additional optional parameters:
     *        - users_id: (integer|null) ID of the corresponding co-worker.
     *        - duration (integer|null) Duration of the entry in seconds.
     *        - hourly_rate (float) Hourly rate.
     *        - projects_id (boolean) ID of the corresponding project.
     *        - text (string|null) Description text.
     */
    public function create(int $customersId, int $servicesId, int $billable, string $timeSince, string $timeUntil = null, array $optionalParameters = []): array
    {
        $data = [
            'customers_id' => $customersId,
            'services_id' => $servicesId,
            'billable' => $billable,
            'time_since' => $timeSince,
            'time_until' => $timeUntil,
            ...$optionalParameters,
        ];

        return $this->clockodoApiService->performPostRequest('v2/entries', $data);
    }

    /**
     * Edit entry
     *
     * @param  int  $id ID of the entry.
     * @param  array  $optionalParameters Additional optional parameters:
     *        - customers_id (integer) ID of the corresponding customer.
     *        - projects_id (integer|null) ID of the corresponding project.
     *        - services_id (integer) ID of the corresponding service.
     *        - lumpsum_services_id (integer) ID of the corresponding lumpsum service.
     *        - users_id (integer) ID of the corresponding co-worker.
     *        - billable (integer) Is the entry billable?
     *          0: not billable, 1: billable, 2: already billed
     *        - text (string|null) Description text.
     *        - duration (integer) Duration of the entry in seconds.
     *        - lumpsum (float) Value of the lump sum entry.
     *        - lumpsum_services_amount (float) Amount of the lump sum service.
     *        - hourly_rate (float) Hourly rate.
     *        - time_since (string) In format ISO 8601 UTC, e.g. "2021-06-30T12:34:56Z".
     *        - time_until (string) In format ISO 8601 UTC, e.g. "2021-06-30T12:34:56Z".
     */
    public function edit(int $id, array $optionalParameters = []): array
    {
        return $this->clockodoApiService->performPutRequest('v2/entries/'.$id, $optionalParameters);
    }

    /**
     * Delete entry
     *
     * @param  int  $id ID of the entry.
     */
    public function delete(int $id): array
    {
        return $this->clockodoApiService->performDeleteRequest('v2/entries/'.$id);
    }
}
