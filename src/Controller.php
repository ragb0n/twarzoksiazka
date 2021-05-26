<?php

declare(strict_types=1);

namespace App;

use App\Request;
use App\Exception\ConfigurationException;

require_once('Exceptions/ConfigurationException.php');
require_once('View.php');
require_once('Database.php');

class Controller
{
    private const DEFAULT_ACTION = 'main';
    private static array $configuration = [];
    private Request $request;
    private Database $database;
    private View $view;

    public static function initConfigurtion(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request){
        if(empty(self::$configuration['db'])){
            throw new ConfigurationException ('Błąd konfiguracji');
        }
        $this->database = new Database(self::$configuration['db']);
        $this->request = $request;
        $this->view = new View();
    }

    public function profile(){
        $page = 'profile';

        if($this->request->getRequestGet('id') == null){
            $viewParams = $this->getProfileData(intval($_SESSION['id']));
        }else{
            $otherUserId = $this->request->getRequestGet('id');
            $viewParams = $this->getProfileData(intval($otherUserId));
            $viewParams['friendStatus'] = $this->database->checkIfAlreadyFriend($_SESSION['id'], $otherUserId);
            if($viewParams['friendStatus'] == false){
                $viewParams['isInvited'] = $this->database->checkIfInvited($_SESSION['id'], $otherUserId);
                $viewParams['pendingInvitation'] = $this->database->checkIfInvited($otherUserId, $_SESSION['id']);
                $dataPost['invite'] = $this->request->getRequestPost('invite') ?? null;
                if(isset($dataPost['invite']) && $dataPost['invite'] == 'send'){
                    $this->database->sendInvitation($_SESSION['id'], $otherUserId);
                }
                if(isset($dataPost['invite']) && $dataPost['invite'] == 'abort'){
                    $this->database->abortInvitation($_SESSION['id'], $otherUserId);
                }
                if(isset($dataPost['invite']) && $dataPost['invite'] == 'accept'){
                    $this->database->acceptInvitation($otherUserId, $_SESSION['id']);
                }
            }else{
                $dataPost['invite'] = $this->request->getRequestPost('invite') ?? null;
                if(isset($dataPost['invite']) && $dataPost['invite'] == 'delete'){
                    $this->database->deleteFriend($_SESSION['id'], $otherUserId);
                }
            }
        }
        if($this->request->getRequestPost('react') !== null) {
            $this->database->reactionAddDelete(intval($_SESSION['id']), intval($this->request->getRequestPost('react')));
        }
        $this->view->render($page, $viewParams ?? null);
    }

    public function friends(){
        $page = 'friends';

        if($this->request->getRequestPost('searchQuery') !== null){
            $searchQuery = $this->request->getRequestPost('searchQuery');
            $resultsFriends = $this->database->getUserProfiles($searchQuery, $_SESSION['id']);
            $resultsAllUsers = $this->database->getUserProfiles($searchQuery, null);
        }else{
            $resultsFriends = $this->database->getUserProfiles(null, $_SESSION['id']);
            $resultsAllUsers = $this->database->getUserProfiles(null, null);
        }
        $viewParams = [
            "friends" => $resultsFriends,
            "users" => $resultsAllUsers
        ] ?? null;
        $this->view->render($page, $viewParams ?? null);
    }
    
    public function login(){
        $page = 'login';
        
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            header("Location: /?action=main");
            exit;
        }else{
            if($this->request->getRequestPost('username') == null || $this->request->getRequestPost('password') == null){
                if($this->request->getRequestPost('username') == null){
                    $viewParams['username_error'] = "Podaj swój login";
                }
                if($this->request->getRequestPost('password') == null){
                    $viewParams['password_error'] = "Podaj swoje hasło";
                }
            }elseif($this->request->getRequestPost('username') != null && $this->request->getRequestPost('password') != null){
                $viewParams['login_error'] = $this->database->login([
                    'username' => trim($this->request->getRequestPost('username')),
                    'password' => $this->request->getRequestPost('password')
                ]);
            }
            $this->view->render($page, $viewParams ?? null);
        }
    }

    public function register(){
        $page = 'register';
        $errorFlag = false;
        $viewParams['register_error'] = [
            'name_error' => false,
            'surname_error' => false,
            'email_error' => false,
            'birthdate_error' => false,
            'username_error' => false,
            'password_error' => false,
            'password_repeat_error' => false,
            'different_passwords_error' => false,
            'sex_error' => false,
            'rules_error' => false,
            'database_answer' => null
        ];
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            header("Location: /?action=main");
            exit;
        }else{

            if(
                $this->request->getRequestPost('newuser_name') != null ||
                $this->request->getRequestPost('newuser_surname') != null ||
                $this->request->getRequestPost('newuser_email') != null ||
                $this->request->getRequestPost('newuser_birthdate') != null ||
                $this->request->getRequestPost('newuser_username') != null ||
                $this->request->getRequestPost('newuser_password') != null ||
                $this->request->getRequestPost('newuser_password_repeat') != null ||
                $this->request->getRequestPost('newuser_sex') != null ||
                $this->request->getRequestPost('rules') != null
            ){

                if(trim($this->request->getRequestPost('newuser_name')) == null){
                    $viewParams['register_error']['name_error'] = true;
                    $errorFlag = true;
                }
                if(trim($this->request->getRequestPost('newuser_surname')) == null){
                    $viewParams['register_error']['surname_error'] = true;
                    $errorFlag = true;
                }
                if(trim($this->request->getRequestPost('newuser_email')) == null){
                    $viewParams['register_error']['email_error'] = true;
                    $errorFlag = true;
                }
                if(trim($this->request->getRequestPost('newuser_birthDate')) == null){
                    $viewParams['register_error']['birthdate_error'] = true;
                    $errorFlag = true;
                }
                if(trim($this->request->getRequestPost('newuser_username')) == null){
                    $viewParams['register_error']['username_error'] = true;
                    $errorFlag = true;
                }
                if($this->request->getRequestPost('newuser_password') == null){
                    $viewParams['register_error']['password_error'] = true;
                    $errorFlag = true;
                }
                if($this->request->getRequestPost('newuser_password_repeat') == null){
                    $viewParams['register_error']['password_repeat_error'] = true;
                    $errorFlag = true;
                }
                if($this->request->getRequestPost('newuser_password') != null && $this->request->getRequestPost('newuser_password_repeat') != null && $this->request->getRequestPost('newuser_password') != $this->request->getRequestPost('newuser_password_repeat')){
                    $viewParams['register_error']['different_passwords_error'] = true;
                    $errorFlag = true;
                }
                if($this->request->getRequestPost('newuser_sex') == null){
                    $viewParams['register_error']['sex_error'] = true;
                    $errorFlag = true;
                }
                if($this->request->getRequestPost('rules') == null){
                    $viewParams['register_error']['rules_error'] = true;
                    $errorFlag = true;
                }
                if($errorFlag == true){
                    header("Reload:0");
                }else{
                    $viewParams['register_error']['database_answer'] = $this->database->createAccount([
                        'newuser_name' => trim($this->request->getRequestPost('newuser_name')),
                        'newuser_surname' => trim($this->request->getRequestPost('newuser_surname')),
                        'newuser_email' => trim($this->request->getRequestPost('newuser_email')),
                        'newuser_birthDate' => $this->request->getRequestPost('newuser_birthDate'),
                        'newuser_city' => trim($this->request->getRequestPost('newuser_city')),
                        'newuser_username' => trim($this->request->getRequestPost('newuser_username')),
                        'newuser_password' => $this->request->getRequestPost('newuser_password'),
                        'newuser_sex' => $this->request->getRequestPost('newuser_sex'),
                        'newuser_birth_place' => trim($this->request->getRequestPost('newuser_birth_place')),
                        'newuser_school' => trim($this->request->getRequestPost('newuser_school')),
                        'newuser_work' => trim($this->request->getRequestPost('newuser_work')),
                        'newuser_hobby' => trim($this->request->getRequestPost('newuser_hobby')),
                        'newuser_about' => trim($this->request->getRequestPost('newuser_about'))
                    ]);
                }
            }
        }
        $this->view->render($page, $viewParams ?? null);
    }

    public function main(){
        $page = 'main';

        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            header("Location: /?action=login");
            exit;
        }

        if($this->request->getRequestPost('new_post_text') !== null){
            $this->database->createPost(['new_post_text' => $this->request->getRequestPost('new_post_text')]);
        }

        if($this->request->getRequestPost('postDelete') !== null) {
            $this->database->deletePost(intval($this->request->getRequestPost('postDelete')));
        }

        if($this->request->getRequestPost('react') !== null) {
            $this->database->reactionAddDelete(intval($_SESSION['id']), intval($this->request->getRequestPost('react')));
        }

        $viewParams = [
            'posts' => $this->database->getPosts(null),
            'logged_user_name' => $_SESSION['name'],
            'logged_user_surname' => $_SESSION['surname'],
            'profilePhoto' => $this->database->getPhotos(intval($_SESSION['id']), null, 1),
            'backgroundPhoto' => $this->database->getPhotos(intval($_SESSION['id']), null, 2),
        ];
        $this->view->render($page, $viewParams ?? null);
    }

    public function groups(){
        $page = 'groups';
        $viewParams = [1];
        $this->view->render($page, $viewParams ?? null);
    }

    public function pages(){
        $page = 'pages';
        $viewParams = [1];
        $this->view->render($page, $viewParams ?? null);
    }

    public function logout(){
        $page = 'logout';
        $viewParams = [1];
        $this->view->render($page, $viewParams ?? null);
    }

    public function editProfile(){
        $page = 'editProfile';

        $viewParams = [
            'currentInfo' => $this->database->getProfileInfo(intval($_SESSION['id']))
        ];
        $this->view->render($page, $viewParams ?? null);
    }

    public function events(){
        $page = 'events';
        $viewParams = [1];
        $this->view->render($page, $viewParams ?? null);
    }

    public function messages(){
        $page = 'messages';
        $viewParams = [1];
        $this->view->render($page, $viewParams ?? null);
    }

    public function run(): void
    {
        switch($this->action()){
            case 'profile':
                $this->profile();
            break;
            case 'friends':
                $this->friends();
            break;
            case 'editProfile':
                $this->editProfile();
            break;
            case 'groups':
                $this->groups();
            break;
            case 'pages':
                $this->pages();
            break;
            case 'events':
                $this->events();
            break;
            case 'messages':
                $this->messages();
            break;           
            case 'logout':
                $this->logout();
            break; 
            case 'login';
                $this->login();
            break;
            case 'register':
                $this->register();
            break;
            default:
                $this->main();
            break; 
        }
    }

    public function action(): string //metoda pobierająca przez getRequestGet zawartość tablicy _GET i zwracająca z niej wartości dla kolumny action
    {
        return $this->request->getRequestGet('action', self::DEFAULT_ACTION);
    }

    private function getProfileData(?int $currentUserId){
        return $result = [
            'profileData' => $this->database->getProfileInfo($currentUserId), 
            'posts' => $this->database->getPosts($currentUserId),
            'profilePhoto' => $this->database->getPhotos($currentUserId, null, 1),
            'backgroundPhoto' => $this->database->getPhotos($currentUserId, null, 2),
        ];
    }
}