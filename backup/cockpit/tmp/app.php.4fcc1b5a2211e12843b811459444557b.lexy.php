<?php

    // Generate title
    $_title = [];

    foreach (explode('/', $app['route']) as $part) {
        if (trim($part)) $_title[] = $app('i18n')->get(ucfirst($part));
    }

?><!doctype html>
<html lang="<?php echo  $app('i18n')->locale ; ?>" data-base="<?php $app->base('/'); ?>" data-route="<?php $app->route('/'); ?>" data-version="<?php echo  $app['cockpit/version'] ; ?>" data-locale="<?php echo  $app('i18n')->locale ; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo  implode(' &raquo; ', $_title).(count($_title) ? ' - ':'').$app['app.name'] ; ?></title>
    <link rel="icon" href="<?php $app->base('/favicon.ico'); ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <script>
        // App constants
        var SITE_URL   = '<?php echo  rtrim($app->filestorage->getUrl('site://'), '/') ; ?>';
        var ASSETS_URL = '<?php echo  rtrim($app->filestorage->getUrl('assets://'), '/') ; ?>';
    </script>
    <script src="<?php $app->base('assets:lib/fuc.js.php'); ?>"></script>
    <?php echo  $app->assets($app('admin')->data->get('assets'), $app['debug'] ? time() : $app['cockpit/version']) ; ?>

    <script src="<?php $app->route('/cockpit.i18n.data'); ?>"></script>

    <script>
        App.$data = <?php echo  json_encode($app('admin')->data->get('extract')) ; ?>;
        UIkit.modal.labels.Ok = App.i18n.get(UIkit.modal.labels.Ok);
        UIkit.modal.labels.Cancel = App.i18n.get(UIkit.modal.labels.Cancel);
    </script>

    <?php $app->trigger('app.layout.header'); ?>
    <?php $app->block('app.layout.header'); ?>

</head>
<body>

    <div class="app-header" data-uk-sticky="{animation: 'uk-animation-slide-top', showup:true}">

        <div class="app-header-topbar">

            <div class="uk-container uk-container-center">

                <div class="uk-grid uk-flex-middle">

                    <div>

                        <div data-uk-dropdown="delay:400,mode:'click'">

                            <a href="<?php $app->route('/'); ?>" class="uk-link-muted uk-text-bold app-name-link uk-flex uk-flex-middle">
                                <span class="app-logo"></span>
                                <span class="app-name"><?php echo  $app['app.name'] ; ?></span>
                            </a>

                            <div class="uk-dropdown app-panel-dropdown">

                                <div class="uk-grid uk-grid-gutter uk-grid-small uk-grid-divider">

                                    <div class="uk-width-medium-1-3">

                                        <div class="uk-margin">
                                            <span class="uk-badge uk-badge-outline uk-text-primary"><?php echo $app("i18n")->get('System'); ?></span>
                                        </div>

                                        <ul class="uk-nav uk-nav-side uk-nav-dropdown app-nav">

                                            <li class="<?php echo  $app['route'] == '/cockpit/dashboard' ? 'uk-active':'' ; ?>"><a href="<?php $app->route('/cockpit/dashboard'); ?>"><img class="uk-margin-small-right inherit-color" src="<?php $app->base('assets:app/media/icons/dashboard.svg'); ?>" width="30" height="30" data-uk-svg alt="assets" /> <?php echo $app("i18n")->get('Dashboard'); ?></a></li>

                                            <li class="<?php echo  strpos($app['route'],'/assetsmanager')===0 ? 'uk-active':'' ; ?>"><a href="<?php $app->route('/assetsmanager'); ?>"><img class="uk-margin-small-right inherit-color" src="<?php $app->base('assets:app/media/icons/assets.svg'); ?>" width="30" height="30" data-uk-svg alt="assets" /> <?php echo $app("i18n")->get('Assets'); ?></a></li>

                                            <?php if ($app->module("cockpit")->hasaccess('cockpit', 'accounts')) { ?>
                                            <li class="uk-nav-divider"></li>
                                            <li class="<?php echo  strpos($app['route'],'/accounts')===0 ? 'uk-active':'' ; ?>"><a href="<?php $app->route('/accounts'); ?>"><img class="uk-margin-small-right inherit-color" src="<?php $app->base('assets:app/media/icons/accounts.svg'); ?>" width="30" height="30" data-uk-svg alt="assets" /> <?php echo $app("i18n")->get('Accounts'); ?></a></li>
                                            <?php } ?>

                                            <?php if ($app->module("cockpit")->hasaccess('cockpit', 'finder')) { ?>
                                            <li class="uk-nav-divider"></li>
                                            <li class="<?php echo  strpos($app['route'],'/finder')===0 ? 'uk-active':'' ; ?>"><a href="<?php $app->route('/finder'); ?>"><img class="uk-margin-small-right inherit-color" src="<?php $app->base('assets:app/media/icons/finder.svg'); ?>" width="30" height="30" data-uk-svg alt="assets" /> <?php echo $app("i18n")->get('Finder'); ?></a></li>
                                            <?php } ?>

                                            <?php if ($app->module("cockpit")->hasaccess('cockpit', 'settings')) { ?>
                                            <li class="<?php echo  strpos($app['route'],'/settings')===0 ? 'uk-active':'' ; ?>"><a href="<?php $app->route('/settings'); ?>"><img class="uk-margin-small-right inherit-color" src="<?php $app->base('assets:app/media/icons/settings.svg'); ?>" width="30" height="30" data-uk-svg alt="assets" /> <?php echo $app("i18n")->get('Settings'); ?></a></li>
                                            <?php } ?>

                                        </ul>

                                        <?php $app->trigger('cockpit.menu.aside'); ?>

                                    </div>

                                    <div class="uk-grid-margin uk-width-medium-2-3">

                                        <div class="uk-margin">
                                            <span class="uk-badge uk-badge-outline uk-text-primary"><?php echo $app("i18n")->get('Modules'); ?></span>
                                        </div>

                                        <?php if ($app('admin')->data['menu.modules']->count()) { ?>
                                        <ul class="uk-sortable uk-grid uk-grid-match uk-grid-small uk-grid-gutter uk-text-center">

                                            <?php foreach ($app('admin')->data['menu.modules'] as $item) { ?>
                                            <li class="uk-width-1-2 uk-width-medium-1-3" data-route="<?php echo  $item['route'] ; ?>">
                                                <a class="uk-display-block uk-panel-box <?php echo  (@$item['active']) ? 'uk-bg-primary uk-contrast':'uk-panel-framed' ; ?>" href="<?php $app->route($item['route']); ?>">
                                                    <div class="uk-svg-adjust">
                                                        <?php if (preg_match('/\.svg$/i', $item['icon'])) { ?>
                                                        <img src="<?php echo $app->pathToUrl($item['icon']); ?>" alt="<?php echo $app("i18n")->get($item['label']); ?>" data-uk-svg width="30px" height="30px" />
                                                        <?php } else { ?>
                                                        <img src="<?php echo $app->pathToUrl('assets:app/media/icons/module.svg'); ?>" alt="<?php echo $app("i18n")->get($item['label']); ?>" data-uk-svg width="30px" height="30px" />
                                                        <?php } ?>
                                                    </div>
                                                    <div class="uk-text-truncate uk-text-small uk-margin-small-top"><?php echo $app("i18n")->get($item['label']); ?></div>
                                                </a>
                                            </li>
                                            <?php } ?>

                                        </ul>
                                        <?php } ?>

                                        <?php $app->trigger('cockpit.menu.main'); ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="uk-flex-item-1" riot-mount>
                        <cp-search></cp-search>
                    </div>

                    <?php if ($app('admin')->data['menu.modules']->count()) { ?>
                    <div class="uk-hidden-small">
                        <ul class="uk-subnav app-modulesbar">
                            <?php foreach ($app('admin')->data['menu.modules'] as $item) { ?>
                            <li>
                                <a class="uk-svg-adjust <?php echo  (@$item['active']) ? 'uk-active':'' ; ?>" href="<?php $app->route($item['route']); ?>" title="<?php echo $app("i18n")->get($item['label']); ?>" data-uk-tooltip="offset:10">
                                    <?php if (preg_match('/\.svg$/i', $item['icon'])) { ?>
                                    <img src="<?php echo $app->pathToUrl($item['icon']); ?>" alt="<?php echo $app("i18n")->get($item['label']); ?>" data-uk-svg width="20px" height="20px" />
                                    <?php } else { ?>
                                    <img src="<?php echo $app->pathToUrl('assets:app/media/icons/module.svg'); ?>" alt="<?php echo $app("i18n")->get($item['label']); ?>" data-uk-svg width="20px" height="20px" />
                                    <?php } ?>

                                    <?php if ($item['active']) { ?>
                                    <span class="uk-text-small uk-margin-small-left uk-text-bolder"><?php echo $app("i18n")->get($item['label']); ?></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>

                    <div>

                        <div data-uk-dropdown="mode:'click'">

                            <a class="uk-display-block" href="<?php $app->route('/accounts/account'); ?>" style="width:30px;height:30px;" riot-mount>
                                <cp-gravatar email="<?php echo  $app['user']['email'] ; ?>" size="30" alt="<?php echo  $app["user"]["name"] ? $app["user"]["name"] : $app["user"]["user"] ; ?>"></cp-gravatar>
                            </a>

                            <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-flip">
                                <ul class="uk-nav uk-nav-navbar">
                                    <li class="uk-nav-header uk-text-truncate"><?php echo  $app["user"]["name"] ? $app["user"]["name"] : $app["user"]["user"] ; ?></li>
                                    <li><a href="<?php $app->route('/accounts/account'); ?>"><?php echo $app("i18n")->get('Account'); ?></a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li class="uk-nav-item-danger"><a href="<?php $app->route('/auth/logout'); ?>"><?php echo $app("i18n")->get('Logout'); ?></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="app-main">
        <div class="uk-container uk-container-center">
            <?php $app->trigger('app.layout.contentbefore'); ?>
            <?php echo  $content_for_layout ; ?>
            <?php $app->trigger('app.layout.contentafter'); ?>
        </div>
    </div>

    <?php $app->trigger('app.layout.footer'); ?>
    <?php $app->block('app.layout.footer'); ?>

    <!-- RIOT COMPONENTS -->
    <?php foreach ($app('admin')->data['components'] as $component) { ?>
    <script type="riot/tag" src="<?php $app->base($component); ?>?nc=<?php echo  $app['debug'] ? time() : $app['cockpit/version'] ; ?>"></script>
    <?php } ?>

    <?php foreach ($app('fs')->ls('*.tag', '#config:tags') as $component) { ?>
    <script type="riot/tag" src="<?php echo $app->pathToUrl('#config:tags/'.$component->getBasename()); ?>?nc=<?php echo  $app['debug'] ? time() : $app['cockpit/version'] ; ?>"></script>
    <?php } ?>


    <div id="loginmodal" class="uk-modal" riot-view>

        <style>
            .uk-modal-dialog { width: 360px; }
        </style>

        <div class="uk-modal-dialog uk-form" ref="loginDialog">

            <form class="uk-form" method="post" action="<?php $app->route('/auth/check'); ?>" onsubmit="{ submit }">

                <div class="uk-text-center">
                    <img src="<?php $app->base('assets:app/media/icons/login.svg'); ?>" width="100" height="100">
                </div>

                <div class="uk-form-row uk-text-center uk-h2 uk-text-bold">
                    <?php echo  $app['app.name'] ; ?>
                </div>

                <div class="uk-form-row uk-margin-large-top">
                    <input ref="user" class="uk-form-large uk-width-1-1" type="text" placeholder="<?php echo $app("i18n")->get('Username'); ?>" required>
                </div>

                <div class="uk-form-row">
                    <input ref="password" class="uk-form-large uk-width-1-1" type="password" placeholder="<?php echo $app("i18n")->get('Password'); ?>" required>
                </div>

                <div class="uk-margin-top">
                    <button class="uk-button uk-button-primary uk-button-large uk-width-1-1"><?php echo $app("i18n")->get('Authenticate'); ?></button>
                </div>

            </form>

        </div>

        <script type="view/script">

            var $this = this;

            this.on('mount', function() {

                this.modal = UIkit.modal($this.root, {
                    keyboard: false,
                    bgclose: false
                });

                // check session
                var check = function() {

                    App.request('/check-backend-session').then(function(resp) {

                        if (resp && resp.status && $this.modal.isActive()) {
                            $this.modal.hide();
                        }

                        if (resp && !resp.status && !$this.modal.isActive()) {
                            $this.modal.show();
                        }
                    });
                }

                setInterval(check, 60000);

                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) check();
                }, false);
            });

            submit(e) {

                e.preventDefault();

                App.request('/auth/check', {auth:{user:this.refs.user.value, password:this.refs.password.value}}).then(function(data) {

                    if (data && data.success) {

                        if (data.user._id != App.$data.user._id) {
                            App.reroute('/');
                        } else {
                            $this.modal.dialog.find('form')[0].reset();
                            $this.modal.hide();
                        }

                    } else {

                        $this.modal.dialog.removeClass('uk-animation-shake');

                        setTimeout(function(){
                            $this.modal.dialog.addClass('uk-animation-shake');
                        }, 50);
                    }

                }, function(res) {
                    App.ui.notify(res && (res.message || res.error) ? (res.message || res.error) : 'Login failed.', 'danger');
                });

                return false;
            }

        </script>

    </div>

</body>
</html>
