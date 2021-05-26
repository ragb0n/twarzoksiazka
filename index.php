<?php 

declare(strict_types=1);

namespace App;

require_once('src/utils/debug.php');
require_once('src/Controller.php');
require_once('src/Exceptions/AppException.php');
require_once('src/Exceptions/ConfigurationException.php');
require_once('src/Request.php');

use App\Request;
use App\Exception\ConfigurationException;
use App\Exception\AppException;
use Throwable; //interfejs Throwable zawiera metody do obsługi wyjątków

$configuration = require_once('config/config.php');;

$request = new Request($_GET, $_POST);

try {
    session_start();
    Controller::initConfigurtion($configuration);
    (new Controller($request))->run();

    // $controller = new Controller($request);
    // $controller->run();
}catch (ConfigurationException $e){
    echo "<h3>Wystąpił błąd w aplikacji</h3>";
    echo 'Błąd z konfiguracją. Skontaktuj się z administratorem';
    echo $e;
}catch (AppException $e){
    echo "<h1>Wystąpił błąd w aplikacji</h1>";
    echo $e;
}catch (Throwable $e){
    echo "<h3>Wystąpił błąd w aplikacji</h3>";
    echo $e;
}