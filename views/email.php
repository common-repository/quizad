<?php include 'partial/header.php';
if (is_admin())
{
    ?>

    <section class="main-content-info">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span> Sprawdz swoją skrzynkę pocztową </h2>
        </div>
        <p>Dziękujemy za rejestrację, link aktywacyjny został wysłany na podany adres (powinien pojawić się
            niezwłocznie, ale nie później jak do 2h od momentu wypełnienia formularza).
            Prosimy o zatwierdzenie rejestracji.</p>

        <form id="resent-email">
            <div class="email">
                <p>
                    <input class="button button-primary button-large" type="submit" id="submit"
                           value="Wyślij ponownie">
                </p>
            </div>
        </form>
    </section>

    <?php include 'partial/settings/notice.php'; ?>

    <?php
}
else
{
    echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';