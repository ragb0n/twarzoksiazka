<html lang='pl'>
    <head>
        <title>twarzoksiążka</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet">           
        <link href="public/style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="../public/images/favicon.png">
        <script src="https://kit.fontawesome.com/c97458608f.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <header>
            <nav>
                <div class="logo"><a href="/?action=main"><i>twarzoksiążka</i></a></div>               
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true): ?>    
                        <ul>
                            <li>
                                <a href="/?action=profile"><i class="fas fa-user-alt"></i> Profil</a>
                            </li>
                            <li>
                                <a href="/?action=friends"><i class="fas fa-user-friends"></i> Ludzie</a>
                            </li>
                            <li>
                                <a href="/?action=groups"><i class="fas fa-users"></i> Grupy</a>
                            </li>
                            <li>
                                <a href="/?action=pages"><i class="fas fa-pager"></i> Strony</a>
                            </li>
                            <li>
                                <a href="/?action=events"><i class="fas fa-calendar-day"></i> Wydarzenia</a>
                            </li>
                            <li>
                                <a href="/?action=messages"><i class="fas fa-comments"></i> Wiadomości</a>
                            </li>
                            <li>
                                <a href="/?action=logout"><i class="fas fa-sign-out-alt"></i> Wyloguj</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </nav>
        </header>
        <main>
            <?php require_once("pages/$page.php"); ?>
        </main>
        <footer>
            <i>twarzoksiążka 2021 - Patryk Marcinków</i>
            <br>
            <i>Ostatnia aktualizacja: 22.05.2021</i>
        </footer>
        <script>
        function sendReact(postId){
            $.ajax({
                type: 'POST',
                data: `react=${postId}`,
                url: '/?action=main',
                success: function() {   
                    window.location.replace("/?action=main")                
                }
            });
        }
        </script>

    </body>
</html>