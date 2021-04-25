<?php

declare(strict_types=1);

error_reporting(E_ALL); //wyświetlanie wszystkich błędów w kodzie
ini_set('display_errors','1');


function dump($data)
{
    echo '<div 
        style="
            background: lightgray;
            padding: 0 10px;
            border: 1px solid gray;
            display: inline-block;
            "
        >
    <pre>';
    print_r($data);
    echo '</pre>
    </div>
    </br>';
}