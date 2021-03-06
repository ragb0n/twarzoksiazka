<div class="register">
    <h2>Edycja danych</h2>
    <p>W tym miejscu możesz zmienić dane swojego profilu</p>

    Aktualne informacje
    <hr>
    <div>
        Imię: <?php echo $params['currentInfo']['name']; ?>
    </div>
    <div>
        Nazwisko: <?php echo $params['currentInfo']['surname']; ?>
    </div>
    <div>
        Nazwa użytkownika: <?php echo $params['currentInfo']['name']; ?>
    </div>
    <div>
        E-mail: <?php echo $params['currentInfo']['name']; ?>
    </div>
    <div>
        Data-urodzenia: <?php echo $params['currentInfo']['birth_date']; ?>
    </div>
    <div>
        Płeć: <?php echo $params['currentInfo']['sex']; ?>
    </div>
    <div>
        Mieszkasz w: <?php echo $params['currentInfo']['city']; ?>
    </div>
    <div>
        Pochodzisz z: <?php echo $params['currentInfo']['birth_place']; ?>
    </div>
    <div>
        Gdzie się uczysz: <?php echo $params['currentInfo']['school']; ?>
    </div>
    <div>
        Gdzie pracujesz: <?php echo $params['currentInfo']['work']; ?>
    </div>
    <div>
        Twoje hobby: <?php echo $params['currentInfo']['hobby']; ?>
    </div>
    <div>
        Coś więcej o Tobie: <?php echo $params['currentInfo']['about']; ?>
    </div>
    <br>
    Formularz aktualizacyjny
    <hr>
    <form action="/?action=editProfile" method="post" id="update_form" enctype="multipart/form-data">
        <div class="register_required">
        <br>
        <br>
        <div>
            <label>Imię</label>
            <input type="text" name="update_name" class="register_form_field" >
        </div>
        <div>
            <label>Nazwisko</label>
            <input type="text" name="update_surname" class="register_form_field" >
        </div>
        <div>
            <label>Nazwa użytkownika</label>
            <input type="text" name="update_username" class="register_form_field" >
        </div>
        <div>
            <label>Hasło</label>
            <input type="password" name="update_password" class="register_form_field" >
        </div>
        <div>
            <label>Powtórz hasło</label>
            <input type="password" name="update_password_repeat" class="register_form_field" >
        </div>
        <div>
            <label>E-mail</label>
            <input type="email" name="update_email" class="register_form_field" >
        </div>  
        <div>
            <label>Data urodzenia</label>
            <input type="date" id="datefield" name="update_birthDate" class="register_form_field" >
            <script> //skrypt JS ograniczający wybór daty urodzenia maksymalnie do dnia dzisiejszego
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //Styczeń to nr. 0!
                var yyyy = today.getFullYear();
                if(dd<10){
                        dd='0'+dd
                } 
                if(mm<10){
                        mm='0'+mm
                } 

                today = yyyy+'-'+mm+'-'+dd;
                document.getElementById("datefield").setAttribute("max", today);
            </script>
        </div>
        <div>
            <label>Płeć</label>
            <br>
            <label class="radio_label" for="mężczyzna">Mężczyzna</label>
            <input type="radio" id="mężczyzna" value="mężczyzna" name="update_sex" class="register_form_field">
            <label for="kobieta">Kobieta</label>
            <input class="radio_label" type="radio" id="kobieta" value="kobieta" name="update_sex" class="register_form_field">
        </div>
        <br>
        <br>
        <br>
        </div>
        <div class="register_optional">
            <br>
            <br>
            <div>
                <label>Mieszkasz w</label>
                <input type="text" name="update_city" class="register_form_field">
            </div>
            <div>
                <label>Pochodzisz z</label>
                <input type="text" name="update_birth_place" class="register_form_field">
            </div>
            <div>
                <label>Gdzie się uczysz?</label>
                <input type="text" name="update_school" class="register_form_field">
            </div>
            <div>
                <label>Gdzie pracujesz?</label>
                <input type="text" name="update_work" class="register_form_field">
            </div>
            <div>
                <label>Twoje hobby</label>
                <input type="text" name="update_hobby" class="register_form_field">
            </div>
            <div>
                <label>Coś więcej o Tobie?</label>
                <textarea name="update_about" form="register_form"></textarea>   
            </div>
        </div>
        <div class="register_summary">
            <button type="submit" form="update_form">Aktualizuj dane</button>
        </div>
    </form>  
</div>