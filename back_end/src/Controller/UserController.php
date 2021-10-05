<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// api key 85cbe795b1fe
        
class UserController extends AbstractController
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = "85cbe795b1fe";
    }

    /**
     * @Route("/api/users/register", name="user_register", methods={"POST"})
     */
    public function registerAction(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $login = $parameters['login'];
        $password = md5($parameters['password']);
        $email = $parameters['email'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/members/signup?client_id=" . $apiKey . "&login=" . $login . "&password=" . $password . "&email=" . $email;
        $response = $this->client->request(
            'POST',
            $toGet
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/login", name="user_login", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        
        $currentUser = [];
        $parameters = json_decode($request->getContent(), true);
        $login = $parameters['login'];
        $password = md5($parameters['password']);
        $apiKey = $this->apiKey;

        $getToken = "https://api.betaseries.com/members/auth?login=" . $login . "&password=" . $password . "&client_id=" . $apiKey;
        
        $response = $this->client->request(
            'POST',
            $getToken,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        $currentUser['token'] = $content['token'];
        $currentUser['id'] = $content['user']['id'];

        $getInfos = "https://api.betaseries.com/members/infos?id=" . $currentUser['id'] . "&client_id=" . $apiKey;
        $response = $this->client->request(
            'GET',
            $getInfos,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        $content['member']['token'] = $currentUser['token'];

        // Récupère les series de l'utilisateur
        $authorization = "Bearer " . $currentUser['token'];

        $toGet = "https://api.betaseries.com/shows/member?client_id=" . $apiKey . "&id=" . $currentUser['id'];
        $response = $this->client->request(
            'GET',
            $toGet,
            [
                'headers' => [
                    "Authorization" => $authorization
                ]
            ]
        );
        $showList = $response->getContent();
        $showList = $response->toArray();


        for ($i=0; $i < count($showList['shows']); $i++) { 
            $showList['shows'][$i]['archive_url'] = "https://api.betaseries.com/shows/archive?client_id=" . $apiKey . "&id=" . $showList['shows'][$i]['id'] . "&token=" . $currentUser['token'];
        }
        
        $content['member']['showList'] = $showList['shows'];
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/show", name="user_show", methods={"POST"})
     */
    public function getMemberShow(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;
        $authorization = "Bearer " . $token;

        $toGet = "https://api.betaseries.com/shows/member?client_id=" . $apiKey . "&id=" . $id;
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
        for ($i=0; $i < count($content['shows']); $i++) { 
            $content['shows'][$i]['archive_url'] = "https://api.betaseries.com/shows/archive?client_id=" . $apiKey . "&id=" . $content['shows'][$i]['id'] . "&token=" . $token;
        }
        return new JsonResponse($content['shows']);
    }

    /**
     * @Route("/api/users/add/show", name="user_add_show", methods={"POST"})
     */
    public function addUserShow(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id']; // id de la série
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toAdd = "https://api.betaseries.com/shows/show?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'POST',
            $toAdd,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse("OK");
    }

    /**
     * @Route("/api/users/remove/show", name="user_remove_show", methods={"POST"})
     */
    public function removeUserShow(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id']; // id de la série
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toAdd = "https://api.betaseries.com/shows/archive?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'POST',
            $toAdd,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/unarchived/show", name="user_unarchived_show", methods={"POST"})
     */
    public function unarchivedUserShow(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id']; // id de la série
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toAdd = "https://api.betaseries.com/shows/archive?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'DELETE',
            $toAdd,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/friends", name="user_friends", methods={"POST"})
     */
    public function getMemberFriends(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/friends/list?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'GET',
            $toGet,
        );

        $content = $response->getContent();
        $content = $response->toArray();

        $toGetBlocked = "https://api.betaseries.com/friends/list?client_id=" . $apiKey . "&token=" . $token . "&blocked=true";
        $responseBlocked = $this->client->request(
            'GET',
            $toGetBlocked,
        );
        // blocked
        $contentBlocked = $responseBlocked->getContent();
        $contentBlocked = $responseBlocked->toArray();

        $toGetInvitations = "https://api.betaseries.com/members/notifications?client_id=" . $apiKey . "&token=" . $token . "&types=friend";
        $responseInvitations = $this->client->request(
            'GET',
            $toGetInvitations,
        );

        $contentInvitations = $responseInvitations->getContent();
        $contentInvitations = $responseInvitations->toArray();

        $toReturn = [
            "friends" => $content,
            "blocked" => $contentBlocked,
            "invitations" => $contentInvitations
        ];

        return new JsonResponse($toReturn);
    }

    /**
     * @Route("/api/users/delete/notification", name="user_delete_notification", methods={"POST"})
     */
    public function deleteNotification(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toDeleteInvitations = "https://api.betaseries.com/members/notification?client_id=" . $apiKey . "&token=" . $token . "&id=" . $id;
        return new JsonResponse($toDeleteInvitations);
        $responseInvitations = $this->client->request(
            'DELETE',
            $toDeleteInvitations,
        );

        return new JsonResponse("Supprimé");
    }

    /**
     * @Route("/api/users/find", name="user_find", methods={"POST"})
     */
    public function findUsers(Request $request):Response
    {
        
        $parameters = json_decode($request->getContent(), true);
        $toSearch = $parameters['login'] . "%";
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/members/search?client_id=" . $apiKey . "&token=" . $token . "&login=" . $toSearch;
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        if($content['users'][0] == null) {
            return new JsonResponse("not found");
        }

        return new JsonResponse($content);
    }


    /**
     * @Route("/api/users/add/friends", name="user_add_friends", methods={"POST"})
     */
    public function addMemberFriends(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        // $toSearch = $parameters['login'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;
        $userId = $parameters['id'];
        
        $toGet = "https://api.betaseries.com/friends/friend?client_id=" . $apiKey . "&token=" . $token . "&id=" . $userId;
        $response = $this->client->request(
            'POST',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/list", name="user_list", methods={"POST"})
     */
    public function listMembers(Request $request):Response
    {
        
        $parameters = json_decode($request->getContent(), true);
        $toSearch = $parameters['login'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/members/search?client_id=" . $apiKey . "&token=" . $token . "&login=" . $toSearch;
        $response = $this->client->request(
            'GET',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        if($content['users'][0] == null) {
            return new JsonResponse("not found");
        }

        // $userId = $content['users'][0]['id'];
        
        // $toGet = "https://api.betaseries.com/friends/friend?client_id=" . $apiKey . "&token=" . $token . "&id=" . $userId;
        // $response = $this->client->request(
        //     'POST',
        //     $toGet,
        // );
        // $content = $response->getContent();
        // $content = $response->toArray();
        
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/remove/friends", name="user_remove_friends", methods={"POST"})
     */
    public function removeMemberFriends(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/friends/friend?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'DELETE',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/block/friends", name="user_block_friends", methods={"POST"})
     */
    public function blockMemberFriends(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/friends/block?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'POST',
            $toGet,
        );

        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/unblock/friends", name="user_unblock_friends", methods={"POST"})
     */
    public function unblockMemberFriends(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $id = $parameters['id'];
        $token = $parameters['token'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/friends/block?client_id=" . $apiKey . "&id=" . $id . "&token=" . $token;
        $response = $this->client->request(
            'DELETE',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/add/view", name="user_add_view", methods={"POST"})
     */
    public function addView(Request $request):Response
    {
        
        $parameters = json_decode($request->getContent(), true);
        $token = $parameters['token'];
        $id = $parameters['id'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/episodes/watched?client_id=" . $apiKey . "&token=" . $token . "&id=" . $id . "&bulk=false";

        $response = $this->client->request(
            'POST',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/add/views", name="user_add_views", methods={"POST"})
     */
    public function addViews(Request $request):Response
    {
        $parameters = json_decode($request->getContent(), true);
        $token = $parameters['token'];
        $id = $parameters['id'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/episodes/watched?client_id=" . $apiKey . "&token=" . $token . "&id=" . $id;

        $response = $this->client->request(
            'POST',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/remove/view", name="user_remove_view", methods={"POST"})
     */
    public function removeView(Request $request):Response
    {
        
        $parameters = json_decode($request->getContent(), true);
        $token = $parameters['token'];
        $id = $parameters['id'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/episodes/watched?client_id=" . $apiKey . "&token=" . $token . "&id=" . $id . "&bulk=false";

        $response = $this->client->request(
            'DELETE',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/add/comment", name="user_add_comment", methods={"POST"})
     */
    public function addComment(Request $request):Response
    {
        
        $parameters = json_decode($request->getContent(), true);
        $token = $parameters['token'];
        $id = $parameters['id'];
        $text = $parameters['text'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/comments/comment?type=episode&client_id=" . $apiKey . "&token=" . $token . "&id=" . $id . "&text=" . $text;

        $response = $this->client->request(
            'POST',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/users/add/note", name="user_add_note", methods={"POST"})
     */
    public function addNote(Request $request):Response
    {
        
        $parameters = json_decode($request->getContent(), true);
        $token = $parameters['token'];
        $id = $parameters['id'];
        $note = $parameters['note'];
        $apiKey = $this->apiKey;

        $toGet = "https://api.betaseries.com/episodes/note?client_id=" . $apiKey . "&token=" . $token . "&id=" . $id . "&note=" . $note;

        $response = $this->client->request(
            'POST',
            $toGet,
        );
        $content = $response->getContent();
        $content = $response->toArray();
        
        return new JsonResponse($content);
    }
}
