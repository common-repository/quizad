<?php

include 'partial/header.php';
?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span> Formularz logowania</h2>
        </div>

        <?php include 'partial/settings/notice.php'; ?>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="form-login-splash">
                    <h3>Wprowadź dane logowania</h3>
                    <p>Posiadając już aktywne konto, możesz sam zarządzać ustawieniami</p>
                    <div class="box">
                        <p>
                            <label for="username"><span>Login:</span></label>
                            <input name="user username" id="username" class="input user" size="20">
                        </p>
                        <p>
                            <label for="password"><span>Hasło:</span></label>
                            <input name="user password" id="password" class="input user" size="20" type="password">
                        </p>
                        <div class="loader" style="display:none">Loading...</div>
                        <div class="btn" id="singIn">
                            <input class="button button-primary button-large" type="submit" id="login"
                                   value="Zaloguj się">
                        </div>
                </form>

            </div>
        </div>

    </section>

    <section class="aside-content">
        <div class="aside-row notice notice-success">
            <span class="login">
                <h4>Rejestracja</h4>
                <p>Nie posiadasz jeszcze konta?</p>
                <p>Napisz do nas, a&nbsp;my założymy je dla Ciebie.</p>
                <div class="btn">
                    <a href="mailto:support@splashandroll.pl">
                        <input class="button button-primary button-large" type="submit" id="submit"
                               value="Napisz do nas">
                    </a>
                </div>
            </span>
            <br/>
        </div>
    </section>

<?php include 'partial/footer.php';