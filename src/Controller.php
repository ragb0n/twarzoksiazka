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
    private string $loggedUser;

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
            case 'friends':
                $page = 'friends';
            break;
            case 'groups':
                $page = 'groups';
            break;
            case 'pages':
                $page = 'pages';
            break;
            case 'events':
                $page = 'events';
            break;
            case 'messages':
                $page = 'messages';
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
                        $this->loggedUser = $this->database->login([
                                'username' => $data['username'],
                                'password' => $data['password']
                            ]);
                    }
                }
                $viewParams = [1];
            break;
            case 'register':
                $page = 'register';

                $data = $this->getRequestPost();

                if(!empty($data)){
                    $this->database->createAccount([
                        'newuser_name' => $data['newuser_name'],
                        'newuser_surname' => $data['newuser_surname'],
                        'newuser_email' => $data['newuser_email'],
                        'newuser_birthDate' => $data['newuser_birthDate'],
                        'newuser_city' => $data['newuser_city'],
                        'newuser_username' => $data['newuser_username'],
                        'newuser_password' => $data['newuser_password'],
                        'newuser_sex' => $data['newuser_sex']
                    ]);
                }

                $viewParams = [1];
            break;
            default:
                $page = 'main';
                
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    header("Location: /?action=login");
                    exit;
                }

                $data = $this->getRequestPost();

                if(!empty($data)){
                    $this->database->createPost(['new_post_text' => $data['new_post_text']]);
                }

                $viewParams = [
                    'posts' => $this->database->getPosts(),
                    'logged_user_name' => $_SESSION['name'],
                    'logged_user_surname' => $_SESSION['surname']
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
}