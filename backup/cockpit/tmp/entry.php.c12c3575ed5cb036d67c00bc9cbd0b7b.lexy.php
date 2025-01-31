
<script type="riot/tag" src="<?php $app->base('collections:assets/collection-entrypreview.tag'); ?>"></script>

<?php if (isset($collection['color']) && $collection['color']) { ?>
<style>
    .app-header { border-top: 8px <?php echo  $collection['color'] ; ?> solid; }
</style>
<?php } ?>

<div>
    <ul class="uk-breadcrumb">
        <li><a href="<?php $app->route('/collections'); ?>"><?php echo $app("i18n")->get('Collections'); ?></a></li>
        <li data-uk-dropdown="mode:'hover', delay:300">
            <a href="<?php $app->route('/collections/entries/'.$collection['name']); ?>"><i class="uk-icon-bars"></i> <?php echo  htmlspecialchars(@$collection['label'] ? $collection['label']:$collection['name']) ; ?></a>

            <?php if ($app->module('collections')->hasaccess($collection['name'], 'collection_edit')) { ?>
            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-nav-header"><?php echo $app("i18n")->get('Actions'); ?></li>
                    <li><a href="<?php $app->route('/collections/collection/'.$collection['name']); ?>"><?php echo $app("i18n")->get('Edit'); ?></a></li>
                    <li class="uk-nav-divider"></li>
                    <li class="uk-text-truncate"><a href="<?php $app->route('/collections/export/'.$collection['name']); ?>" download="<?php echo  $collection['name'] ; ?>.collection.json"><?php echo $app("i18n")->get('Export entries'); ?></a></li>
                    <li class="uk-text-truncate"><a href="<?php $app->route('/collections/import/collection/'.$collection['name']); ?>"><?php echo $app("i18n")->get('Import entries'); ?></a></li>
                </ul>
            </div>
            <?php } ?>
        </li>
    </ul>
</div>

<div class="uk-margin-top-large" riot-view>

    <div class="uk-alert" if="{ !fields.length }">
        <?php echo $app("i18n")->get('No fields defined'); ?>. <a href="<?php $app->route('/collections/collection'); ?>/{ collection.name }"><?php echo $app("i18n")->get('Define collection fields'); ?>.</a>
    </div>

    <h3 class="uk-flex uk-flex-middle uk-text-bold">
        <img class="uk-margin-small-right" src="<?php echo $app->pathToUrl($collection['icon'] ? 'assets:app/media/icons/'.$collection['icon']:'collections:icon.svg'); ?>" width="25" alt="icon">
        { App.i18n.get(entry._id ? 'Edit Entry':'Add Entry') }

        <a class="uk-text-large uk-margin-small-left" onclick="{showPreview}" if="{ collection.contentpreview && collection.contentpreview.enabled }" title="<?php echo $app("i18n")->get('Preview'); ?>"><i class="uk-icon-eye"></i></a>
    </h3>

    <div class="uk-grid">

        <div class="uk-grid-margin uk-width-medium-3-4">

            <form class="uk-form" if="{ fields.length }" onsubmit="{ submit }">

                <ul class="uk-tab uk-margin-large-bottom uk-flex uk-flex-center uk-noselect" show="{ App.Utils.count(groups) > 1 }">
                    <li class="{ !group && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get('All') }</a></li>
                    <li class="{ group==parent.group && 'uk-active'}" each="{items,group in groups}" show="{ items.length }"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get(group) }</a></li>
                </ul>

                <div class="uk-grid uk-grid-match uk-grid-gutter" if="{ !preview }">

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
                                <cp-field type="{field.type || 'text'}" bind="entry.{ field.localize && parent.lang ? (field.name+'_'+parent.lang):field.name }" opts="{ field.options || {} }"></cp-field>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="uk-margin-large-top">
                    <button class="uk-button uk-button-large uk-button-primary"><?php echo $app("i18n")->get('Save'); ?></button>
                    <a class="uk-button uk-button-link" href="<?php $app->route('/collections/entries/'.$collection['name']); ?>">
                        <span show="{ !entry._id }"><?php echo $app("i18n")->get('Cancel'); ?></span>
                        <span show="{ entry._id }"><?php echo $app("i18n")->get('Close'); ?></span>
                    </a>
                </div>

            </form>

        </div>

        <div class="uk-grid-margin uk-width-medium-1-4 uk-flex-order-first uk-flex-order-last-medium">

            <div class="uk-margin uk-form" if="{ languages.length }">

                <div class="uk-width-1-1 uk-form-select">

                    <label class="uk-text-small"><?php echo $app("i18n")->get('Language'); ?></label>
                    <div class="uk-margin-small-top"><span class="uk-badge uk-badge-outline {lang ? 'uk-text-primary' : 'uk-text-muted'}">{ lang ? _.find(languages,{code:lang}).label:'Default' }</span></div>

                    <select bind="lang" onchange="{persistLanguage}">
                        <option value=""><?php echo $app("i18n")->get('Default'); ?></option>
                        <option each="{language,idx in languages}" value="{language.code}">{language.label}</option>
                    </select>
                </div>

            </div>

            <div class="uk-margin">
                <label class="uk-text-small"><?php echo $app("i18n")->get('Last Modified'); ?></label>
                <div class="uk-margin-small-top uk-text-muted" if="{entry._id}">
                    <i class="uk-icon-calendar uk-margin-small-right"></i> {  App.Utils.dateformat( new Date( 1000 * entry._modified )) }
                </div>
                <div class="uk-margin-small-top uk-text-muted" if="{!entry._id}"><?php echo $app("i18n")->get('Not saved yet'); ?></div>
            </div>

            <div class="uk-margin" if="{entry._id}">
                <label class="uk-text-small"><?php echo $app("i18n")->get('Revisions'); ?></label>
                <div class="uk-margin-small-top">
                    <span class="uk-position-relative">
                        <cp-revisions-info class="uk-badge uk-text-large" rid="{entry._id}"></cp-revisions-info>
                        <a class="uk-position-cover" href="<?php $app->route('/collections/revisions/'.$collection['name']); ?>/{entry._id}"></a>
                    </span>
                </div>
            </div>

            <div class="uk-margin" if="{entry._id && entry._mby}">
                <label class="uk-text-small"><?php echo $app("i18n")->get('Last update by'); ?></label>
                <div class="uk-margin-small-top">
                    <cp-account account="{entry._mby}"></cp-account>
                </div>
            </div>

            <?php $app->trigger('collections.entry.aside'); ?>

        </div>

    </div>

    <collection-entrypreview collection="{collection}" entry="{entry}" groups="{ groups }" fields="{ fields }" fieldsidx="{ fieldsidx }" excludeFields="{ excludeFields }" languages="{ languages }" settings="{ collection.contentpreview }" if="{ preview }"></collection-entrypreview>

    <script type="view/script">

        var $this = this;

        this.mixin(RiotBindMixin);

        this.collection   = <?php echo  json_encode($collection) ; ?>;
        this.fields       = this.collection.fields;
        this.fieldsidx    = {};
        this.excludeFields = <?php echo  json_encode($excludeFields) ; ?>;

        this.entry        = <?php echo  json_encode($entry) ; ?>;

        this.languages    = App.$data.languages;
        this.groups       = {Main:[]};
        this.group        = '';

        if (this.languages.length) {
            this.lang = App.session.get('collections.entry.'+this.collection._id+'.lang', '');
        }

        // fill with default values
        this.fields.forEach(function(field) {

            $this.fieldsidx[field.name] = field;

            if ($this.entry[field.name] === undefined) {
                $this.entry[field.name] = field.options && field.options.default || null;
            }

            if (field.localize && $this.languages.length) {

                $this.languages.forEach(function(lang) {

                    var key = field.name+'_'+lang.code;

                    if ($this.entry[key] === undefined) {

                        if (field.options && field.options['default_'+lang.code] === null) {
                            return;
                        }

                        $this.entry[key] = field.options && field.options.default || null;
                        $this.entry[key] = field.options && field.options['default_'+lang.code] || $this.entry[key];
                    }
                });
            }

            if (field.type == 'password') {
                $this.entry[field.name] = '';
            }

            if ($this.excludeFields.indexOf(field.name) > -1) {
                return;
            }

            if (field.group && !$this.groups[field.group]) {
                $this.groups[field.group] = [];
            } else if (!field.group) {
                field.group = 'Main';
            }

            $this.groups[field.group || 'Main'].push(field);
        });

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
            this.group = e.item && e.item.group || false;
        }

        submit(e) {

            if (e) {
                e.preventDefault();
            }

            var required = [];

            this.fields.forEach(function(field){

                if (field.required && !$this.entry[field.name]) {

                    if (!($this.entry[field.name]===false || $this.entry[field.name]===0)) {
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

            App.request('/collections/save_entry/'+this.collection.name, {entry:this.entry}).then(function(entry) {

                if (entry) {

                    App.ui.notify("Saving successful", "success");

                    _.extend($this.entry, entry);

                    $this.fields.forEach(function(field){

                        if (field.type == 'password') {
                            $this.entry[field.name] = '';
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

            return false;
        }

        showPreview() {
            this.preview = true;
        }

        hasFieldAccess(field) {

            var acl = this.fieldsidx[field] && this.fieldsidx[field].acl || [];

            if (this.excludeFields.indexOf(field) > -1) {
                return false;
            }

            if (field == '_modified' ||
                App.$data.user.group == 'admin' ||
                !acl ||
                (Array.isArray(acl) && !acl.length) ||
                acl.indexOf(App.$data.user.group) > -1 ||
                acl.indexOf(App.$data.user._id) > -1
            ) {
                return true;
            }

            return false;
        }

        persistLanguage(e) {
            App.session.set('collections.entry.'+this.collection._id+'.lang', e.target.value);
        }

        copyLocalizedValue(e) {

            var field = e.target.getAttribute('field'),
                lang = e.target.getAttribute('lang'),
                val = JSON.stringify(this.entry[field+(lang ? '_':'')+lang]);

            this.entry[field+(this.lang ? '_':'')+this.lang] = JSON.parse(val);
        }

    </script>

</div>
