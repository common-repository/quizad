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
                <form id="form" class="form-login">
                    <h3>1. Wprowadź token aktywacyjny</h3>
                    <p>Posiadając już aktywne konto, token możesz sam wygenerować w Ustawieniach</p>
                    <div class="email">
                        <div class="capt_style">
                            <div id="captcha" class="g-recaptcha" data-theme="dark"
                                 data-sitekey="Your Site Key"></div>
                        </div>

                        <div class="box">
                            <p>
                            <label for="token"><span>Token:</span></label>
                            <input name="user token" id="token" class="input user" size="20">
                            <label for="confirm"></label>
                            <input name="user token" id="confirm" class="button" size="8" value="Zweryfikuj token">
                            </p>
                            <div class="loader" style="display:none">Loading...</div>
                        </div>
                    </div>

                    <h3>2. Wybierz interesujące cię kategorie</h3>
                    <p>Wbierz co najmniej jedną z kategorii</p>

                    <div class="categories disabled">
                        <?php include 'partial/settings/categories.php'; ?>
                    </div>

                    <div class="btn disabled" id="singIn">
                        <input class="button button-primary button-large disabled" type="submit" id="login"
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
                <p>Zarejestruj swoją aplikacje wordpress i&nbsp;korzytaj z&nbsp;pełnych możliwośći QuizAd.</p>
                <a class="button button-primary"
                   href="?page=my-submenu-ustawienia">
                    Zarejestruj się</a>
            </span>
            <br/>
        </div>
    </section>

<?php include 'partial/footer.php';