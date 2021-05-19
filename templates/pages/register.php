<div class="register">
        <h2>Rejestracja</h2>
        <p>Wypełnij poniższy formularz, aby stworzyć swoje konto w serwisie twarzoksiążka!</p>
        <form action="/?action=register" method="post" id="register_form" enctype="multipart/form-data">
                <div class="register_required">
                Informacje obowiązkowe
                <br>
                <br>

                        <div>
                                <label>Imię</label><span class="required_field">*</span> 
                                <?php if($params['register_error']['name_error'] == true): ?>
                                        <span class="registration_error">To pole nie może być puste!</span>
                                <?php endif; ?>
                                <input type="text" name="newuser_name" class="register_form_field" >
                        </div>
                        <div>
                                <label>Nazwisko</label><span class="required_field">*</span>
                                <?php if($params['register_error']['surname_error'] == true): ?>
                                        <span class="registration_error">To pole nie może być puste!</span>
                                <?php endif; ?>
                                <input type="text" name="newuser_surname" class="register_form_field" >
                        </div>
                        <div>
                                <label>Nazwa użytkownika</label><span class="required_field">*</span>
                                <?php if($params['register_error']['username_error'] == true): ?>
                                        <span class="registration_error">To pole nie może być puste!</span>
                                <?php elseif(isset($params['register_error']['database_answer']) && $params['register_error']['database_answer']['error_code'] == 1): ?>
                                        <span class="registration_error"><?php echo $params['register_error']['database_answer']['text']; ?></span>
                                <?php endif; ?>
                                <input type="text" name="newuser_username" class="register_form_field" >
                        </div>
                        <div>
                                <label>Hasło</label><span class="required_field">*</span>
                                <?php if($params['register_error']['password_error'] == true): ?>
                                <span class="registration_error">To pole nie może być puste!</span>
                                <?php endif; ?>
                                <input type="password" name="newuser_password" class="register_form_field" >
                        </div>
                        <div>
                                <label>Powtórz hasło</label><span class="required_field">*</span>
                                <?php if($params['register_error']['password_repeat_error'] == true): ?>
                                <span class="registration_error">To pole nie może być puste!</span>
                                <?php elseif($params['register_error']['different_passwords_error'] == true): ?>
                                <span class="registration_error">Hasła nie są identyczne!</span>
                                <?php endif; ?>
                                <input type="password" name="newuser_password_repeat" class="register_form_field" >
                        </div>
                        <div>
                                <label>E-mail</label><span class="required_field">*</span>
                                <?php if($params['register_error']['email_error'] == true): ?>
                                <span class="registration_error">To pole nie może być puste!</span>
                                <?php elseif(isset($params['register_error']['database_answer']) && $params['register_error']['database_answer']['error_code'] == 2): ?>
                                <span class="registration_error"><?php echo $params['register_error']['database_answer']['text']; ?></span>
                                <?php endif; ?>
                                <input type="email" name="newuser_email" class="register_form_field" >
                        </div>  
                        <div>
                                <label>Data urodzenia</label><span class="required_field">*</span>
                                <?php if($params['register_error']['birthdate_error'] == true): ?>
                                        <span class="registration_error">To pole nie może być puste!</span>
                                <?php endif; ?>
                                <input type="date" id="datefield" name="newuser_birthDate" class="register_form_field" >
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
                                <label>Płeć</label><span class="required_field">*</span>
                                <?php if($params['register_error']['sex_error'] == true): ?>
                                        <span class="registration_error">Musisz wybrać płeć!</span>
                                <?php endif; ?>

                                <br>

                                <label class="radio_label" for="mężczyzna">Mężczyzna</label>
                                <input type="radio" id="mężczyzna" value="mężczyzna" name="newuser_sex" class="register_form_field">
                                <label for="kobieta">Kobieta</label>
                                <input class="radio_label" type="radio" id="kobieta" value="kobieta" name="newuser_sex" class="register_form_field">
                        </div>

                        <br>

                        <input  style="width: 5%" type="checkbox" id="accepted" name="rules" value="accepted" >
                        <label for="accepted"> 
                                <span class="required_field">*</span> Oświadczam, że zapoznałem/am się z Regulaminem Serwisu <i>twarzoksiążka</i> (którego nie ma) i akceptuję wszystkie zawarte w nim warunki.
                        </label>
                        <?php if($params['register_error']['rules_error'] == true): ?>
                                <span class="registration_error">Aby założyć konto, musisz akceptować regulamin serwisu!</span>
                        <?php endif; ?>

                        <br>
                        <br>

                </div>
                <div class="register_optional">
                        Informacje dodatkowe
                        <br>
                        <br>
                        <div>
                                <label>Mieszkasz w</label>
                                <input type="text" name="newuser_city" class="register_form_field">
                        </div>
                        <div>
                                <label>Pochodzisz z</label>
                                <input type="text" name="newuser_birth_place" class="register_form_field">
                        </div>
                        <div>
                                <label>Zdjęcie profilowe (max. 30 MB, format jpg, png, jpeg)</label>
                                <input type="file" name="newuser_profile_photo" class="register_form_field">
                        </div>
                        <div>
                                <label>Zdjęcie w tle (max. 30 MB, format jpg, png, jpeg)</label>
                                <input type="file" name="newuser_background_photo" class="register_form_field">
                        </div>
                        <div>
                                <label>Gdzie się uczysz?</label>
                                <input type="text" name="newuser_school" class="register_form_field">
                        </div>
                        <div>
                                <label>Gdzie pracujesz?</label>
                                <input type="text" name="newuser_work" class="register_form_field">
                        </div>
                        <div>
                                <label>Twoje hobby</label>
                                <input type="text" name="newuser_hobby" class="register_form_field">
                        </div>
                        <div>
                                <label>Coś więcej o Tobie?</label>
                                <textarea name="newuser_about" form="register_form"></textarea>   
                        </div>
                </div>
                <div class="register_summary">
                        <br>
                        <br>
                        Pamiętaj, że wszystkie te informacje możesz później zmienić, z poziomu panelu edycji profilu.
                        <br>
                        <br>
                        <button type="submit" form="register_form">Rejestracja</button>
                </div>
        </form>  
        
</div>