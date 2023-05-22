<?php

use Fs98\ClockodoClient\Clocks\Clocks;
use Fs98\ClockodoClient\Services\ClockodoApiService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses()->group('unit', 'clocks');

$clockodoHeaders = [
    'X-Clockodo-External-Application' => null,
    'X-ClockodoApiUser' => null,
    'X-ClockodoApiKey' => null,
];
$clockodoApiUrl = 'https://my.clockodo.com/api/';

it('sends a GET request to get currently running entries', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $clocks = new Clocks($clockodoApiService);

    // Act
    $clocks->currentlyRunning();

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders) {
        $mockRequestUrl = $clockodoApiUrl.'v2/clock';

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'GET' &&
            $request->headers($clockodoHeaders);
    });
});

it('sends a POST request to start a clock', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockCustomersId = 1702926;
    $mockServicesId = 1702927;
    $mockOptionalParameters = [
        'text' => '...',
        'users_id' => 1702928,
    ];

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $clocks = new Clocks($clockodoApiService);

    // Act
    $clocks->start($mockCustomersId, $mockServicesId, $mockOptionalParameters);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockCustomersId, $mockServicesId, $mockOptionalParameters) {
        $mockData = [
            'customers_id' => $mockCustomersId,
            'services_id' => $mockServicesId,
            ...$mockOptionalParameters,
        ];
        $mockRequestUrl = $clockodoApiUrl.'v2/clock';

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'POST' &&
            $request->headers($clockodoHeaders) &&
            $request->data() === $mockData;
    });
});

it('sends a DELETE request to stop the clock', function () use ($clockodoHeaders, $clockodoApiUrl) {
    // Arrange
    $mockClockId = 1702926;
    $mockUsersId = 1702927;

    Http::fake([
        '*' => Http::response([]),
    ]);

    $clockodoApiService = new ClockodoApiService($clockodoHeaders, $clockodoApiUrl);
    $clocks = new Clocks($clockodoApiService);

    // Act
    $clocks->stop($mockClockId, $mockUsersId);

    // Assert
    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($clockodoApiUrl, $clockodoHeaders, $mockUsersId, $mockClockId) {
        $mockData = [
            'users_id' => $mockUsersId,
        ];
        $mockRequestUrl = $clockodoApiUrl.'v2/clock/'.$mockClockId;

        return $request->url() == $mockRequestUrl &&
            $request->method() === 'DELETE' &&
            $request->headers($clockodoHeaders) &&
            $request->data() === $mockData;
    });
});
