<?php

use Fs98\ClockodoClient\Entries\Entries;
use Fs98\ClockodoClient\Services\ClockodoApiService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses()->group('unit', 'entries');

$clockodoHeaders = [
    'X-Clockodo-External-Application' => null,
    'X-ClockodoApiUser' => null,
    'X-ClockodoApiKey' => null,
];
$clockodoApiUrl = 'https://my.clockodo.com/api/';

it('sends a GET request to get entries for a specific time range with optional parameters', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockTimeSince = '2021-06-30T12:34:56Z';
    $mockTimeUntil = '2021-06-30T12:34:56Z';
    $mockOptionalParameters = ['filter[users_id]' => 124354];

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $entries = new Entries($clockodoApiService);

    // Act
    $entries->get($mockTimeSince, $mockTimeUntil, $mockOptionalParameters);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockTimeSince, $mockTimeUntil, $mockOptionalParameters) {
        $mockData = [
            'time_since' => $mockTimeSince,
            'time_until' => $mockTimeUntil,
            ...$mockOptionalParameters,
        ];
        $mockRequestUrl = $clockodoApiUrl.'v2/entries?'.http_build_query($mockData);

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'GET' &&
            $request->headers($clockodoHeaders);
    });
});

it('sends a GET request to get a selected entry by id', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockEntryId = 1702926;

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $entries = new Entries($clockodoApiService);

    // Act
    $entries->getOne($mockEntryId);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockEntryId) {
        $mockRequestUrl = $clockodoApiUrl.'v2/entries/'.$mockEntryId;

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'GET' &&
            $request->headers($clockodoHeaders);
    });
});

it('sends a POST request to create a new entry', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockCustomersId = 123455;
    $mockServicesId = 123455;
    $mockBillable = 0;
    $mockTimeSince = '2021-06-30T12:34:56Z';
    $mockTimeUntil = null;
    $mockOptionalParameters = ['text' => '...'];

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $entries = new Entries($clockodoApiService);

    // Act
    $entries->create($mockCustomersId, $mockServicesId, $mockBillable, $mockTimeSince, $mockTimeUntil, $mockOptionalParameters);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockCustomersId, $mockServicesId, $mockBillable, $mockTimeSince, $mockTimeUntil, $mockOptionalParameters) {
        $mockData = [
            'customers_id' => $mockCustomersId,
            'services_id' => $mockServicesId,
            'billable' => $mockBillable,
            'time_since' => $mockTimeSince,
            'time_until' => $mockTimeUntil,
            ...$mockOptionalParameters,
        ];
        $mockRequestUrl = $clockodoApiUrl.'v2/entries';

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'POST' &&
            $request->headers($clockodoHeaders) &&
            $request->data() === $mockData;
    });
});

it('sends a PUT request to edit existing entry by id', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockEntryId = 1702926;
    $mockOptionalParameters = ['text' => '...'];

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $entries = new Entries($clockodoApiService);

    // Act
    $entries->edit($mockEntryId, $mockOptionalParameters);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockEntryId, $mockOptionalParameters) {
        $mockRequestUrl = $clockodoApiUrl.'v2/entries/'.$mockEntryId;

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'PUT' &&
            $request->headers($clockodoHeaders) &&
            $request->data() === $mockOptionalParameters;
    });
});

it('sends a DELETE request to delete entry by id', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockEntryId = 1702926;

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $entries = new Entries($clockodoApiService);

    // Act
    $entries->delete($mockEntryId);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockEntryId) {
        $mockRequestUrl = $clockodoApiUrl.'v2/entries/'.$mockEntryId;

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'DELETE' &&
            $request->headers($clockodoHeaders);
    });
});
