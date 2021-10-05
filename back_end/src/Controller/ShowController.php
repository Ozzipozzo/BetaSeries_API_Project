<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowController extends AbstractController
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = "85cbe795b1fe";
    }

    /**
     * @Route("/api/show/all", name="show_all", methods={"POST"})
     */
    public function getAllShow(Request $request):Response
    {
        $toGet;
        $parameters = json_decode($request->getContent(), true);
        $start = $parameters['start']; 
        if($start == 0 || $start == null) {
            $toGet = "https://api.betaseries.com/shows/list?client_id=" . $this->apiKey . "&order=popularity&limit=20";
        } else {
            $toGet = "https://api.betaseries.com/shows/list?client_id=" . $this->apiKey . "&order=popularity&limit=20&start=" . $start;
        }
        
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content['shows']);
    }

    /**
     * @Route("/api/show/one", name="show_one", methods={"POST"})
     */
    public function getOneShow(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $toGet = "https://api.betaseries.com/shows/display?client_id=" . $this->apiKey . "&order=popularity&limit=20&id=" . $id;
        
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/get/episodes", name="api_get_episodes")
     */
    public function getEpisodes(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $season = $parameters['season'];
        $token = $parameters['token'];

        if($token != "" && $token  != null) {
            $toGet = "https://api.betaseries.com/shows/episodes?client_id=" . $this->apiKey . "&id=" . $id . "&season=" . $season . "&token=" . $token;
        } else {
            $toGet = "https://api.betaseries.com/shows/episodes?client_id=" . $this->apiKey . "&id=" . $id . "&season=" . $season;
        }
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();

        $content['seasonImage'] = "http://api.betaseries.com/pictures/seasons?client_id=" . $this->apiKey . "&show_id=" . $id . "&season=" . $season;

        return new JsonResponse($content);
    }

    /**
     * @Route("/api/get/episode", name="api_get_episode")
     */
    public function getEpisode(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $authorization = "Bearer " . $parameters['token'];
        
        $toGet = "https://api.betaseries.com/episodes/display?client_id=" . $this->apiKey . "&id=" . $id ;
        $response = $this->client->request(
            'GET',
            $toGet,
            [
                'headers' => [
                    "Authorization" => $authorization
                ]
            ]
        );
        

        $content = $response->getContent();
        $content = $response->toArray();

        $toGet = "https://api.betaseries.com/comments/comments?client_id=" . $this->apiKey . "&type=episode&id=" . $id ;
        $response = $this->client->request(
            'GET',
            $toGet,
            [
                'headers' => [
                    "Authorization" => $authorization
                ]
            ]
        );

        $contentComments = $response->getContent();
        $contentComments = $response->toArray();
        $content['commentList'] = $contentComments;
        
        $content['episodeImage'] = "https://api.betaseries.com/pictures/episodes?client_id=" . $this->apiKey . "&id=" . $id;

        return new JsonResponse($content);
    }

    /**
     * @Route("/api/get/genres", name="api_get_genres")
     */
    public function getGenres(Request $request):Response
    {
        $toGet = "https://api.betaseries.com/shows/genres?client_id=" . $this->apiKey ;
        $response = $this->client->request(
            'GET',
            $toGet,
        );

        $content = $response->getContent();
        $content = $response->toArray();

        return new JsonResponse($content);
    }

    /**
     * @Route("/api/show/byGenre", name="show_byGenre", methods={"POST"})
     */
    public function getShowByGenre(Request $request):Response
    {
        $toGet;
        $parameters = json_decode($request->getContent(), true);
        $genre = $parameters['genre'];
        $start = $parameters['start']; 
        if($start == 0 || $start == null) {
            $toGet = "https://api.betaseries.com/search/shows?client_id=" . $this->apiKey . "&genres=" . $genre . "&limit=20";
        } else {
            $toGet = "https://api.betaseries.com/search/shows?client_id=" . $this->apiKey . "&genres=" . $genre . "&limit=20&offset=" . $start;
        }
        
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content['shows']);
    }

    /**
     * @Route("/api/show/byName", name="show_byName", methods={"POST"})
     */
    public function getShowByName(Request $request):Response
    {
        $toGet;
        $parameters = json_decode($request->getContent(), true);
        $name = $parameters['name'];
        $toGet = "https://api.betaseries.com/search/shows?client_id=" . $this->apiKey . "&text=" . $name . "&limit=20";
        
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }
}
