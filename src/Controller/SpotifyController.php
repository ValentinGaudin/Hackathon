<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{

    private const TOKEN = "BQD3liYn0B6x8UV4dnmgmBZOhF5lmZC3lt9S0UjXiRGD4ALZzGesvO-1noQYJ7QKb4qEb3kZ3ZtuNY4mklkcfcpmG4S4g1aMuIvvq73kAjekwSe7HaluS1J0WcGyno_6QItNTKKkaZyoPjCnQjdUUhdGgnHFSls";

    public function show()
    {

        $token = self::TOKEN;
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $playlists = $results['playlists']['items'];
            $id = $playlists['1']['id'];
            return $this->twig->render('Spotify/index.html.twig', ['id' => $id]);
        }
        return $response->getStatusCode();
    }

    public function change($bpm)
    {
        $token = self::TOKEN;
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $playlists = $results['playlists']['items'];

            $filteredPlaylists = $this->getFilteredPlaylists($playlists, $bpm);
            $randId = rand(0, count($filteredPlaylists) - 1);

            $id = $filteredPlaylists[$randId]['id'];
            return $this->twig->render('Spotify/index.html.twig', ['id' => $id]);
        }
        return $response->getStatusCode();
    }

    private function getFilteredPlaylists($playlists, $key)
    {
        $filteredPlaylists = [];

        foreach ($playlists as $playlist) {
            if (str_contains($playlist['name'], $key)) {
                $filteredPlaylists[] = $playlist;
            };
        }

        return $filteredPlaylists;
    }
}
