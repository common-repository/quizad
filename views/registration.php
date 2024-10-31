<?php

include 'partial/header.php';
?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span> Formularz rejestracyjny</h2>
        </div>

        <?php include 'partial/settings/notice.php'; ?>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="form">
                    <h3>1. Podaj adres e-mail</h3>

                    <div class="email">
                        <div class="capt_style">
                            <div id="captcha" class="g-recaptcha" data-theme="dark"
                                 data-sitekey="Your Site Key"></div>
                        </div>

                        <p>
                            <label for="email"><span>Podaj swój adres e-mail:</span></label>
                            <input type="email" name="user email" id="email"
                                   class="input user" size="20">
                        </p>

                    </div>

                    <h3>2. Wybierz interesujące cię kategorie</h3>
                    <p>Wbierz co najmniej jedną z kategorii</p>

                    <div class="categories">
                        <?php include 'partial/settings/categories.php'; ?>
                    </div>

                    <div class="btn" id="sendIt">
                        <input class="button button-primary button-large" type="submit" id="submit"
                               value="Załóż nowe konto ">
                    </div>

                </form>

            </div>
        </div>

    </section>

    <section class="aside-content">
        <div class="aside-row notice notice-success">
            <span class="login">
                <h4>Logowanie</h4>
                <p>Posiadasz już aktywne konto?</p>
                <p>Aktywuj swoją stronę/bloga i&nbsp;zarabiaj razem z&nbsp;QuizAd.</p>
                <a class="button button-primary"
                   href="?page=my-submenu-ustawienia&option=login">
                    Zaloguj się</a>
            </span>
            <br/>
        </div>
    </section>

<?php include 'partial/footer.php';