<ul class="uk-nav uk-nav-side uk-nav-dropdown uk-margin-top">

    <li class="uk-nav-header"><?php echo $app("i18n")->get('Forms'); ?></li>

    <?php foreach ($forms as $form) { ?>
    <li>
        <a href="<?php $app->route('/forms/entries/'.$form['name']); ?>">
        <i class="uk-icon-justify uk-icon-inbox"></i> <?php echo  $form['label'] ? $form['label'] : $form['name'] ; ?>
        </a>
    </li>
    <?php } ?>
</ul>