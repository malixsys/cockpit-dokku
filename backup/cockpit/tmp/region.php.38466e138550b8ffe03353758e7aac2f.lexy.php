<div>
    <ul class="uk-breadcrumb">
        <li><a href="<?php $app->route('/regions'); ?>"><?php echo $app("i18n")->get('Regions'); ?></a></li>
        <li class="uk-active"><span><?php echo $app("i18n")->get('Region'); ?></span></li>
    </ul>
</div>


<div class="uk-margin" riot-view>

    <form class="uk-form" onsubmit="{ submit }">

        <div class="uk-grid">

            <div class="uk-width-medium-1-4">

                <div class="uk-panel uk-panel-box uk-panel-card">

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Name'); ?></label>
                       <input class="uk-width-1-1 uk-form-large" type="text" ref="name" bind="region.name" pattern="[a-zA-Z0-9_]+" required>
                       <p class="uk-text-small uk-text-muted" if="{!region._id}">
                           <?php echo $app("i18n")->get('Only alpha nummeric value is allowed'); ?>
                       </p>
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Label'); ?></label>
                       <input class="uk-width-1-1 uk-form-large" type="text" ref="label" bind="region.label">
                   </div>

                   <div class="uk-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Icon'); ?></label>
                       <div data-uk-dropdown="pos:'right-center', mode:'click'">
                           <a><img class="uk-display-block uk-margin uk-container-center" riot-src="{ region.icon ? '<?php echo $app->pathToUrl('assets:app/media/icons/'); ?>'+region.icon : '<?php echo $app->pathToUrl('regions:icon.svg'); ?>'}" alt="icon" width="100"></a>
                           <div class="uk-dropdown uk-dropdown-scrollable uk-dropdown-width-2">
                                <div class="uk-grid uk-grid-gutter">
                                    <div>
                                        <a class="uk-dropdown-close" onclick="{ selectIcon }" icon=""><img src="<?php echo $app->pathToUrl('regions:icon.svg'); ?>" width="30" icon=""></a>
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
                           <field-colortag bind="region.color" title="<?php echo $app("i18n")->get('Color'); ?>" size="20px"></field-colortag>
                       </div>
                   </div>

                   <div class="uk-grid-margin">
                       <label class="uk-text-small"><?php echo $app("i18n")->get('Description'); ?></label>
                       <textarea class="uk-width-1-1 uk-form-large" name="description" bind="region.description" rows="5"></textarea>
                   </div>
               </div>

            </div>

            <div class="uk-width-medium-3-4">

                <div class="uk-form-row">

                    <ul class="uk-tab uk-flex uk-margin">
                        <li class="{ view==='fields' ? 'uk-active':'' }" data-view="fields"><a onclick="{ toggleview }"><?php echo $app("i18n")->get('Fields'); ?></a></li>
                        <li class="{ view==='template' ? 'uk-active':'' }" data-view="template"><a onclick="{ toggleview }"><?php echo $app("i18n")->get('Template'); ?></a></li>
                        <li class="{ view==='acl' ? 'uk-active':'' }" data-view="acl"><a onclick="{ toggleview }"><?php echo $app("i18n")->get('Permissions'); ?></a></li>
                    </ul>

                    <div class="uk-margin-large-top" show="{ view==='fields' }">

                        <cp-fieldsmanager bind="region.fields"></cp-fieldsmanager>

                    </div>

                    <div class="uk-margin-large-top" show="{ view==='template' }">
                        <field-code bind="region.template" syntax="php" height="400"></field-code>
                    </div>

                    <div class="uk-margin-top" show="{ view==='acl' }">

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
                                        <strong class="uk-text-uppercase"><?php echo $app("i18n")->get('Region'); ?></strong>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.public.form" label="<?php echo $app("i18n")->get('Form'); ?>"></field-boolean></div>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.public.edit" label="<?php echo $app("i18n")->get('Edit Region'); ?>"></field-boolean></div>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.public.render" label="<?php echo $app("i18n")->get('Render Region'); ?>"></field-boolean></div>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.public.data" label="<?php echo $app("i18n")->get('Get Region Data'); ?>"></field-boolean></div>
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
                                        <strong class="uk-text-uppercase"><?php echo $app("i18n")->get('Region'); ?></strong>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.{group}.form" label="<?php echo $app("i18n")->get('Form'); ?>"></field-boolean></div>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.{group}.edit" label="<?php echo $app("i18n")->get('Edit Region'); ?>"></field-boolean></div>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.{group}.render" label="<?php echo $app("i18n")->get('Render Region'); ?>"></field-boolean></div>
                                        <div class="uk-margin-top"><field-boolean bind="region.acl.{group}.data" label="<?php echo $app("i18n")->get('Get Region Data'); ?>"></field-boolean></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="uk-margin-large-top">

                        <div class="uk-button-group">
                            <button class="uk-button uk-button-large uk-button-primary"><?php echo $app("i18n")->get('Save'); ?></button>
                            <a class="uk-button uk-button-large" href="<?php $app->route('/regions/form'); ?>/{ region.name }" if="{ region._id }"><?php echo $app("i18n")->get('Show form'); ?></a>
                        </div>

                        <a class="uk-button uk-button-large uk-button-link" href="<?php $app->route('/regions'); ?>">
                            <span show="{ !region._id }"><?php echo $app("i18n")->get('Cancel'); ?></span>
                            <span show="{ region._id }"><?php echo $app("i18n")->get('Close'); ?></span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </form>

    <script type="view/script">

        var $this = this;

        this.mixin(RiotBindMixin);

        this.view = 'fields';

        this.region = <?php echo  json_encode($region) ; ?>;
        this.aclgroups  = <?php echo  json_encode($aclgroups) ; ?>;

        if (!this.region.acl) {
            this.region.acl = {};
        }

        if (Array.isArray(this.region.acl)) {
            this.region.acl = {};
        }

        this.on('mount', function(){

            this.trigger('update');

            // bind clobal command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {

                e.preventDefault();
                $this.submit();
                return false;
            });
        });

        this.on('update', function(){

            // lock name if saved
            if (this.region._id) {
                this.refs.name.disabled = true;
            }
        });

        selectIcon(e) {
            this.region.icon = e.target.getAttribute('icon');
        }

        submit(e) {

            if(e) e.preventDefault();

            var region = this.region;

            App.callmodule('regions:saveRegion', [this.region.name, region]).then(function(data) {

                if (data.result) {

                    App.ui.notify("Saving successful", "success");
                    $this.region = data.result;
                    $this.update();

                } else {

                    App.ui.notify("Saving failed.", "danger");
                }
            });
        }

        toggleview(e) {
            this.view = e.target.parentElement.getAttribute('data-view');
        }

    </script>
</div>
