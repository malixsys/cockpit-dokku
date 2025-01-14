<div>
    <ul class="uk-breadcrumb">
        <li><a href="<?php $app->route('/collections'); ?>"><?php echo $app("i18n")->get('Collections'); ?></a></li>
        <li class="uk-active"><span><?php echo $app("i18n")->get('Collection'); ?></span></li>
    </ul>
</div>

<div class="uk-margin-top" riot-view>

    <form class="uk-form" onsubmit="{ submit }">

        <div class="uk-grid" data-uk-grid-margin>

            <div class="uk-width-medium-1-4">
               <div class="uk-panel uk-panel-box uk-panel-card">

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Name'); ?></label>
                       <input class="uk-width-1-1 uk-form-large" type="text" ref="name" bind="collection.name" pattern="[a-zA-Z0-9_]+" required>
                       <p class="uk-text-small uk-text-muted" if="{!collection._id}">
                           <?php echo $app("i18n")->get('Only alpha nummeric value is allowed'); ?>
                       </p>
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Label'); ?></label>
                       <input class="uk-width-1-1 uk-form-large" type="text" ref="label" bind="collection.label">
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Icon'); ?></label>
                       <div data-uk-dropdown="pos:'right-center', mode:'click'">
                           <a><img class="uk-display-block uk-margin uk-container-center" riot-src="{ collection.icon ? '<?php echo $app->pathToUrl('assets:app/media/icons/'); ?>'+collection.icon : '<?php echo $app->pathToUrl('collections:icon.svg'); ?>'}" alt="icon" width="100"></a>
                           <div class="uk-dropdown uk-dropdown-scrollable uk-dropdown-width-2">
                                <div class="uk-grid uk-grid-gutter">
                                    <div>
                                        <a class="uk-dropdown-close" onclick="{ selectIcon }" icon=""><img src="<?php echo $app->pathToUrl('collections:icon.svg'); ?>" width="30" icon=""></a>
                                    </div>
                                    <?php foreach ($app->helper("fs")->ls('*.svg', 'assets:app/media/icons') as $icon) { ?>
                                    <div>
                                        <a class="uk-dropdown-close" onclick="{ selectIcon }" icon="<?php echo  $icon->getFilename() ; ?>"><img src="<?php echo $app->pathToUrl($icon->getRealPath()); ?>" width="30" icon="<?php echo  $icon->getFilename() ; ?>"></a>
                                    </div>
                                    <?php } ?>
                                </div>
                           </div>
                       </div>
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Color'); ?></label>
                       <div class="uk-margin-small-top">
                           <field-colortag bind="collection.color" title="<?php echo $app("i18n")->get('Color'); ?>" size="20px"></field-colortag>
                       </div>
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Description'); ?></label>
                       <textarea class="uk-width-1-1 uk-form-large" name="description" bind="collection.description" bind-event="input" rows="5"></textarea>
                   </div>

                    <div class="uk-margin">
                        <field-boolean bind="collection.sortable" title="<?php echo $app("i18n")->get('Sortable entries'); ?>" label="<?php echo $app("i18n")->get('Custom sortable entries'); ?>"></field-boolean>
                    </div>

                    <div class="uk-margin">
                        <field-boolean bind="collection.in_menu" title="<?php echo $app("i18n")->get('Show in system menu'); ?>" label="<?php echo $app("i18n")->get('Show in system menu'); ?>"></field-boolean>
                    </div>

                </div>
            </div>

            <div class="uk-width-medium-3-4">

                <ul class="uk-tab uk-margin-large-bottom">
                    <li class="{ tab=='fields' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="fields">{ App.i18n.get('Fields') }</a></li>
                    <li class="{ tab=='auth' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="auth">{ App.i18n.get('Permissions') }</a></li>
                    <li class="{ tab=='other' && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleTab }" data-tab="other">{ App.i18n.get('Other') }</a></li>
                </ul>

                <div class="uk-form-row" show="{tab=='fields'}">

                    <cp-fieldsmanager bind="collection.fields" listoption="true" templates="{ templates }"></cp-fieldsmanager>

                </div>

                <div class="uk-form-row" show="{tab=='auth'}">

                    <div class="uk-panel-space">

                        <div class="uk-grid">
                            <div class="uk-width-1-3 uk-flex uk-flex-middle uk-flex-center">
                                <div class="uk-text-center">
                                    <p class="uk-text-uppercase uk-text-small uk-text-bold"><?php echo $app("i18n")->get('Public'); ?></p>
                                    <img class="uk-text-primary uk-svg-adjust" src="<?php echo $app->pathToUrl('assets:app/media/icons/globe.svg'); ?>" alt="icon" width="80" data-uk-svg>
                                </div>
                            </div>
                            <div class="uk-flex-item-1">
                                <div class="uk-margin uk-text-small">
                                    <strong class="uk-text-uppercase"><?php echo $app("i18n")->get('Collection'); ?></strong>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.{group}.collection_edit" label="<?php echo $app("i18n")->get('Edit Collection'); ?>"></field-boolean></div>
                                    <strong class="uk-text-uppercase uk-display-block uk-margin-top"><?php echo $app("i18n")->get('Entries'); ?></strong>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.public.entries_view" label="<?php echo $app("i18n")->get('View Entries'); ?>"></field-boolean></div>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.public.entries_edit" label="<?php echo $app("i18n")->get('Edit Entries'); ?>"></field-boolean></div>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.public.entries_create" label="<?php echo $app("i18n")->get('Create Entries'); ?>"></field-boolean></div>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.public.entries_delete" label="<?php echo $app("i18n")->get('Delete Entries'); ?>"></field-boolean></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="uk-panel uk-panel-box uk-panel-space uk-panel-card uk-margin" each="{group in aclgroups}">

                        <div class="uk-grid">
                            <div class="uk-width-1-3 uk-flex uk-flex-middle uk-flex-center">
                                <div class="uk-text-center">
                                    <p class="uk-text-uppercase uk-text-small">{ group }</p>
                                    <img class="uk-text-muted uk-svg-adjust" src="<?php echo $app->pathToUrl('assets:app/media/icons/accounts.svg'); ?>" alt="icon" width="80" data-uk-svg>
                                </div>
                            </div>
                            <div class="uk-flex-item-1">
                                <div class="uk-margin uk-text-small">
                                    <strong class="uk-text-uppercase"><?php echo $app("i18n")->get('Collection'); ?></strong>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.{group}.collection_edit" label="<?php echo $app("i18n")->get('Edit Collection'); ?>"></field-boolean></div>
                                    <strong class="uk-text-uppercase uk-display-block uk-margin-top"><?php echo $app("i18n")->get('Entries'); ?></strong>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.{group}.entries_view" label="<?php echo $app("i18n")->get('View Entries'); ?>"></field-boolean></div>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.{group}.entries_edit" label="<?php echo $app("i18n")->get('Edit Entries'); ?>"></field-boolean></div>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.{group}.entries_create" label="<?php echo $app("i18n")->get('Create Entries'); ?>"></field-boolean></div>
                                    <div class="uk-margin-top"><field-boolean bind="collection.acl.{group}.entries_delete" label="<?php echo $app("i18n")->get('Delete Entries'); ?>"></field-boolean></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="uk-margin uk-panel-box uk-panel-card">
                        <div class="uk-flex uk-flex-middle">
                            <div class="uk-flex-item-1"><span class="uk-badge uk-badge-success uk-text-uppercase uk-margin-small-bottom badge-rule">Create</span></div>
                            <div><field-boolean bind="collection.rules.create.enabled" label="<?php echo $app("i18n")->get('Enabled'); ?>"></field-boolean></div>
                        </div>
                        <field-code bind="rules.create" syntax="php" if="{collection.rules.create.enabled}" height="350"></field-code>
                    </div>

                    <div class="uk-margin uk-panel-box uk-panel-card">
                        <div class="uk-flex uk-flex-middle">
                            <div class="uk-flex-item-1"><span class="uk-badge uk-text-uppercase uk-margin-small-bottom badge-rule">Read</span></div>
                            <div><field-boolean bind="collection.rules.read.enabled" label="<?php echo $app("i18n")->get('Enabled'); ?>"></field-boolean></div>
                        </div>
                        <field-code bind="rules.read" syntax="php" if="{collection.rules.read.enabled}" height="350"></field-code>
                    </div>

                    <div class="uk-margin uk-panel-box uk-panel-card">
                        <div class="uk-flex uk-flex-middle">
                            <div class="uk-flex-item-1"><span class="uk-badge uk-badge-warning uk-text-uppercase uk-margin-small-bottom badge-rule">Update</span></div>
                            <div><field-boolean bind="collection.rules.update.enabled" label="<?php echo $app("i18n")->get('Enabled'); ?>"></field-boolean></div>
                        </div>
                        <field-code bind="rules.update" syntax="php" if="{collection.rules.update.enabled}" height="350"></field-code>
                    </div>

                    <div class="uk-margin uk-panel-box uk-panel-card">
                        <div class="uk-flex uk-flex-middle">
                            <div class="uk-flex-item-1"><span class="uk-badge uk-badge-danger uk-text-uppercase uk-margin-small-bottom badge-rule">Delete</span></div>
                            <div><field-boolean bind="collection.rules.delete.enabled" label="<?php echo $app("i18n")->get('Enabled'); ?>"></field-boolean></div>
                        </div>
                        <field-code bind="rules.delete" syntax="php" if="{collection.rules.delete.enabled}" height="350"></field-code>
                    </div>

                </div>


                <div class="uk-form-row" show="{tab=='other'}">

                    <div class="uk-form-row">
                        <strong class="uk-text-small uk-text-uppercase"><?php echo $app("i18n")->get('Content Preview'); ?></strong>
                        <div class="uk-margin-top"><field-boolean bind="collection.contentpreview.enabled" label="<?php echo $app("i18n")->get('Enabled'); ?>"></field-boolean></div>
                        <div class="uk-form-icon uk-form uk-width-1-1 uk-text-muted uk-margin-top" show="{collection.contentpreview && collection.contentpreview.enabled}">
                            <i class="uk-icon-globe"></i>
                            <input class="uk-width-1-1 uk-form-large uk-text-primary" type="url" placeholder="<?php echo $app("i18n")->get('http://...'); ?>" bind="collection.contentpreview.url">
                        </div>
                        <div class="uk-grid uk-margin-top" show="{collection.contentpreview && collection.contentpreview.enabled}">
                            <div class="uk-width-medium-2-3">
                                <div class="uk-form-icon uk-form uk-width-1-1 uk-text-muted">
                                    <i class="uk-icon-random"></i>
                                    <input class="uk-width-1-1 uk-form-large uk-text-primary" type="url" placeholder="<?php echo $app("i18n")->get('ws://...'); ?>" bind="collection.contentpreview.wsurl">
                                </div>
                            </div>
                            <div class="uk-width-medium-1-3">
                                <div class="uk-form-icon uk-form uk-width-1-1 uk-text-muted">
                                    <i class="uk-icon-crosshairs"></i>
                                    <input class="uk-width-1-1 uk-form-large uk-text-primary" type="text" placeholder="protocol-1, protocol-2" bind="collection.contentpreview.wsprotocols" title="Websocket Protocol">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="uk-margin-large-top" show="{ collection.fields.length }">

                    <div class="uk-button-group">
                        <button class="uk-button uk-button-large uk-button-primary"><?php echo $app("i18n")->get('Save'); ?></button>
                        <a class="uk-button uk-button-large" href="<?php $app->route('/collections/entries'); ?>/{ collection.name }" if="{ collection._id }"><?php echo $app("i18n")->get('Show entries'); ?></a>
                    </div>

                    <a class="uk-button uk-button-large uk-button-link" href="<?php $app->route('/collections'); ?>">
                        <span show="{ !collection._id }"><?php echo $app("i18n")->get('Cancel'); ?></span>
                        <span show="{ collection._id }"><?php echo $app("i18n")->get('Close'); ?></span>
                    </a>
                </div>

            </div>

        </div>

    </form>

    <style>

        .badge-rule {
            width: 50px;
        }

    </style>

    <script type="view/script">

        var $this = this, f;

        this.mixin(RiotBindMixin);

        this.collection = <?php echo  json_encode($collection) ; ?>;
        this.templates  = <?php echo  json_encode($templates) ; ?>;
        this.aclgroups  = <?php echo  json_encode($aclgroups) ; ?>;

        this.collection.rules = this.collection.rules || {
            "create" : {enabled:false},
            "read"   : {enabled:false},
            "update" : {enabled:false},
            "delete" : {enabled:false}
        };

        // hack to not break old installations - @todo remove in future
        'create,read,update,delete'.split(',').forEach(function(m){
            if (Array.isArray($this.collection.rules[m])) {
                $this.collection.rules[m] = {enabled:false};
            }
        })

        this.rules = <?php echo  json_encode($rules) ; ?>;

        this.tab = 'fields';

        if (!this.collection.acl) {
            this.collection.acl = {};
        }

        if (Array.isArray(this.collection.acl)) {
            this.collection.acl = {};
        }

        this.on('update', function(){

            // lock name if saved
            if (this.collection._id) {
                this.refs.name.disabled = true;
            }
        });

        this.on('mount', function(){

            this.trigger('update');

            // bind clobal command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {

                e.preventDefault();
                $this.submit();
                return false;
            });
        });

        toggleTab(e) {
            this.tab = e.target.getAttribute('data-tab');
        }

        selectIcon(e) {
            this.collection.icon = e.target.getAttribute('icon');
        }

        submit(e) {

            if (e) e.preventDefault();

            App.request('/collections/save_collection', {collection: this.collection, rules: this.rules}).then(function(collection) {

                App.ui.notify("Saving successful", "success");
                $this.collection = collection;
                $this.update();

            }).catch(function() {
                App.ui.notify("Saving failed.", "danger");
            });
        }

    </script>

</div>
