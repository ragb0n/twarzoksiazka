<?php

declare(strict_types=1);

namespace App;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;
use Throwable;

class Database
{
    private PDO $conn;

    public function __construct(array $config)
    {
        try{
            $this->configurationValidation($config);
            $this->createConnection($config);
        }catch (PDOException $e){
            throw new StorageException('Błąd połączeniz a bazą danych');
        }
    }

    public function login(array $data): void
    {
        $username = $data['username'];
        $password = $data['password'];
        $query = "SELECT COUNT(*), user_id, username, password, name, surname FROM users WHERE username = '$username'";
        $result = $this->conn->query($query); 
        $queryResult = $result->fetchAll(PDO::FETCH_ASSOC);
        if(count($queryResult) == 1){                    
            if($queryResult[0]['password'] == $password){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $queryResult[0]['user_id'];
                $_SESSION["username"] = $queryResult[0]['username'];    
                $_SESSION["name"] = $queryResult[0]['name'];
                $_SESSION["surname"] = $queryResult[0]['surname'];                            
                        
                header("Location: /?action=main");
            }
        }
    }

    public function createAccount(array $data): void
    {
        try{
            $name = $this->conn->quote($data['newuser_name']);
            $surname = $this->conn->quote($data['newuser_surname']);
            $email = $this->conn->quote($data['newuser_email']);
            $birthDate = $this->conn->quote($data['newuser_birthDate']);
            $city = $this->conn->quote($data['newuser_city']);
            $username = $this->conn->quote($data['newuser_username']);
            $password = $this->conn->quote($data['newuser_password']);
            $sex = $this->conn->quote($data['newuser_sex']);

            $query = "INSERT INTO users (
                name, 
                surname, 
                email, 
                birth_date,
                city,
                username, 
                password,
                sex) VALUES (
                    $name, 
                    $surname, 
                    $email, 
                    $birthDate, 
                    $city, 
                    $username, 
                    $password, 
                    $sex
                )";

            $this->conn->exec($query);
        }catch(Throwable $e){
            throw new StorageException("Błąd tworzenia konta!");
        }
    }

    public function createPost(array $data): void
    {
        try{

            $tresc = $this->conn->quote($data['new_post_text']);
            $query = "INSERT INTO posts(author_id, post_text) VALUES (1, $tresc);";

            $result = $this->conn->exec($query);
        }catch (Throwable $e){
            throw new StorageException('Wystapił błąd podczas dodawania nowego postu');
            dump($e);
            exit;
        }
    }

    public function getPosts(): array
    {
        try {
            $query = "SELECT * FROM posts ORDER BY creation_date DESC";
            $result = $this->conn->query($query); 
            return $result->fetchAll(PDO::FETCH_ASSOC); //fetchAll() zastepuje foreach, zwraca tablicę, fetch() pobiera tylko pierwszy element zwrócony przez zapytanie
        }catch(Throwable $e){
            throw new StorageException("Nie udało się pobrac postów", 400, $e);
        }

    }

    private function createConnection(array $config): void
    {
        $dsn =  "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO($dsn, $config['user'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }
    private function configurationValidation(array $config): void
    {
        if (
            empty($config['database'])
            || empty($config['host'])
            || empty($config['user'])
            || empty($config['password'])
        ) {
            throw new ConfigurationException('Błąd konfiguracji połączenia');
        }
    }
}