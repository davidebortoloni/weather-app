<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class WeatherController extends Controller
{
    public function index()
    {
        return Inertia::render('Home');
    }

    public function getWeatherData($city)
    {
        try {
            $coordinates = $this->getCoordinatesForCity($city);
            if (!$coordinates) {
                return null;
            }

            $response = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $coordinates['lat'],
                'longitude' => $coordinates['lon'],
                'current_weather' => true
            ]);

            $data = $response->json();

            $currentWeather = $data['current_weather'];

            $weatherCodeDescriptions = [
                0 => "Cielo sereno",
                1 => "Prevalentemente sereno",
                2 => "Parzialmente nuvoloso",
                3 => "Nuvoloso",
                45 => "Nebbia",
                51 => "Pioggerella leggera",
                61 => "Pioggia leggera",
                63 => "Pioggia",
                71 => "Neve leggera",
                73 => "Neve",
                80 => "Acquazzone leggero",
                95 => "Temporale",
            ];

            $currentWeather['description'] = $weatherCodeDescriptions[$currentWeather['weathercode']] ?? 'Unknown';

            $weather = new Weather();

            $weather->city = $city;
            $weather->temperature = $currentWeather['temperature'];
            $weather->description = $currentWeather['description'];
            $weather->code = $currentWeather['weathercode'];
            $weather->is_day = $currentWeather['is_day'];
            $weather->windspeed = $currentWeather['windspeed'];

            return $weather;

            $weather->save();

            return $weather;
        } catch (\Exception $e) {
            $latestWeather = Weather::where('city', $city)->latest()->first();
            return response()->json($latestWeather);
        }
    }

    private function getCoordinatesForCity($city)
    {
        $geocodeUrl = 'https://geocoding-api.open-meteo.com/v1/search';

        $response = Http::get($geocodeUrl, [
            'name' => $city
        ]);

        $data = $response->json();

        if (isset($data['results'][0]['latitude']) && isset($data['results'][0]['longitude'])) {
            return [
                'lat' => $data['results'][0]['latitude'],
                'lon' => $data['results'][0]['longitude']
            ];
        }

        return null;
    }
}
