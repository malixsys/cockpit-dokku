<div>
    <ul class="uk-breadcrumb">
        <li class="uk-active"><span><?php echo $app("i18n")->get('Regions'); ?></span></li>
    </ul>
</div>

<div riot-view>

    <div if="{ ready }">

        <div class="uk-margin uk-clearfix" if="{ App.Utils.count(regions) }">

            <div class="uk-form-icon uk-form uk-text-muted">

                <i class="uk-icon-filter"></i>
                <input class="uk-form-large uk-form-blank" type="text" ref="txtfilter" placeholder="<?php echo $app("i18n")->get('Filter regions...'); ?>" onkeyup="{ updatefilter }">

            </div>

            <?php if ($app->module("cockpit")->hasaccess('regions', 'create')) { ?>
            <div class="uk-float-right">
                <a class="uk-button uk-button-large uk-button-primary uk-width-1-1" href="<?php $app->route('/regions/region'); ?>"><?php echo $app("i18n")->get('Add Region'); ?></a>
            </div>
            <?php } ?>

        </div>

        <div class="uk-width-medium-1-1 uk-viewport-height-1-3 uk-container-center uk-text-center uk-flex uk-flex-middle uk-flex-center" if="{ !App.Utils.count(regions) }">

            <div class="uk-animation-scale">

                <p>
                    <img class="uk-svg-adjust uk-text-muted" src="<?php echo $app->pathToUrl('regions:icon.svg'); ?>" width="80" height="80" alt="Regions" data-uk-svg />
                </p>
                <hr>
                <span class="uk-text-large"><strong><?php echo $app("i18n")->get('No regions'); ?>.</strong>

                <?php if ($app->module("cockpit")->hasaccess('regions', 'create')) { ?>
                <a href="<?php $app->route('/regions/region'); ?>"><?php echo $app("i18n")->get('Create one'); ?></a></span>
                <?php } ?>

            </div>

        </div>


        <div class="uk-grid uk-grid-match uk-grid-gutter uk-grid-width-1-1 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 uk-margin-top">

            <div each="{ meta, region in regions }" show="{ infilter(meta) }">

                <div class="uk-panel uk-panel-box uk-panel-card">

                    <div class="uk-panel-teaser uk-position-relative">
                        <canvas width="600" height="350"></canvas>
                        <a href="<?php $app->route('/regions/form'); ?>/{ region }" class="uk-position-cover uk-flex uk-flex-middle uk-flex-center">
                            <div class="uk-width-1-4 uk-svg-adjust" style="color:{ (meta.color) }">
                                <img riot-src="{ meta.icon ? '<?php echo $app->pathToUrl('assets:app/media/icons/'); ?>'+meta.icon : '<?php echo $app->pathToUrl('regions:icon.svg'); ?>'}" alt="icon" data-uk-svg>
                            </div>
                        </a>
                    </div>

                    <div class="uk-grid uk-grid-small">

                        <div data-uk-dropdown="delay:300">

                            <a class="uk-icon-cog" style="color: { (meta.color) }" href="<?php $app->route('/regions/region'); ?>/{ region }" if="{ meta.allowed.region_edit }"></a>
                            <a class="uk-icon-cog" style="color: { (meta.color) }" if="{ !meta.allowed.region_edit }"></a>

                            <div class="uk-dropdown">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li class="uk-nav-header"><?php echo $app("i18n")->get('Actions'); ?></li>
                                    <li><a href="<?php $app->route('/regions/form'); ?>/{ region }"><?php echo $app("i18n")->get('Form'); ?></a></li>
                                    <li if="{ meta.allowed.region_edit }" class="uk-nav-divider"></li>
                                    <li if="{ meta.allowed.region_edit }"><a href="<?php $app->route('/regions/region'); ?>/{ region }"><?php echo $app("i18n")->get('Edit'); ?></a></li>
                                    <?php if ($app->module("cockpit")->hasaccess('regions', 'delete')) { ?>
                                    <li class="uk-nav-item-danger"><a class="uk-dropdown-close" onclick="{ this.parent.remove }"><?php echo $app("i18n")->get('Delete'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="uk-flex-item-1 uk-text-center">
                            <a class="uk-text-bold uk-link-muted" href="<?php $app->route('/regions/form'); ?>/{region}">{ meta.label || region }</a>
                        </div>
                        <div>&nbsp;</div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script type="view/script">

        var $this = this;

        this.ready  = true;
        this.regions = <?php echo  json_encode($regions) ; ?>;

        remove(e, region) {

            region = e.item.region;

            App.ui.confirm("Are you sure?", function() {

                App.request('/regions/remove_region/'+region, {nc:Math.random()}).then(function(data) {

                    App.ui.notify("Region removed", "success");

                    delete $this.regions[region];

                    $this.update();
                });
            });
        }

        updatefilter(e) {

        }

        infilter(region, value, name, label) {

            if (!this.refs.txtfilter.value) {
                return true;
            }

            value = this.refs.txtfilter.value.toLowerCase();
            name  = [region.name.toLowerCase(), region.label.toLowerCase()].join(' ');

            return name.indexOf(value) !== -1;
        }

    </script>

</div>
