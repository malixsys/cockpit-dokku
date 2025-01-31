
<?php if ($singleton['color']) { ?>
<style>
    .app-header { border-top: 8px <?php echo  $singleton['color'] ; ?> solid; }
</style>
<?php } ?>

<div>

    <ul class="uk-breadcrumb">
        <li><a href="<?php $app->route('/singletons'); ?>"><?php echo $app("i18n")->get('Singletons'); ?></a></li>
        <li class="uk-active" data-uk-dropdown>

            <a><i class="uk-icon-bars"></i> <?php echo  htmlspecialchars(@$singleton['label'] ? $singleton['label']:$singleton['name']) ; ?></a>

            <?php if ($app->module('singletons')->hasaccess($singleton['name'], 'edit')) { ?>
            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-nav-header"><?php echo $app("i18n")->get('Actions'); ?></li>
                    <li><a href="<?php $app->route('/singletons/singleton/'.$singleton['name']); ?>"><?php echo $app("i18n")->get('Edit'); ?></a></li>
                </ul>
            </div>
            <?php } ?>

        </li>
    </ul>

    <div class="uk-margin-top" riot-view>

        <div class="uk-alert" if="{ !fields.length }">
            <?php echo $app("i18n")->get('No fields defined'); ?>. <a href="<?php $app->route('/singletons/singleton'); ?>/{ singleton.name }"><?php echo $app("i18n")->get('Define singleton fields'); ?>.</a>
        </div>

        <h3 class="uk-flex uk-flex-middle uk-text-bold">
            <img class="uk-margin-small-right" src="<?php echo $app->pathToUrl($singleton['icon'] ? 'assets:app/media/icons/'.$singleton['icon']:'singletons:icon.svg'); ?>" width="25" alt="icon">
            { singleton.label || singleton.name }
        </h3>

        <?php if ($singleton['description']) { ?>
        <div class="uk-margin uk-text-muted">
            <?php echo  htmlspecialchars($singleton['description']) ; ?>
        </div>
        <?php } ?>

        <div class="uk-grid">

            <div class="uk-width-medium-3-4 uk-grid-margin">

                <ul class="uk-tab uk-margin-large-bottom uk-flex uk-flex-center" show="{ App.Utils.count(groups) > 1 }">
                    <li class="{ !group && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get('All') }</a></li>
                    <li class="{ group==parent.group && 'uk-active'}" each="{items,group in groups}" show="{ items.length }"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get(group) }</a></li>
                </ul>

                <form class="uk-form" if="{ fields.length }" onsubmit="{ submit }">

                    <div class="uk-grid uk-grid-match uk-grid-gutter">

                        <div class="uk-width-medium-{field.width}" each="{field,idx in fields}" show="{!group || (group == field.group) }" if="{ hasFieldAccess(field.name) }" no-reorder>

                            <div class="uk-panel">

                                <label>

                                    <span class="uk-text-bold">{ field.label || field.name }</span>

                                    <span if="{ field.localize }" data-uk-dropdown="mode:'click'">
                                        <a class="uk-icon-globe" title="<?php echo $app("i18n")->get('Localized field'); ?>" data-uk-tooltip="pos:'right'"></a>
                                        <div class="uk-dropdown uk-dropdown-close">
                                            <ul class="uk-nav uk-nav-dropdown">
                                                <li class="uk-nav-header"><?php echo $app("i18n")->get('Copy content from:'); ?></li>
                                                <li show="{parent.lang}"><a onclick="{parent.copyLocalizedValue}" lang="" field="{field.name}"><?php echo $app("i18n")->get('Default'); ?></a></li>
                                                <li show="{parent.lang != language.code}" each="{language,idx in languages}" value="{language.code}"><a onclick="{parent.parent.copyLocalizedValue}" lang="{language.code}" field="{field.name}">{language.label}</a></li>
                                            </ul>
                                        </div>
                                    </span>

                                </label>

                                <div class="uk-margin uk-text-small uk-text-muted">
                                    { field.info || ' ' }
                                </div>

                                <div class="uk-margin">
                                    <cp-field type="{field.type || 'text'}" bind="{ parent.getBindValue(field) }" opts="{ field.options || {} }"></cp-field>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="uk-margin-large-top">
                        <button class="uk-button uk-button-large uk-button-primary"><?php echo $app("i18n")->get('Save'); ?></button>
                        <a class="uk-button uk-button-link" href="<?php $app->route('/singletons'); ?>"><?php echo $app("i18n")->get('Close'); ?></a>
                    </div>

                </form>
            </div>

            <div class="uk-grid-margin uk-width-medium-1-4 uk-flex-order-first uk-flex-order-last-medium">

                <div class="uk-margin uk-form" if="{ languages.length }">

                    <div class="uk-width-1-1 uk-form-select">

                        <label class="uk-text-small"><?php echo $app("i18n")->get('Language'); ?></label>
                        <div class="uk-margin-small-top"><span class="uk-badge uk-badge-outline {lang ? 'uk-text-primary' : 'uk-text-muted'}">{ lang ? _.find(languages,{code:lang}).label:'Default' }</span></div>

                        <select bind="lang">
                            <option value=""><?php echo $app("i18n")->get('Default'); ?></option>
                            <option each="{language in languages}" value="{language.code}">{language.label}</option>
                        </select>
                    </div>

                </div>

                <div class="uk-margin">
                    <label class="uk-text-small"><?php echo $app("i18n")->get('Last Modified'); ?></label>
                    <div class="uk-margin-small-top uk-text-muted"><i class="uk-icon-calendar uk-margin-small-right"></i> {  App.Utils.dateformat( new Date( 1000 * singleton._modified )) }</div>
                </div>

                <div class="uk-margin">
                    <label class="uk-text-small"><?php echo $app("i18n")->get('Revisions'); ?></label>
                    <div class="uk-margin-small-top">
                        <span class="uk-position-relative">
                            <cp-revisions-info class="uk-badge uk-text-large" rid="{singleton._id}"></cp-revisions-info>
                            <a class="uk-position-cover" href="<?php $app->route('/singletons/revisions/'.$singleton['name']); ?>/{singleton._id}"></a>
                        </span>
                    </div>
                </div>

                <div class="uk-margin" if="{data._mby}">
                    <label class="uk-text-small"><?php echo $app("i18n")->get('Last update by'); ?></label>
                    <div class="uk-margin-small-top">
                        <cp-account account="{data._mby}"></cp-account>
                    </div>
                </div>

                <?php $app->trigger('singletons.form.aside'); ?>

            </div>


        </div>


        <script type="view/script">

            var $this = this;

            this.mixin(RiotBindMixin);

            this.singleton    = <?php echo  json_encode($singleton) ; ?>;
            this.fields    = this.singleton.fields;
            this.fieldsidx = {};

            this.data      = <?php echo  json_encode($data) ; ?> || {};

            this.languages = App.$data.languages;
            this.groups       = {main:[]};
            this.group        = 'main';

            // fill with default values
            this.fields.forEach(function(field){

                $this.fieldsidx[field.name] = field;

                if ($this.data[field.name] === undefined) {
                    $this.data[field.name] = field.options && field.options.default || null;
                }

                if (field.localize && $this.languages.length) {

                    $this.languages.forEach(function(lang) {

                        var key = field.name+'_'+lang.code;

                        if ($this.data[key] === undefined) {
                            $this.data[key] = field.options && field.options.default || null;
                            $this.data[key] = field.options && field.options['default_'+lang.code] || $this.data[key];
                        }
                    });
                }

                if (field.type == 'password') {
                    $this.data[field.name] = '';
                }

                if (field.group && !$this.groups[field.group]) {
                    $this.groups[field.group] = [];
                } else if (!field.group) {
                    field.group = 'main';
                }

                $this.groups[field.group || 'main'].push(field);
            });

            if (!this.groups[this.group].length) {
                this.group = Object.keys(this.groups)[1];
            }

            this.on('mount', function(){

                // bind clobal command + save
                Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                    $this.submit(e);
                    return false;
                });

                // wysiwyg cmd + save hack
                App.$(this.root).on('submit', function(e, component) {
                    if (component) $this.submit(e);
                });
            });

            toggleGroup(e) {
                e.preventDefault();
                this.group = e.item && e.item.group || false;
            }

            getBindValue(field) {
                return 'data.'+(field.localize && this.lang ? (field.name+'_'+this.lang):field.name);
            }

            submit(e) {

                if(e) e.preventDefault();

                var required = [];

                this.fields.forEach(function(field){

                    if (field.required && !$this.data[field.name]) {

                        if (!($this.data[field.name]===false || $this.data[field.name]===0)) {
                            required.push(field.label || field.name);
                        }
                    }
                });

                if (required.length) {
                    App.ui.notify([
                        App.i18n.get('Fill in these required fields before saving:'),
                        '<div class="uk-margin-small-top">'+required.join(',')+'</div>'
                    ].join(''), 'danger');
                    return;
                }

                App.request('/singletons/update_data/'+this.singleton.name, {data:this.data}).then(function(res) {

                    if (res) {

                        App.ui.notify("Saving successful", "success");

                        $this.data = res.data;

                        $this.fields.forEach(function(field){

                            if (field.type == 'password') {
                                $this.data[field.name] = '';
                            }
                        });

                        if ($this.tags['cp-revisions-info']) {
                            $this.tags['cp-revisions-info'].sync();
                        }

                        $this.update();

                    } else {
                        App.ui.notify("Saving failed.", "danger");
                    }

                }, function(res) {
                    App.ui.notify(res && (res.message || res.error) ? (res.message || res.error) : "Saving failed.", "danger");
                });
            }

            hasFieldAccess(field) {

                var acl = this.fieldsidx[field] && this.fieldsidx[field].acl || [];

                if (field == '_modified' ||
                    App.$data.user.group == 'admin' ||
                    !acl ||
                    (Array.isArray(acl) && !acl.length) ||
                    acl.indexOf(App.$data.user.group) > -1 ||
                    acl.indexOf(App.$data.user._id) > -1

                ) { return true; }

                return false;
            }

            copyLocalizedValue(e) {

                var field = e.target.getAttribute('field'),
                    lang = e.target.getAttribute('lang'),
                    val  = JSON.stringify(this.data[field+(lang ? '_':'')+lang]);

                this.data[field+(this.lang ? '_':'')+this.lang] = JSON.parse(val);
            }

        </script>

    </div>

</div>
