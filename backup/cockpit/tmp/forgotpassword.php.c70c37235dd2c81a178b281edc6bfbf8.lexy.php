<!doctype html>
<html lang="<?php echo  $app('i18n')->locale ; ?>" class="uk-height-1-1" data-base="<?php $app->base('/'); ?>" data-route="<?php $app->route('/'); ?>" data-locale="<?php echo  $app('i18n')->locale ; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $app("i18n")->get('Password Reset!'); ?></title>
    <link rel="icon" href="<?php $app->base('/favicon.ico'); ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <?php echo  $app->assets($app['app.assets.base'], $app['cockpit/version']) ; ?>
    <?php echo  $app->assets(['assets:lib/uikit/js/components/form-password.min.js'], $app['cockpit/version']) ; ?>

    <style>
        .container {
            width: 420px;
            max-width: 90%;
        }

        .uk-panel-box-header {
            border-bottom: none;
        }

        .reset-dialog {
            box-shadow: 0 30px 75px 0 rgba(10, 25, 41, 0.2);
        }

        svg path,
        svg rect,
        svg circle {
            fill: currentColor;
        }

    </style>

</head>
<body class="passwordreset-page uk-height-viewport uk-flex uk-flex-middle uk-flex-center">

    <div class="uk-position-relative container uk-animation-scale" riot-view>

        <form class="uk-form" method="post" action="<?php $app->route('/auth/check'); ?>" onsubmit="{ submit }">

            <div id="reset-dialog" class="reset-dialog uk-panel-box uk-panel-space uk-panel-card uk-nbfc" show="{!$user}">

                <div name="header" class="uk-panel-box-header uk-text-bold uk-text-center">

                    <p>
                        <img src="<?php echo $app->pathToUrl('assets:app/media/icons/password-reset.svg'); ?>" width="80" height="80" alt="" />
                    </p>

                    <h2 class="uk-text-bold uk-text-truncate"><span><?php echo  $app['app.name'] ; ?></span></h2>

                    <p class="uk-text-bold"><?php echo $app("i18n")->get('Password Recovery'); ?></p>

                    <div class="uk-animation-shake uk-margin-top" if="{ error }">
                        <strong>{ error }</strong>
                    </div>
                </div>

                <div class="uk-alert uk-alert-success uk-text-center uk-animation-slide-bottom" if="{ reset }">
                    <?php echo $app("i18n")->get('Please check your email-inbox.'); ?>
                </div>

                <div class="uk-form-row" show="{ !reset }">
                    <input ref="user" class="uk-form-large uk-width-1-1 uk-text-center" type="text" placeholder="<?php echo $app("i18n")->get('Username or Email'); ?>" autofocus required>
                </div>

                <div class="uk-margin-top" show="{ !reset }">
                    <button class="uk-button uk-button-outline uk-button-large uk-text-primary uk-width-1-1"><?php echo $app("i18n")->get('Reset'); ?></button>
                </div>
            </div>

            <p class="uk-text-center"><a class="uk-button uk-button-link uk-link-muted" href="<?php echo  $app->retrieve('cockpit.login.url', $app->routeUrl('/auth/login')) ; ?>"><?php echo $app("i18n")->get('Back to Login'); ?></a></p>

        </form>


        <script type="view/script">

            this.error = false;
            this.reset = false;

            submit(e) {

                e.preventDefault();

                this.error = false;
                this.reset = false;

                App.request('/auth/requestreset', {user:this.refs.user.value}).then(function(data){

                    this.reset = true;
                    this.update();

                }.bind(this)).catch(function(data) {

                    this.error = '<?php echo $app("i18n")->get("User does not exist"); ?>';

                    App.$('#reset-dialog').removeClass('uk-animation-shake');

                    setTimeout(function(){
                        App.$('#reset-dialog').addClass('uk-animation-shake');
                    }, 50);

                    this.update();

                }.bind(this));

                return false;
            }

        </script>

    </div>

</body>
</html>
