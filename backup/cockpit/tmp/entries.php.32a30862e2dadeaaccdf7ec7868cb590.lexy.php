
<?php if ($form['color']) { ?>
<style>
    .app-header { border-top: 8px <?php echo  $form['color'] ; ?> solid; }
</style>
<?php } ?>

<div>
    <ul class="uk-breadcrumb">
        <li><a href="<?php $app->route('/forms'); ?>"><?php echo $app("i18n")->get('Forms'); ?></a></li>
        <li class="uk-active" data-uk-dropdown>

            <a><i class="uk-icon-bars"></i> <?php echo  htmlspecialchars(@$form['label'] ? $form['label']:$form['name']) ; ?></a>

            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-nav-header"><?php echo $app("i18n")->get('Actions'); ?></li>
                    <li><a href="<?php $app->route('/forms/form/'.$form['name']); ?>"><?php echo $app("i18n")->get('Edit'); ?></a></li>
                    <li class="uk-nav-divider"></li>
                    <li class="uk-text-truncate"><a href="<?php $app->route('/forms/export/'.$form['name']); ?>" download="<?php echo  $form['name'] ; ?>.form.json"><?php echo $app("i18n")->get('Export entries'); ?></a></li>
                </ul>
            </div>

        </li>
    </ul>

</div>

<div riot-view>

    <div class="uk-margin uk-text-muted uk-text-center" if="{ready && entries.length}">

        <img class="uk-svg-adjust" src="<?php echo $app->pathToUrl($form['icon'] ? 'assets:app/media/icons/'.$form['icon']:'forms:icon.svg'); ?>" width="50" alt="icon" data-uk-svg>
        <?php if ($form['description']) { ?>
        <div class="uk-container-center uk-margin-top uk-width-medium-1-2">
            <?php echo  htmlspecialchars($form['description']) ; ?>
        </div>
        <?php } ?>
    </div>

    <div show="{ ready }">

        <div class="uk-width-medium-1-3 uk-viewport-height-1-2 uk-container-center uk-text-center uk-flex uk-flex-middle" if="{ready && !entries.length}">

            <div class="uk-animation-scale uk-width-1-1 uk-text-muted">

                <img class="uk-svg-adjust" src="<?php echo $app->pathToUrl($form['icon'] ? 'assets:app/media/icons/'.$form['icon']:'forms:icon.svg'); ?>" width="50" alt="icon" data-uk-svg>
                <?php if ($form['description']) { ?>
                <div class="uk-margin-top uk-text-small">
                    <?php echo  htmlspecialchars($form['description']) ; ?>
                </div>
                <?php } ?>
                <hr>
                <span class="uk-text-large"><?php echo $app("i18n")->get('No entries'); ?></span>

            </div>

        </div>

        <div class="uk-clearfix uk-margin-large-top" if="{ entries.length }">

            <div class="uk-float-right uk-animation-fade" if="{ selected.length }">

                <a class="uk-button uk-button-large uk-button-danger" onclick="{ removeselected }">
                    <?php echo $app("i18n")->get('Delete'); ?> <span class="uk-badge uk-badge-contrast uk-margin-small-left">{ selected.length }</span>
                </a>

            </div>

        </div>

        <table class="uk-table uk-table-border uk-table-striped uk-margin-top" if="{ entries.length }">
            <thead>
                <tr>
                    <th width="20"><input class="uk-checkbox" type="checkbox" data-check="all"></th>
                    <th class="uk-text-small"><?php echo $app("i18n")->get('Entry'); ?></th>
                    <th width="100" class="uk-text-small"><?php echo $app("i18n")->get('Created'); ?></th>
                    <th width="20"></th>
                </tr>
            </thead>
            <tbody>
                <tr each="{entry, idx in entries}">
                    <td width="20"><input class="uk-checkbox" type="checkbox" data-check data-id="{ entry._id }"></td>
                    <td>
                        <div class="uk-text-small uk-margin-small-top" each="{ value, name in entry.data }">
                            <strong>{name}:</strong>
                            <div>
                                {value}
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="uk-text-muted">{ App.Utils.dateformat( new Date( 1000 * entry._modified )) }</span>
                    </td>
                    <td>
                        <a class="uk-text-danger" onclick="{ parent.remove }" title="<?php echo $app("i18n")->get('Delete'); ?>"><i class="uk-icon-trash-o"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


    <script type="view/script">

        var $this = this, $root = App.$(this.root);

        this.ready      = false;
        this.form       = <?php echo  json_encode($form) ; ?>;
        this.loadmore   = false;
        this.entries    = [];

        this.selected = [];

        this.on('mount', function(){

            $root.on('click', '[data-check]', function() {

                if (this.getAttribute('data-check') == 'all') {
                    $root.find('[data-check][data-id]').prop('checked', this.checked);
                }

                $this.checkselected();

                $this.update();
            });

            this.load();

        });

        remove(e, entry, idx) {

            entry = e.item.entry
            idx   = e.item.idx;

            App.ui.confirm("Are you sure?", function() {

                App.callmodule('forms:remove', [this.form.name, {'_id':entry._id}]).then(function(data) {

                    App.ui.notify("Entry removed", "success");

                    $this.entries.splice(idx, 1);

                    $this.update();

                    $this.checkselected(true);
                });

            }.bind(this));
        }

        removeselected() {

            if (this.selected.length) {

                App.ui.confirm("Are you sure?", function() {

                    var promises = [];

                    this.entries = this.entries.filter(function(entry, yepp){

                        yepp = ($this.selected.indexOf(entry._id) === -1);

                        if (!yepp) {
                            promises.push(App.callmodule('forms:remove', [$this.form.name, {'_id':entry._id}]));
                        }

                        return yepp;
                    });

                    Promise.all(promises).then(function(){
                        App.ui.notify("Entries removed", "success");
                    });

                    this.update();
                    this.checkselected(true);

                }.bind(this));
            }
        }

        load() {

            var limit=50, options = { sort: {'_created': -1}, limit: limit, skip: (this.entries.length || 0) };

            return App.callmodule('forms:find', [this.form.name, options]).then(function(data){

                this.entries = this.entries.concat(data.result);

                this.ready    = true;
                this.loadmore = data.result.length && data.result.length == limit;

                this.checkselected();

                this.update();

            }.bind(this))
        }

        checkselected(update) {

            var checkboxes = $root.find('[data-check][data-id]'),
                selected   = checkboxes.filter(':checked');

            this.selected = [];

            if (selected.length) {

                selected.each(function(){
                    $this.selected.push(App.$(this).attr('data-id'));
                });
            }

            $root.find('[data-check="all"]').prop('checked', checkboxes.length === selected.length);

            if (update) {
                this.update();
            }
        }

    </script>

</div>
