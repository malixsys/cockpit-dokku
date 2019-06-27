<div>

    <div class="uk-panel-box uk-panel-card">

        <div class="uk-panel-box-header uk-flex">
            <strong class="uk-panel-box-header-title uk-flex-item-1">
                <?php echo $app("i18n")->get('Regions'); ?>

                <?php if ($app->module("cockpit")->hasaccess('regions', 'create')) { ?>
                <a href="<?php $app->route('/regions/region'); ?>" class="uk-icon-plus uk-margin-small-left" title="<?php echo $app("i18n")->get('Create Region'); ?>" data-uk-tooltip></a>
                <?php } ?>
            </strong>

            <?php if (count($regions)) { ?>
            <span class="uk-badge uk-flex uk-flex-middle"><span><?php echo  count($regions) ; ?></span></span>
            <?php } ?>
        </div>

        <?php if (count($regions)) { ?>

            <div class="uk-margin">

                <ul class="uk-list uk-list-space uk-margin-top">
                    <?php foreach (array_slice($regions, 0, count($regions) > 5 ? 5: count($regions)) as $region) { ?>
                    <li>
                        <a href="<?php $app->route('/regions/form/'.$region['name']); ?>">

                            <img class="uk-margin-small-right uk-svg-adjust" src="<?php echo $app->pathToUrl(isset($region['icon']) && $region['icon'] ? 'assets:app/media/icons/'.$region['icon']:'regions:icon.svg'); ?>" width="18px" alt="icon" data-uk-svg>

                            <?php echo  htmlspecialchars(@$region['label'] ? $region['label'] : $region['name']) ; ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>

            </div>

            <?php if (count($regions) > 5) { ?>
            <div class="uk-panel-box-footer uk-text-center">
                <a class="uk-button uk-button-small uk-button-link" href="<?php $app->route('/regions'); ?>"><?php echo $app("i18n")->get('Show all'); ?></a>
            </div>
            <?php } ?>

        <?php } else { ?>

            <div class="uk-margin uk-text-center uk-text-muted">

                <p>
                    <img src="<?php echo $app->pathToUrl('regions:icon.svg'); ?>" width="30" height="30" alt="Regions" data-uk-svg />
                </p>

                <?php echo $app("i18n")->get('No regions'); ?>.

                <?php if ($app->module("cockpit")->hasaccess('regions', 'create')) { ?>
                <a href="<?php $app->route('/regions/region'); ?>"><?php echo $app("i18n")->get('Create a region'); ?></a>.
                <?php } ?>

            </div>

        <?php } ?>

    </div>

</div>
