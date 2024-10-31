<?php


include 'partial/header.php';
if (is_admin())
{
	?>

    <section class="main-content">

        <div class="headerRegister">
            <h2><span class="dashicons dashicons-chart-bar"></span> Statystyki</h2>
        </div>

        <div class="main-content-body">
            <div class="registration-row">

                <?php include 'partial/statistics/chart.php' ?>

                <div class="btn">
                    <a class="button button-primary button-large" href="https://dashboard.quizAd.net/">
                        Pełne statystyki
                    </a>
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