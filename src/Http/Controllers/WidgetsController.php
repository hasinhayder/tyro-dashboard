<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WidgetsController extends BaseController
{
    public function widgets(): \Illuminate\Contracts\View\View
    {
        return view('tyro-dashboard::examples.widgets', $this->getViewData());
    }

    public function xkcd(?int $id = null): JsonResponse
    {
        $url = $id ? "https://xkcd.com/{$id}/info.0.json" : 'https://xkcd.com/info.0.json';

        try {
            $response = Http::timeout(6)->acceptJson()->get($url);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to reach XKCD',
            ], 502);
        }

        if (!$response->ok()) {
            return response()->json([
                'error' => 'XKCD returned an error',
                'status' => $response->status(),
            ], 502);
        }

        return response()->json($response->json());
    }

    public function stockQuote(Request $request, string $symbol): JsonResponse
    {
        $symbol = Str::lower(trim($symbol));

        if ($symbol === '') {
            return response()->json(['error' => 'Symbol is required'], 422);
        }

        // Stooq uses symbols like aapl.us, tsla.us
        // Docs: https://stooq.com/q/l/
        $url = 'https://stooq.com/q/l/';

        try {
            $response = Http::timeout(6)->get($url, [
                's' => $symbol,
                'f' => 'sd2t2ohlcv',
                'h' => 1,
                'e' => 'csv',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to reach quote provider'], 502);
        }

        if (!$response->ok()) {
            return response()->json(['error' => 'Quote provider returned an error'], 502);
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($response->body()));
        if (!$lines || count($lines) < 2) {
            return response()->json(['error' => 'Unexpected quote format'], 502);
        }

        $headers = str_getcsv($lines[0]);
        $row = str_getcsv($lines[1]);
        if (count($headers) !== count($row)) {
            return response()->json(['error' => 'Unexpected quote format'], 502);
        }

        $data = array_combine($headers, $row);

        // When Stooq doesn't know the symbol it returns "N/D" in numeric fields
        if (isset($data['Close']) && $data['Close'] === 'N/D') {
            return response()->json(['error' => 'Unknown symbol'], 404);
        }

        return response()->json([
            'symbol' => $data['Symbol'] ?? strtoupper($symbol),
            'date' => $data['Date'] ?? null,
            'time' => $data['Time'] ?? null,
            'open' => $data['Open'] ?? null,
            'high' => $data['High'] ?? null,
            'low' => $data['Low'] ?? null,
            'close' => $data['Close'] ?? null,
            'volume' => $data['Volume'] ?? null,
            'provider' => 'stooq',
        ]);
    }

    public function fxRates(string $base): JsonResponse
    {
        $base = strtoupper(trim($base));

        if ($base === '' || !preg_match('/^[A-Z]{3}$/', $base)) {
            return response()->json(['error' => 'Invalid base currency'], 422);
        }

        // Free, no-key endpoint; response shape includes: base_code, rates, time_last_update_unix
        $url = "https://open.er-api.com/v6/latest/{$base}";

        try {
            $response = Http::timeout(6)->acceptJson()->get($url);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to reach FX provider'], 502);
        }

        if (!$response->ok()) {
            return response()->json([
                'error' => 'FX provider returned an error',
                'status' => $response->status(),
            ], 502);
        }

        $json = $response->json();
        if (!is_array($json) || !isset($json['rates']) || !is_array($json['rates'])) {
            return response()->json(['error' => 'Unexpected FX format'], 502);
        }

        if (($json['result'] ?? null) !== 'success') {
            return response()->json([
                'error' => $json['error-type'] ?? 'FX provider error',
            ], 502);
        }

        return response()->json([
            'base' => $json['base_code'] ?? $base,
            'rates' => $json['rates'],
            'time_last_update_unix' => $json['time_last_update_unix'] ?? null,
            'provider' => 'open.er-api.com',
        ]);
    }

    public function flightStates(Request $request): JsonResponse
    {
        $icao24 = Str::lower(trim((string) $request->query('icao24', '')));
        $callsign = strtoupper(trim((string) $request->query('callsign', '')));

        $lamin = $request->query('lamin');
        $lamax = $request->query('lamax');
        $lomin = $request->query('lomin');
        $lomax = $request->query('lomax');

        $hasBbox = $lamin !== null || $lamax !== null || $lomin !== null || $lomax !== null;

        if ($icao24 !== '' && !preg_match('/^[0-9a-f]{6}$/', $icao24)) {
            return response()->json(['error' => 'Invalid ICAO24 (expected 6 hex characters)'], 422);
        }

        if ($hasBbox) {
            if ($lamin === null || $lamax === null || $lomin === null || $lomax === null) {
                return response()->json(['error' => 'Bounding box requires lamin, lamax, lomin, lomax'], 422);
            }

            $lamin = (float) $lamin;
            $lamax = (float) $lamax;
            $lomin = (float) $lomin;
            $lomax = (float) $lomax;

            if ($lamin < -90 || $lamin > 90 || $lamax < -90 || $lamax > 90 || $lomin < -180 || $lomin > 180 || $lomax < -180 || $lomax > 180) {
                return response()->json(['error' => 'Bounding box out of range'], 422);
            }

            if ($lamin >= $lamax || $lomin >= $lomax) {
                return response()->json(['error' => 'Bounding box min must be < max'], 422);
            }
        }

        if ($icao24 === '' && !$hasBbox) {
            return response()->json(['error' => 'Provide either icao24 or a bounding box (lamin/lamax/lomin/lomax)'], 422);
        }

        $query = [];
        if ($icao24 !== '') {
            $query['icao24'] = $icao24;
        }
        if ($hasBbox) {
            $query['lamin'] = $lamin;
            $query['lamax'] = $lamax;
            $query['lomin'] = $lomin;
            $query['lomax'] = $lomax;
        }

        $url = 'https://opensky-network.org/api/states/all';

        try {
            $response = Http::timeout(8)->acceptJson()->get($url, $query);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to reach flight provider'], 502);
        }

        if (!$response->ok()) {
            return response()->json([
                'error' => 'Flight provider returned an error',
                'status' => $response->status(),
            ], 502);
        }

        $json = $response->json();
        if (!is_array($json) || !array_key_exists('states', $json)) {
            return response()->json(['error' => 'Unexpected flight provider format'], 502);
        }

        $states = is_array($json['states'] ?? null) ? $json['states'] : [];

        $mapped = [];
        foreach ($states as $s) {
            if (!is_array($s) || count($s) < 17) {
                continue;
            }

            $state = [
                'icao24' => $s[0] ?? null,
                'callsign' => isset($s[1]) ? (trim((string) $s[1]) ?: null) : null,
                'origin_country' => $s[2] ?? null,
                'time_position' => $s[3] ?? null,
                'last_contact' => $s[4] ?? null,
                'longitude' => $s[5] ?? null,
                'latitude' => $s[6] ?? null,
                'baro_altitude' => $s[7] ?? null,
                'on_ground' => $s[8] ?? null,
                'velocity' => $s[9] ?? null,
                'true_track' => $s[10] ?? null,
                'vertical_rate' => $s[11] ?? null,
                'geo_altitude' => $s[13] ?? null,
                'squawk' => $s[14] ?? null,
                'spi' => $s[15] ?? null,
                'position_source' => $s[16] ?? null,
            ];

            if ($callsign !== '' && $state['callsign']) {
                $cs = strtoupper((string) $state['callsign']);
                if (!str_starts_with($cs, $callsign)) {
                    continue;
                }
            }

            $mapped[] = $state;
        }

        return response()->json([
            'time' => $json['time'] ?? null,
            'count' => count($mapped),
            'states' => $mapped,
            'provider' => 'opensky-network.org',
        ]);
    }
}
