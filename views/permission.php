<?php include 'partial/header.php';
if (is_admin()) {
    ?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span> Wystąpił nieoczekiwany błąd! </h2>
        </div>
        <p>Aby wyświetlić poprawnie tą stronę -&nbsp;należy dokonać <a
                    href="admin.php?page=my-submenu-ustawienia&option=login_splash">logowania</a>.
        </p>

        <div class="btn">
            <a href="mailto:support@splashandroll.pl">
                <input class="button button-primary button-large" type="submit" id="submit"
                       value="Skontaktuj się z naszą pomocą">
            </a>
        </div>
    </section>

    <?php
} else {
    echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';