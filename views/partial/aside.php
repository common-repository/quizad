<section class="aside-content">
    <div class="aside-row notice notice-success">
            <span class="deactivate">
                <h4>Status wtyczki</h4>
                <p>Wtyczka jest <b>włączona</b>, reklamy są wyświeltane</p>
                <a class="button button-primary"
                   href="<?php echo esc_url(admin_url('admin-ajax.php')) ?>?action=quizAd_deactivate">
                    Wyłącz wtyczke</a>
            </span>
        <br/>
    </div>

    <!--    <div class="aside-row notice notice-content">-->
    <!--        <div class="reinstall">-->
    <!--            <h4>Wyślij dane pomocy</h4>-->
    <!--            <p>Wtyczka QuizAd jest wciąż rozwijana. Staramy&nbsp;się dokładać wszelkich starań,-->
    <!--                aby osiągnąć satysfakcje naszych klientów. W&nbsp;razie wystąpienia problemów kliknij przycisk poniżej,-->
    <!--                aby złożyć raport o&nbsp;błędzie.</p>-->
    <!--            <a class="button button-primary --><?php //echo(isset($_GET['d_success']) ? 'link-disabled" disabled' : '"'); ?>
    <!--               href=" --><?php //echo esc_url(admin_url('admin-ajax.php')) ?><!--?action=quizAd_debug">-->
    <!--                Wyślij raport</a>-->
    <!--            --><?php //if (isset($_GET['d_success'])) { ?>
    <!--                <p class="cache-info">Raport z&nbsp;diagnostyką można wysłać tylko raz na&nbsp;godzinę.</p>-->
    <!--            --><?php //} ?>
    <!--            <br/>-->
    <!--        </div>-->
    <!--    </div>-->

    <div class="aside-row notice notice-content">
        <span class="reinstall">
            <h4>Reinstalacja wtyczki</h4>
            <p><b>Usuwa</b> wszyskie dane wtyczki oraz przywraca stan przed aktywacją pluginu.</p>
            <a class="button button-primary"
               href="<?php echo esc_url(admin_url('admin-ajax.php')) ?>?action=quizAd_reinstall">
                    Przeinstaluj wtyczkę</a>
        </span>
        <br/>
    </div>

    <!--    <div class="aside-row notice notice-content">-->
    <!--                <span class="deactivate">-->
    <!--                    <h4>Ustawienia konta quizAd</h4>-->
    <!--                    <p>Ustawienia dotyczące rozliczeń (faktur, wypłat), znajdziesz w panelu na naszej stronie. </p>-->
    <!--                    <a class="button button-primary"><span>Przejdz do serwisu</span><span> quizAd</span></a>-->
    <!--                </span>-->
    <!--        <br/>-->
    <!--    </div>-->
</section>