<div>
    <ul class="uk-breadcrumb">
        <li><a href="<?php $app->route('/settings'); ?>"><?php echo $app("i18n")->get('Settings'); ?></a></li>
        <li class="uk-active"><span><?php echo $app("i18n")->get('API Access'); ?></span></li>
    </ul>
</div>


<div class="uk-margin-top uk-form" riot-view>

    <div class="uk-grid">
        <div class="uk-width-2-3">

            <div class="uk-text-large uk-text-bold">
                <span class="uk-text-uppercase"><?php echo $app("i18n")->get('Master API-Key'); ?></span>
                <span class="uk-badge uk-badge-danger" show="{ keys.master }"><?php echo $app("i18n")->get('Share with caution'); ?></span>
            </div>

            <div class="uk-grid uk-grid-small uk-margin-top">
                <div class="uk-flex-item-1">
                    <input class="uk-width-1-1 uk-form-large uk-text-primary" type="text" placeholder="<?php echo $app("i18n")->get('No key generated'); ?>" bind="keys.master" name="fullaccesskey" readonly>
                </div>
                <div if="{keys.master}">
                    <button class="uk-button uk-button-link uk-button-large" type="button" onclick="{ copyApiKey }" title="<?php echo $app("i18n")->get('Copy Token'); ?>" data-uk-tooltip="pos:'top'"><i class="uk-icon-copy"></i></button>
                    <button class="uk-button uk-button-link uk-button-large" type="button" onclick="{ removeMasterKey }" title="<?php echo $app("i18n")->get('Delete'); ?>" data-uk-tooltip="pos:'top'"><i class="uk-icon-trash-o uk-text-danger"></i></button>
                </div>
                <div>
                    <button class="uk-button uk-button-primary uk-button-large" type="button" onclick="{ generate }" title="<?php echo $app("i18n")->get('Generate Token'); ?>" data-uk-tooltip="pos:'top'"><i class="uk-icon-magic"></i></button>
                </div>
            </div>

            <div class="uk-margin-large-top">
                <span class="uk-badge uk-badge-outline uk-text-muted"><?php echo $app("i18n")->get('Custom keys'); ?></span>
            </div>

            <div class="uk-margin" show="{keys.special.length}">

                <div class="uk-margin uk-flex" each="{setting,idx in keys.special}">
                    <div class="uk-panel uk-panel-box uk-panel-card uk-flex-item-1 uk-margin-right">

                        <div class="uk-form-row">
                            <label class="uk-text-small uk-text-uppercase"><?php echo $app("i18n")->get('API-Key'); ?></label>

                            <div class="uk-flex">
                                <input class="uk-width-1-1 uk-form-large uk-margin-right uk-text-primary" type="text" placeholder="<?php echo $app("i18n")->get('No key generated'); ?>" bind="keys.special[{idx}].token" readonly>
                                <button class="uk-button uk-button-link" type="button" onclick="{ parent.copyApiKey }" title="<?php echo $app("i18n")->get('Copy Token'); ?>" data-uk-tooltip="pos:'top'"><i class="uk-icon-copy"></i></button>
                                <button class="uk-button uk-button-link" type="button" onclick="{ parent.generate }" title="<?php echo $app("i18n")->get('Generate Token'); ?>" data-uk-tooltip="pos:'top'"><i class="uk-icon-magic"></i></button>
                            </div>
                        </div>

                        <div class="uk-form-row">
                            <label class="uk-text-small"><?php echo $app("i18n")->get('Rules'); ?></label>
                            <field-code bind="keys.special[{idx}].rules"></field-code>
                        </div>

                        <div class="uk-form-row">
                            <label class="uk-text-small"><?php echo $app("i18n")->get('Info'); ?></label>
                            <input class="uk-width-1-1 uk-form-large uk-text-muted uk-form-blank" type="text" placeholder="..." bind="keys.special[{idx}].info">
                        </div>

                    </div>

                    <div>
                        <button class="uk-button uk-button-large uk-button-danger uk-display-block" onclick="{ parent.removeKey }" title="<?php echo $app("i18n")->get('Remove Key'); ?>" data-uk-tooltip="pos:'right'"><i class="uk-icon-trash"></i></button>
                        <button class="uk-button uk-button-large uk-button-link uk-text-muted uk-display-block uk-margin-small-top" onclick="{ addKey }" title="<?php echo $app("i18n")->get('Add Key'); ?>" data-uk-tooltip="pos:'right'"><i class="uk-icon-plus"></i></button>
                    </div>
                </div>

            </div>

            <div class="uk-placeholder uk-text-center" show="{!keys.special.length}">
                <p class="uk-text-large uk-text-muted"><?php echo $app("i18n")->get('You have no custom keys'); ?></p>
                <button class="uk-button uk-button-link" onclick="{ addKey }"><i class="uk-icon-plus"></i> <?php echo $app("i18n")->get('API Key'); ?></button>
            </div>

            <div class="uk-margin-large-top" show="{ keys.master || keys.special.length }">
                <button class="uk-button uk-button-primary uk-button-large" type="button" name="button" onclick="{ save }"><?php echo $app("i18n")->get('Save'); ?></button>
                <a class="uk-button uk-button-large uk-button-link" href="<?php $app->route('/settings'); ?>"><?php echo $app("i18n")->get('Close'); ?></a>
            </div>

        </div>

        <div class="uk-width-1-3">
            <!-- TODo: Quick Docs -->
        </div>
    </div>


    <script type="view/script">

        this.mixin(RiotBindMixin);

        var $this = this;

        this.keys = <?php echo  json_encode($keys) ; ?>;

        this.on('mount', function(){

            // bind clobal command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                e.preventDefault();
                $this.save();
                return false;
            });
        });

        addKey(e) {

            this.keys.special.splice(e.item ? e.item.idx+1 : 0, 0, {
                token: App.Utils.generateToken(120),
                rules: '*',
                info: ''
            });
        }

        removeKey(e) {

            App.ui.confirm("Are you sure?", function() {
                $this.keys.special.splice(e.item.idx, 1);
                $this.update();
            });
        }

        removeMasterKey() {
            this.keys.master = '';
        }

        generate(e) {

            if (e.item) {
                e.item.setting.token = App.Utils.generateToken(120);
            } else {
                this.keys.master = App.Utils.generateToken(120);
            }
        }

        copyApiKey(e) {

            var token = e.item ? e.item.setting.token : this.keys.master;

            App.Utils.copyText(token, function() {
                App.ui.notify("Copied!", "success");
            });
        }

        save() {

            App.request('/restadmin/save', {data:this.keys}).then(function(){
                App.ui.notify("Data saved", "success");
            });
        }

    </script>

</div>
