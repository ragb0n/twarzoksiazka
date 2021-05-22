<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;

require_once('Exceptions/ConfigurationException.php');
require_once('View.php');
require_once('Database.php');

class Controller
{
    private const DEFAULT_ACTION = 'main';
    private static array $configuration = [];
    private array $request;
    private Database $database;
    private View $view;

    public static function initConfigurtion(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(array $request){
        if(empty(self::$configuration['db'])){
            throw new ConfigurationException ('Błąd konfiguracji');
        }
        $this->database = new Database(self::$configuration['db']);
        $this->request = $request;
        $this->view = new View();
    }

    public function run(): void
    {
        switch($this->action()){
            case 'profile':
                $dataGet = $this->getRequestGet();
                $dataPost = $this->getRequestPost();
                $page = 'profile';

                if(!isset($dataGet['id'])){
                    $viewParams = $this->getProfileData(intval($_SESSION['id']));
                }else{
                    $viewParams = $this->getProfileData(intval($dataGet['id']));
                    $viewParams['friendStatus'] = $this->database->checkIfAlreadyFriend($_SESSION['id'], $dataGet['id']);
                    if($viewParams['friendStatus'] == false){
                        $viewParams['isInvited'] = $this->database->checkIfInvited($_SESSION['id'], $dataGet['id']);
                        $viewParams['pendingInvitation'] = $this->database->checkIfInvited($dataGet['id'], $_SESSION['id']);
                        if(isset($dataPost['invite']) && $dataPost['invite'] == 'send'){
                            $this->database->sendInvitation($_SESSION['id'], $dataGet['id']);
                        }
                        if(isset($dataPost['invite']) && $dataPost['invite'] == 'abort'){
                            $this->database->abortInvitation($_SESSION['id'], $dataGet['id']);
                        }
                        if(isset($dataPost['invite']) && $dataPost['invite'] == 'accept'){
                            $this->database->acceptInvitation($dataGet['id'], $_SESSION['id']);
                        }
                        if(isset($dataPost['invite']) && $dataPost['invite'] == 'delete'){
                            $this->database->deleteFriend($_SESSION['id'], $dataGet['id']);
                        }
                    };
                }
                if(isset($dataPost['react'])) {
                    $this->database->reactionAddDelete(intval($_SESSION['id']), intval($dataPost['react']));
                }

            break;
            case 'friends':
                $page = 'friends';
                $data = $this->getRequestPost();

                if(!empty($data)){
                    $resultsFriends = $this->database->getUserProfiles($data['searchQuery'], $_SESSION['id']);
                    $resultsAllUsers = $this->database->getUserProfiles($data['searchQuery'], null);
                }else{
                    $resultsFriends = $this->database->getUserProfiles(null, $_SESSION['id']);
                    $resultsAllUsers = $this->database->getUserProfiles(null, null);
                }
                $viewParams = [
                    "friends" => $resultsFriends,
                    "users" => $resultsAllUsers
                ] ?? null;

            break;
            case 'editProfile':
                $page = 'editProfile';
                $viewParams = [1];
            
            break;
            case 'groups':
                $page = 'groups';
                $viewParams = [1];

            break;
            case 'pages':
                $page = 'pages';
                $viewParams = [1];

            break;
            case 'events':
                $page = 'events';
                $viewParams = [1];

            break;
            case 'messages':
                $page = 'messages';
                $viewParams = [1];

            break;           
            case 'logout':
                $page = 'logout';
                $viewParams = [1];
            break; 
            case 'login';
                $page = 'login';

                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    header("Location: /?action=main");
                    exit;
                }else{
                    $data = $this->getRequestPost();
                    if(!empty($data)){
                        if(empty(trim($data['username']))){
                            $viewParams['login_error'] = "Podaj swój login";
                            break;
                        }else{
                            if(empty(trim($data['password']))){
                                $viewParams['login_error'] = "Podaj swoje hasło";
                                break;
                            }else{
                                $this->loginStatus = $this->database->login([
                                    'username' => trim($data['username']),
                                    'password' => $data['password']
                                ]);
                            }
                        }
                    }
                }
                $viewParams = [
                    'login_error' => $this->loginStatus ?? "&nbsp"
                ];
                
            break;
            case 'register':
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

                    $data = $this->getRequestPost();
                    if(!empty($data)){

                        if(empty(trim($data['newuser_name']))){
                            $viewParams['register_error']['name_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty(trim($data['newuser_surname']))){
                            $viewParams['register_error']['surname_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty(trim($data['newuser_email']))){
                            $viewParams['register_error']['email_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty(trim($data['newuser_birthDate']))){
                            $viewParams['register_error']['birthdate_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty(trim($data['newuser_username']))){
                            $viewParams['register_error']['username_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty($data['newuser_password'])){
                            $viewParams['register_error']['password_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty($data['newuser_password_repeat'])){
                            $viewParams['register_error']['password_repeat_error'] = true;
                            $errorFlag = true;
                        }
                        if(!empty($data['newuser_password']) && !empty($data['newuser_password_repeat']) && $data['newuser_password'] != $data['newuser_password_repeat']){
                            $viewParams['register_error']['different_passwords_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty($data['newuser_sex'])){
                            $viewParams['register_error']['sex_error'] = true;
                            $errorFlag = true;
                        }
                        if(empty($data['rules'])){
                            $viewParams['register_error']['rules_error'] = true;
                            $errorFlag = true;
                        }
                        if($errorFlag == true){
                            break;
                        }else{
                            $viewParams['register_error']['database_answer'] = $this->database->createAccount([
                                'newuser_name' => trim($data['newuser_name']),
                                'newuser_surname' => trim($data['newuser_surname']),
                                'newuser_email' => trim($data['newuser_email']),
                                'newuser_birthDate' => $data['newuser_birthDate'],
                                'newuser_city' => trim($data['newuser_city']),
                                'newuser_username' => trim($data['newuser_username']),
                                'newuser_password' => $data['newuser_password'],
                                'newuser_sex' => $data['newuser_sex'],
                                'newuser_birth_place' => trim($data['newuser_birth_place']),
                                'newuser_school' => trim($data['newuser_school']),
                                'newuser_work' => trim($data['newuser_work']),
                                'newuser_hobby' => trim($data['newuser_hobby']),
                                'newuser_about' => trim($data['newuser_about'])
                            ]);
                        }
                    }
                }
            break;
            default:
                $page = 'main';

                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    header("Location: /?action=login");
                    exit;
                }

                $data = $this->getRequestPost();

                if(isset($data['new_post_text'])){
                    $this->database->createPost(['new_post_text' => $data['new_post_text']]);
                }

                if(isset($data['postDelete'])) {
                    $this->database->deletePost(intval($data['postDelete']));
                }

                if(isset($data['react'])) {
                    $this->database->reactionAddDelete(intval($_SESSION['id']), intval($data['react']));
                }

                $viewParams = [
                    'posts' => $this->database->getPosts(null),
                    'logged_user_name' => $_SESSION['name'],
                    'logged_user_surname' => $_SESSION['surname'],
                    'profilePhoto' => $this->database->getPhotos(intval($_SESSION['id']), null, 1),
                    'backgroundPhoto' => $this->database->getPhotos(intval($_SESSION['id']), null, 2),
                ];
            break; 
        }
        $this->view->render($page, $viewParams ?? null);
    }

    public function action(): string //metoda pobierająca przez getRequestGet zawartość tablicy _GET i zwracająca z niej wartości dla kolumny action
    {
        $data = $this->getRequestGet();
        return $data['action'] ?? self::DEFAULT_ACTION;
    }
    
    private function getRequestPost(): array
    {
        return $this->request['post'] ?? [];
    }

    private function getRequestGet(): array
    {
        return $this->request['get'] ?? [];
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