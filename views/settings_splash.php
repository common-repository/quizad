<?php
include 'partial/header.php';
if (is_admin()) {
    $username = '';
    /** @var array $data */
    if (!is_email($data['website']['email'])) {
        $username = esc_attr($data['website']['email']);
    }
    ?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span>Formularz logowania</h2>
        </div>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="form">
                    <h3>Plugin został zalogowany</h3>

                    <div class="email">
                        <div class="capt_style">
                            <div id="captcha" class="g-recaptcha" data-theme="dark"
                                 data-sitekey="Your Site Key"></div>
                        </div>

                        <p>
                            <label for="user_pass">
                                <span>Login: </span>
                                <input type="email" name="user email" id="email"
                                       class="input user input-disabled" size="20"
                                       value="<?php echo $username; ?>"
                                       disabled>
                            </label>
                        </p>

                    </div>

                    <div class="btn" id="sendIt">
                        <input class="button button-primary button-large" type="submit" id="submit"
                               value="Zalogowany" disabled>
                    </div>


                </form>
            </div>
        </div>


    </section>

    <?php include 'partial/aside.php';
} else {
    echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';