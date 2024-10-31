<?php
include 'partial/header.php';
if (is_admin())
{
    $formName   = ' Formularz rejestracyjny';
    $emailHead  = '1. Podaj adres e-mail';
    $emailLabel = 'Podaj swój adres e-mail:';

    /** @var array $data */
    if (!is_email($data['website']['email']))
    {
        $formName   = ' Formularz logowania';
        $emailHead  = '1. Wprowadź token aktywacyjny';
        $emailLabel = 'Token:';
    }
    ?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span><?php echo $formName; ?></h2>
        </div>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="form">
                    <h3><?php echo $emailHead; ?></h3>

                    <div class="email">
                        <div class="capt_style">
                            <div id="captcha" class="g-recaptcha" data-theme="dark"
                                 data-sitekey="Your Site Key"></div>
                        </div>

                        <p>
                            <label for="user_pass">
                                <span><?php echo $emailLabel; ?></span>
                                <input type="email" name="user email" id="email"
                                       class="input user input-disabled" size="20"
                                       value="<?php echo esc_attr($data['website']['email']); ?>"
                                       disabled>
                            </label>
                        </p>

                    </div>

                    <h3>2. Wybierz interesujące cię kategorie</h3>
                    <p>Wbierz co najmniej jedną z kategorii</p>

                    <div class="categories disabled">


                        <div class="topPane">
                            <div class="leftPane pane" id="sources">
                                <?php
                                foreach ($data['categories']->getCategories() as $category)
                                {
                                    ?>
                                    <div class="option"><?php echo esc_html($category->getName()) ?></div>
                                <?php } ?>
                            </div>

                            <div class="rightPane pane" id="targets">
                                <?php foreach ($data['categories']->getCategories() as $category)
                                {
                                    if (in_array($category->getId(), $data['website']['categories']))
                                    {
                                        ?>
                                        <div class="option"><?php echo esc_html($category->getName()) ?></div>
                                    <?php }
                                } ?>
                            </div>
                        </div>


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
}
else
{
    echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';