<div class="full-page__row">

    <?php $content = $debates; $title = "House of Commons debates"; include '_business_section.php'; ?>
    <?php $search_title = 'Search Commons, Lords, and Westminster Hall debates'; include '_search.php'; ?>
    <?php
        $no_survey = 1;
        $section = 'whall';
        $content = $whall;
        $title = "Westminster Hall debates";
        $urls['day'] = $urls['whallday'];
        include '_business_section.php'; ?>
    <?php
        $section = 'lords';
        $content = $lords;
        $title = "House of Lords debates";
        $urls['day'] = $urls['lordsday'];
        include '_business_section.php'; ?>

</div>
