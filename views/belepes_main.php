<!--Belépési form megjelenítése egy textbox-szal és egy passwordbox-szal, valamint egy belépés gombbal.-->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2 style="margin: auto;  padding-bottom: 40px; text-align: center; margin-top:0; padding-top:  20px; margin-bottom:0;">
                Belépés</h2>
            <form action="<?= SITE_ROOT ?>beleptet" method="post">
                <div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto; ">
                    <label for="login" class="form-label">Felhasználónév:</label>
                    <input type="text" name="login" id="login" class="form-control" required
                           pattern="[a-zA-Z][\-\.a-zA-Z0-9_]{3}[\-\.a-zA-Z0-9_]+">
                    <br>
                    <label for="password" class="form-label">Jelszó:</label>
                    <input type="password" name="password" id="password" class="form-control" required
                           pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$">
                    <br>
                    <input class="btn btn-outline-primary btn-lg btn-block" type="submit" value="Bejelentkezés">
                </div>
            </form>
        </div>
        <!--Regisztrálási form megjelenítése három textbox-szal és két passwordbox-szal, valamint egy regisztracio gombbal.-->
        <div class="col-md-6">
            <h2 style="margin: auto;  padding-bottom: 40px; text-align: center; margin-top:0; padding-top:  20px; margin-bottom:0;">
                Regisztráció</h2>
            <form action="<?= SITE_ROOT ?>regisztral" method="post">
                <div class="form-group" style="max-width: 400px; margin-left:auto; margin-right: auto; ">
                    <label for="csaladi_nev" class="form-label">Családi név</label>
                    <input type="text" name="csaladi_nev" id="csaladi_nev" class="form-control" required
                           inputmode="text">
                    <br>
                    <label for="utonev" class="form-label">Utónév</label>
                    <input type="text" name="utonev" id="utonev" class="form-control" required inputmode="text">
                    <br>
                    <label for="reg_login" class="form-label">Felhasználónév</label>
                    <input type="text" name="reg_login" id="reg_login" class="form-control" required
                           pattern="[a-zA-Z][\-\.a-zA-Z0-9_]{3}[\-\.a-zA-Z0-9_]+">
                    <br>
                    <label for="reg_pw" class="form-label">Jelszó:</label>
                    <input type="password" name="reg_pw" id="reg_pw" class="form-control" required
                           pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$">
                    <br>
                    <label for="reg_pw_confirm" class="form-label">Jelszó megerősítése:</label>
                    <input type="password" name="reg_pw_confirm" id="reg_pw_confirm" class="form-control" required
                           pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$"
                           aria-describedby="passwordHelp">
                    <div id="passwordHelp" class="form-text" style="font-size: small;">
                        Követelmények: min. 8 karakter, legalább egy nagy-, egy kisbetű, ill. egy speciális karakter
                        (@$!%*#?&).
                    </div>
                    <br>
                    <input class="btn btn-outline-primary btn-lg btn-block" type="submit" value="Regisztráció">
                </div>
            </form>
        </div>
    </div>
</div>

