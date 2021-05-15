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
            throw new StorageException('Błąd połączenia z bazą danych');
        }
    }

    public function sendInvitation(string $user1Id, string $user2Id): array
    {
        try{
            if($this->checkIfInvited($user1Id, $user2Id) == true){
                return [
                    "succeed" => false,
                    "error" => "Zaproszenie zostało już wysłane!"
                ];
            }else{
                $query = "INSERT INTO invitations(user_1_id, user_2_id) VALUES ($user1Id, $user2Id)";
                header("Location: /?action=profile&id=$user2Id");
                $this->conn->exec($query); 
                return $operationResult = [
                    "succeed" => true,
                    "error" => "Zaproszenie wysłane!"
                ];
            };
        }catch(PDOException $e){
            throw new StorageException("Błąd wysyłania zaproszenia");
        }
    }

    public function abortInvitation(string $user1Id, string $user2Id)
    {
        try{
            $query = "DELETE FROM invitations WHERE user_1_id = $user1Id AND user_2_id = $user2Id";
            $this->conn->exec($query); 
            header("Location: /?action=profile&id=$user2Id");
        }catch(PDOException $e){
            throw new StorageException("Błąd wysyłania zaproszenia");
        }
    }
    public function checkIfInvited(string $user1Id, string $user2Id): bool
    {
        try{
            $query = "SELECT COUNT(*) FROM invitations WHERE user_1_id = $user1Id AND user_2_id = $user2Id";
            $result = $this->conn->query($query);
            if($result->fetchColumn() != 0){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            throw new StorageException("Błąd wysyłania zaproszenia");
        }
    }
    public function getUserProfiles(?string $searchQuote, ?string $userId): array
    {
        try{
            if(isset($userId)){
                if(isset($searchQuote)){
                    $query = "SELECT DISTINCT user_id, name, surname FROM users WHERE name = '$searchQuote' OR surname = '$searchQuote' OR CONCAT(name, ' ', surname) = '$searchQuote';";
                }else{
                    $query = "SELECT user_id, name, surname FROM users";
                }
            }else{
                $query = "SELECT DISTINCT user_id, name, surname FROM users WHERE name = '$searchQuote' OR surname = '$searchQuote' OR CONCAT(name, ' ', surname) = '$searchQuote';";
            }
            $result = $this->conn->query($query);
            $resultArray = $result -> fetchAll(PDO::FETCH_ASSOC);
            foreach($resultArray as &$user){
                $user['profilePhoto'] = $this->getPhotos(intval($user['user_id']), null, 1)['image'];
                $user['backgroundPhoto'] = $this->getPhotos(intval($user['user_id']), null, 2)['image'];

            }
            return $resultArray;
        }catch(PDOException $e){
            dump($e);
            throw new StorageException("Błąd pobrania profili");
        }
    }
    public function login(array $data): string 
    {
        try{
            $username = $data['username'];
            $password = $data['password'];
            $query = "SELECT COUNT(*), user_id, username, password, name, surname FROM users WHERE username = '$username'";
            $result = $this->conn->query($query); 
            $queryResult = $result->fetch(PDO::FETCH_ASSOC);
            if($queryResult['COUNT(*)'] == 0){    
                return  "Taki użytkownik nie istnieje";
            }else{
                if(password_verify($password, $queryResult['password'])){
                    return "Błędne hasło!";
                }else{
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $queryResult['user_id'];
                        $_SESSION['username'] = $queryResult['username'];    
                        $_SESSION['name'] = $queryResult['name'];
                        $_SESSION['surname'] = $queryResult['surname'];       

                        header("Location: /?action=main");
                }
            }
        }catch(Throwable $e){
            throw new StorageException("Błąd logowania!");
        }
    }

    public function createAccount(array $data): array
    {
        try{
            $username = $this->conn->quote($data['newuser_username']);
            $query = "SELECT COUNT(*) FROM users WHERE username = $username";
            $result = $this->conn->query($query); 
            $queryResult = $result->fetch(PDO::FETCH_ASSOC);
            if($queryResult['COUNT(*)'] != 0){
                return  $error[] = [
                    'text' => 'Istnieje już konto o takiej nazwie użytkownika!',
                    'error_code' => 1
                ];
            }
            $email = $this->conn->quote($data['newuser_email']);
            $query = "SELECT COUNT(*) FROM users WHERE email = $email";
            $result = $this->conn->query($query); 
            $queryResult = $result->fetch(PDO::FETCH_ASSOC);
            if($queryResult['COUNT(*)'] != 0){
                return  $error[] = [
                    'text' => 'Istnieje już konto o takim adresie email!',
                    'error_code' => 2
                ];
            }else{
                $name = $this->conn->quote($data['newuser_name']);
                $surname = $this->conn->quote($data['newuser_surname']);
                $birthDate = $this->conn->quote($data['newuser_birthDate']);
                $city = $this->conn->quote($data['newuser_city']);
                $password = $this->conn->quote(password_hash($data['newuser_password'], PASSWORD_BCRYPT));
                $sex = $this->conn->quote($data['newuser_sex']);
                $birth_place = $this->conn->quote($data['newuser_birth_place']);
                $school = $this->conn->quote($data['newuser_school']);
                $work = $this->conn->quote($data['newuser_work']);
                $hobby = $this->conn->quote($data['newuser_hobby']);
                $about = $this->conn->quote($data['newuser_about']);

                $query = "INSERT INTO users (
                    name, 
                    surname, 
                    email, 
                    birth_date,
                    city,
                    username, 
                    birth_place,
                    school,
                    work,
                    hobby,
                    about,
                    password,
                    sex
                    ) VALUES (
                        $name, 
                        $surname, 
                        $email, 
                        $birthDate, 
                        $city, 
                        $username, 
                        $birth_place,
                        $school,
                        $work,
                        $hobby,
                        $about,
                        $password,
                        $sex
                    )";

                $this->conn->exec($query);
                $newUserId = $this->conn->lastInsertId();

                if(!empty($_FILES['newuser_profile_photo'])){
                    $photoUpload = $this->uploadPhoto('newuser_profile_photo', intval($newUserId), 1);
                    if($photoUpload['error'] == true){
                        $error[] = [
                            'text' => $photoUpload['response'],
                            'error_code' => 3
                        ];
                    }
                }

                if(!empty($_FILES['newuser_background_photo'])){
                    $photoUpload = $this->uploadPhoto('newuser_background_photo', intval($newUserId), 2);
                    if($photoUpload['error'] == true){
                        $error[] = [
                            'text' => $photoUpload['response'],
                            'error_code' => 3
                        ];
                    }
                }
                header("Location: /?action=login");
            }
        }catch(Throwable $e){
            dump($e);

            throw new StorageException("Błąd tworzenia konta!");
            dump($e);
        }
    }

    public function getPhotos(int $userId, ?int $photoId, ?int $usedAs): array { // jeżeli $usedAs = 0 - zdjęcie zwykłe, =1 - zdjęcie profilowe, =2 - zdjęcie w tle
        if(!empty($usedAs)){
            $query ="SELECT COUNT(*) FROM images WHERE images.author_id = '$userId' AND images.used_as = '$usedAs'"; 
            $result = $this->conn->query($query);
            if($result->fetchColumn(0) == 0){
                $query ="SELECT image FROM images WHERE images.author_id = '1' AND images.used_as = '$usedAs'"; 
            }else{
                $query ="SELECT image FROM images WHERE images.author_id = '$userId' AND images.used_as = '$usedAs'"; 

            }
        }else{
            if(!empty($photoId)){
                $query ="SELECT image FROM images WHERE images.author_id = '$userId' AND images.id = '$photoId'"; 
            }else{
                $query ="SELECT image FROM images WHERE images.author_id = '$userId' ORDER BY upload_date DESC"; 
            }
        }
        $result = $this->conn->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function uploadPhoto(string $imageName, int $authorId, int $usedAs): array { // jeżeli $usedAs = 0 - zdjęcie zwykłe, =1 - zdjęcie profilowe, =2 - zdjęcie w tle
        if($_FILES[$imageName]['size'] > 31457280){
            return $uploadStatus[] = [
                'response' => 'Plik jest za duży! Maksymalny rozmiar zdjęcia to 30 MB',
                'erorr' => true
            ];
        }

        $fileName = basename($_FILES[$imageName]['name']);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG');

        if(in_array($fileType, $allowTypes)){
            $image = $_FILES[$imageName]['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
            $query = "INSERT INTO images (author_id, image, used_as) VALUES ($authorId, '$imgContent', '$usedAs')";

            $result = $this->conn->query($query);

            if($result){ 
                return $uploadStatus[] = [
                    'response' => 'Zdjęcie przesłane pomyślnie!',
                    'id' => $this->conn->lastInsertId(),
                    'error' => false
                ];
            }else{ 
                return $uploadStatus[] = [
                    'response' => 'Przesyłanie zdjęcia nie powiodło się!',
                    'error' => true
                ];
            }  
        }else{ 
            return $uploadStatus[] = [
                'response' => 'Zły format zdjęcia. Dozwolone formaty zdjęć to jpg, jpeg oraz png',
                'error' => true
            ];
        } 
    }

    public function getProfileInfo(int $userId): array
    {
        try{
            $query = "SELECT user_id, name, surname, birth_date, creation_date, city, sex, birth_place, school, work, hobby, hobby, about FROM users WHERE user_id = $userId;";
            $result = $this->conn->query($query); 
            return $result->fetch(PDO::FETCH_ASSOC);
        }catch(Throwable $e){
            throw new StorageException("Błąd pobierania danych konta!");
        }
    }

    public function createPost(array $data): void
    {
        try{
            $tresc = $this->conn->quote($data['new_post_text']);
            $autor = $_SESSION['id'];
            $query = "INSERT INTO posts(author_id, post_text) VALUES ($autor, $tresc);";
            $this->conn->exec($query);
        }catch (Throwable $e){
            throw new StorageException('Wystapił błąd podczas dodawania nowego postu');
            dump($e);
            exit;
        }
    }

    public function getPosts(?int $userId): array
    {
        try {
            if(empty($userId)){
                $query = "SELECT post_id, author_id, posts.creation_date, post_text, name, surname FROM posts, users WHERE posts.author_id = users.user_id ORDER BY creation_date DESC";
            }else{
                $query = "SELECT post_id, author_id, posts.creation_date, post_text, name, surname FROM posts, users WHERE posts.author_id = users.user_id AND posts.author_id = $userId ORDER BY creation_date DESC";
            }
            $result = $this->conn->query($query); 
            $resultArray = $result->fetchAll(PDO::FETCH_ASSOC); //fetchAll() zastepuje foreach, zwraca tablicę, fetch() pobiera tylko pierwszy element zwrócony przez zapytanie

            foreach($resultArray as &$post){
                $post['authorPhoto'] = $this->getPhotos(intval($post['author_id']), null, 1)['image'];
            }
            return $resultArray;
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