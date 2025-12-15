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
}
