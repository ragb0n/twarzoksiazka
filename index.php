<?php 

declare(strict_types=1);


namespace App;

use App\Exception\ConfigurationException;
use App\Exception\AppException;
use Throwable; //interfejs Throwable zawiera metody do obsługi wyjątków

require_once('/src/utils/debug.php');
require_once('/src/controller.php');
require_once('/src/Exceptions/AppException.php');
require_once('/src/Exceptions/ConfigurationException.php');


$configuration = require_once('config/config.php');;

$request = [
    'get' => $_GET,
    'post' => $_POST
];

try {
    session_start();
    Controller::initConfigurtion($configuration);
    (new Controller($request))->run();

    // $controller = new Controller($request);
    // $controller->run();
}catch (ConfigurationException $e){
    echo "<h3>Wystąpił błąd w aplikacji</h3>";
    echo 'Błąd z konfiguracją. Skontaktuj się z administratorem';
}catch (AppException $e){
    echo "<h1>Wystąpił błąd w aplikacji</h1>";
    echo '<h3>' . $e->getMessage() . '</h3>';
}catch (Throwable $e){
    echo "<h3>Wystąpił błąd w aplikacji</h3>";
}

