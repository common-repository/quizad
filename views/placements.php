<?php

use QuizAd\Model\Placements\BadPlacementList;
use QuizAd\Model\View\PlacementsView;

/** @var PlacementsView $data */

include 'partial/header.php';
if (is_admin() && $data->getPlacements() instanceof BadPlacementList) {
    echo "<div class='notice notice-warning'>
            <h4>Placement warning</h4>
            <p>Sorry, you do not have any placement.</p>
            </div>";
} elseif (is_admin() && !$data->getPlacements()->getDefaultPlacement()) {
    echo "<div class='notice notice-warning'>
            <h4>Placement warning</h4>
            <p>Sorry, you do not have any default placement.</p>
            </div>";
} else if (is_admin() && $data->getPlacements()->getDefaultPlacement()->getPlacementId() > 0) {
    ?>
    <section class="main-content">

        <div class="headerRegister">
            <h2><span class="dashicons dashicons-editor-code"></span> Zaawansowane</h2>
        </div>

        <div class="main-content-body">

            <div id="list">
                <form id="sentence-list">

                    <div class="registration-row">
                        <h2>Wyświetlaj reklamy po określonej liczbie zdań</h2>
                        <p>Wybierz ilość zdań po których chcesz aby zostały wyświetlane reklamy.</p>
                        <div class="hr"></div>

                        <div class="check-list">
                            <label for="quest_sentence">Liczba zdań </label>
                            <input type="number" name="quest_sentence"
                                   min="4" max="50"
                                   id="quest_sentence"
                                   value="<?php echo esc_attr($data->getPlacements()->getDefaultPlacement()->getPlacementSentence()); ?>"/>
                        </div>

                        <div class="buttons-field">
                            <div class="btn">
                                <input class="button button-primary button-large" type="submit"
                                       value="Zapisz zmiany ">
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        <div class="break"></div>

        <div class="main-content-body">

            <div id="list">
                <form id="placement-position" name="displayPlacementForm">

                    <div class="registration-row">
                        <h2>Wyświetlaj reklamy w następujących miejscach </h2>
                        <p>Wybierz miejsca w których chcesz aby zostały wyświetlane reklamy.</p>
                        <div class="hr"></div>

                        <?php
                        $checkedPositions = $data->getPlacementWebsite()->getDisplayPositions();
                        $numb2            = strpos($checkedPositions, 'pp-pages') !== false ? 'checked="checked"' : '';
                        $numb3            = strpos($checkedPositions,
                            'pp-categories') !== false ? 'checked="checked"' : '';
                        $numb4            = strpos($checkedPositions, 'pp-posts') !== false ? 'checked="checked"' : '';
                        $numb5            = strpos($checkedPositions, 'pp-home') !== false ? 'checked="checked"' : '';
                        ?>

                        <div class="check-list">
                            <p>
                                <input type="checkbox" name="formPlacementsDisplay" id="pp-pages"
                                       value="2" <?php echo esc_attr("$numb2"); ?>/>
                                <label for="pp-pages">Strony</label>
                            </p>
                            <p>
                                <input type="checkbox" name="formPlacementsDisplay" id="pp-posts"
                                       value="4" <?php echo esc_attr("$numb4"); ?>/>
                                <label for="pp-posts">Posty</label>
                            </p>


                            <div class="check-list categories-list">
                                <p>
                                    <input type="checkbox" name="formPlacementsDisplay" id="pp-categories"
                                           value="3" <?php echo esc_attr("$numb3"); ?> class="all-categories"/>
                                    <label for="pp-categories">Wszystkie kategorie wpisów</label>
                                </p>
                                <?php /** @var WP_Term[] $categories - wp category list */
                                $categories = get_categories();
                                if (!empty($categories)) { ?>
                                    <div class="check-list categories-list">
                                        <?php foreach ($categories as $category) {
                                            $isChecked = '';
                                            if (in_array("category-" . $category->term_id,
                                                explode(',', $checkedPositions))) {
                                                $isChecked = 'checked="checked"';
                                            } ?>
                                            <p>
                                                <label for="category-<?php echo esc_attr($category->term_id) ?>">
                                                    <input type="checkbox"
                                                           name="formPlacementsDisplay"
                                                           id="category-<?php echo esc_attr($category->term_id) ?>"
                                                        <?php echo esc_attr("$isChecked"); ?>/>
                                                    <?php echo esc_attr($category->name) ?>
                                                </label>
                                            </p>
                                        <?php } ?>
                                    </div>

                                <?php } ?>
                            </div>

                        </div>

                        <div class="buttons-field">
                            <div class="btn">
                                <input class="button button-primary button-large" type="submit"
                                       value="Zapisz zmiany ">
                            </div>
                        </div>

                        <p class="pull-right cache-info">W przypadku stosowania mechanizmów cache, po zmianie ustawień
                            należy pamiętać o jego wyczyszczeniu.</p>
                    </div>
                </form>

            </div>

        </div>

        <div class="break"></div>

        <div class="main-content-body">
            <div class="registration-row">

                <h2>Ukryj wyświetlanie reklam w wybranch miejscach</h2>
                <p>Wybierz miesca w których <strong>nie</strong> chcesz aby pokazwywały się reklamy.</p>
                <div class="hr"></div>

                <form id="exclude-placement">

                    <?php
                    /** @var WP_Term[] $tags - wp tag list */
                    $tags = get_tags();
                    if (!empty($tags)) { ?>
                        <div class="check-list">
                            <h4>Wybierz tagi:</h4>
                            <div class="check-list categories-list">

                                <?php $excludedPositions = explode(',',
                                    $data->getPlacementWebsite()->getExcludePosition());
                                foreach ($tags as $tag) {
                                    $isChecked = '';
                                    if (in_array("tag-" . $tag->term_id, $excludedPositions)) {
                                        $isChecked = 'checked="checked"';
                                    } ?>
                                    <p>
                                        <label for="tag-<?php echo esc_attr($tag->term_id) ?>">
                                            <input type="checkbox"
                                                   name="formPlacementsExclude"
                                                   id="tag-<?php echo esc_attr($tag->term_id) ?>"
                                                <?php echo esc_attr("$isChecked"); ?>/>
                                            <?php echo esc_attr($tag->name); ?>
                                        </label>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="break"></div>
                        <div class="hr"></div>
                    <?php } ?>

                    <div class="check-list">
                        <div id="search-article">
                            <h4>Wyszukaj posty lub strony</h4>

                            <div class="excluded-list">
                                <label for="searchInput">Podaj tytuł posta/strony </label>
                                <input type="text" id="search">
                                <input class="button button-small add-excluded-btn" value="Dodaj">
                                <div id="data-fetch"></div>
                            </div>
                        </div>

                        <h4>Dotychczasowo wykluczone strony/posty:</h4>
                        <div class="excluded-placement">
                            <ul>
                                <?php $excludedPositions = explode(',',
                                    $data->getPlacementWebsite()->getExcludePosition());
                                foreach ($excludedPositions as $position) {
                                    if (!empty($position)) {
                                        $position = explode('-', esc_html($position));
                                        if (in_array($position[0], array('post', 'page'))) {
                                            $post = get_post($position[1]);
                                            ?>
                                            <li><?php echo esc_html($post->post_title) ?><a
                                                        target="<?php echo esc_html($position[0]) . '-' . esc_html($position[1]) ?>"
                                                        class="close"></a></li>

                                            <?php
                                        }
                                    }
                                } ?>
                            </ul>
                        </div>

                    </div>

                    <div class="buttons-field">
                        <div class="btn">
                            <input class="button button-primary button-large" type="submit"
                                   id="submit-placement-excluded"
                                   value="Zapisz zmiany ">
                        </div>
                    </div>
                    <p class="pull-right cache-info">W przypadku stosowania mechanizmów cache, po zmianie ustawień
                        należy pamiętać o jego wyczyszczeniu.</p>
                </form>

            </div>
        </div>

        <div class="break"></div>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="placement-list" name="defaultPlacementForm">
                    <h2>Kody PLACEMENT ID</h2>
                    <p>Wybierz tylko jedną wartość placement, którą chcesz wyświetlać na stronie.</p>
                    <table class="widefat">

                        <tbody>
                        <?php
                        $numOfColumnsInRow = 5;

                        /** @var PlacementsView $data */
                        foreach ($data->getPlacements()->getPlacements() as $key => $placement) {
                            $id = 'placement-' . $placement->getPlacementId();

                            $shouldClosePreviousRow = $key % $numOfColumnsInRow === 0;
                            if ($shouldClosePreviousRow && $key !== 0) {
                                echo "</tr>";
                            }

                            if ($shouldClosePreviousRow) {

                                echo '<tr><td>
                                    <input type="radio" name="placement" id="' . esc_attr($id) . '"
                                            data-name="formPlacementsList" 
                                     
                                            value="' . esc_attr($placement->getPlacementId()) . '"
                                            ' . (esc_attr($placement->getIsDefault()) ? 'checked="checked"' : '') . '>
                                    <label for=". $id .">' . (esc_html($placement->getPlacementName())) . '</label>
                                    </td>';
                            } else {
                                echo '<td>
                                    <input type="radio" name="placement" id="' . esc_attr($id) . '" 
                                    data-name="formPlacementsList" 
                                    value="' . (esc_attr($placement->getPlacementId())) . '"
                                    ' . (esc_attr($placement->getIsDefault()) ? 'checked="checked"' : '') . '>
                                    <label for=" . $id . ">' . (esc_html($placement->getPlacementName())) . '</label>
                                    </td>';
                            }
                        }
                        if (count($data->getPlacements()->getPlacements()) > 0) {
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>

                    <div class="buttons-field">
                        <div class="btn">
                            <input class="button button-primary button-large" type="submit"
                                   id="submit-placementid"
                                   value="Zapisz zmiany ">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="break"></div>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="download-placements" name="defaultPlacementForm">
                    <h2>Pobranie ustawień</h2>
                    <p>Pobierz najnowsze placementy z serwera.</p>
                    <div class="buttons-field">
                        <div class="btn">
                            <input class="button button-primary button-large" type="submit"
                                   id="submit-placement-download"
                                   value="Pobierz zmiany">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="break"></div>

        <!--        <div class="main-content-body">-->
        <!--            <div class="registration-row">-->
        <!--                <h2>Usuń konto</h2>-->
        <!--                <p>Zamknij swoję konto w systemie context360.</p>-->
        <!--                <div class="buttons-field">-->
        <!--                    <div class="btn">-->
        <!--                        <input class="button button-primary button-large delete" type="submit"-->
        <!--                               size="4" style="text-align: center"-->
        <!--                               value="Usuń konto">-->
        <!---->
        <!--                    </div>-->
        <!--                </div>-->
        <!---->
        <!--                <p class="cache-info delete-message" style="display: none"></p>-->
        <!---->
        <!--                <div class="buttons-field">-->
        <!--                    <div class="login btn delete-box" style="display: none;">-->
        <!--                        <form name="deleteAccountForm" id="delete-account">-->
        <!--                            <p>-->
        <!--                                <label for="user_pass">Hasło do konta wp<br>-->
        <!--                                    <input type="password" name="pwd" id="user_pass" class="input" value=""-->
        <!--                                           size="20"></label>-->
        <!--                            </p>-->
        <!---->
        <!--                            <p>-->
        <!--                                <input class="button button-danger button-large" type="submit"-->
        <!--                                       id="submit-delete-account"-->
        <!--                                       value="Potwierdzam">-->
        <!--                                <input class="button button-secondary button-large" size="4"-->
        <!--                                       style="text-align: center"-->
        <!--                                       value="Anuluje">-->
        <!--                            </p>-->
        <!--                        </form>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!---->
        <!--            </div>-->
        <!--        </div>-->

    </section>

    <?php include 'partial/aside.php';
} else {
    echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php'; ?>

<script type="application/javascript">

    window.onload = function () {
        var categoriesCheckboxes = jQuery('.categories-list').find('input[name=formPlacementsDisplay]').not('.all-categories');
        var allCategories = jQuery('.all-categories');

        if (allCategories.prop('checked')) {
            categoriesCheckboxes.prop('checked', true).attr('disabled', true);
        }

        if (!jQuery('input#pp-posts').is(':checked')) {
            allCategories.attr('disabled', true);
            categoriesCheckboxes.attr('disabled', true);
        }

    }
</script>