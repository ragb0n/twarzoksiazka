<div class="login">
    <h1>Witaj na <i>twarzoksiążce!</i></h1>
    <div class="login_content">
        <div class="login_content_left">
            <img src="../../public/images/welcome.png"/>
            <p><i>twarzoksiążka</i> pomaga kontaktować się z innymi osobami oraz udostępniać im różne informacje i materiały.</p>
            <p><i>twarzoksiążka</i> - za darmo. Od zawsze. Na zawsze.
        </div>
        <div class="login_form">
            <h2>Logowanie</h2>
            <div class="login_error">
                <?php echo $params['username_error'] ?? null; ?>
                <?php echo $params['login_error'] ?? null; ?>
            </div>
            <form action="/?action=login" method="post" id="login_form">
                <div>
                    <label>Nazwa użytkownika</label>
                    <input type="text" name="username" class="login_form_field">
                </div>
                <div>            
                    <div class="login_error">
                        <?php echo $params['password_error'] ?? null; ?>
                    </div>
                    <label>Hasło</label>
                    <input type="password" name="password" class="login_form_field">
                </div>
                    <button type="submit" form="login_form">Zaloguj</button>
            </form>
            Nie masz jeszcze konta?
            <br>
            <a href="/?action=register">Zarejestruj się</a> już teraz!
        </div>
    </div>
</div>
