<ul class="uk-nav uk-nav-side uk-nav-dropdown uk-margin-top">

    <li class="uk-nav-header"><?php echo $app("i18n")->get('Collections'); ?></li>

    <?php foreach ($collections as $collection) { ?>
    <li>
        <a href="<?php $app->route('/collections/entries/'.$collection['name']); ?>">
        <i class="uk-icon-justify uk-icon-list"></i> <?php echo  htmlspecialchars($collection['label'] ? $collection['label'] : $collection['name']) ; ?>
        </a>
    </li>
    <?php } ?>
</ul>
