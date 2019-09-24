<?php

/**
 * @package content
 */

/**
 * Developers can create new Frontend pages from this class. It provides
 * an index view of all the pages in this Symphony install as well as the
 * forms for the creation/editing of a Page
 */

class contentBlueprintsPages extends AdministrationPage
{
    protected $_hilights = array();

    /**
     * The Pages page has /action/id/flag/ context.
     * eg. /edit/1/saved/
     *
     * @param array $context
     * @param array $parts
     * @return array
     */
    public function parseContext(array &$context, array $parts)
    {
        // Order is important!
        $params = array_fill_keys(array('action', 'id', 'flag'), null);

        if (isset($parts[2])) {
            $extras = preg_split('/\//', $parts[2], -1, PREG_SPLIT_NO_EMPTY);
            list($params['action'], $params['id'], $params['flag']) = array_replace([null,null,null], $extras);
            $params['id'] = (int)$params['id'];
        }

        $context = array_filter($params);
    }

    public function insertBreadcrumbsUsingPageIdentifier($page_id, $preserve_last = true)
    {
        if ($page_id == 0) {
            return $this->insertBreadcrumbs(
                array(
                    Widget::Anchor(
                        Widget::SVGIcon('arrow') . __('Pages'),
                        SYMPHONY_URL . '/blueprints/pages/'
                    )
                )
            );
        }

        $pages = PageManager::resolvePage($page_id, 'handle');

        foreach ($pages as &$page) {
            // If we are viewing the Page Editor, the Breadcrumbs should link
            // to the parent's Page Editor.
            if ($this->_context['action'] === 'edit') {
                $page = Widget::Anchor(
                    General::sanitize(PageManager::fetchTitleFromHandle($page)),
                    SYMPHONY_URL . '/blueprints/pages/edit/' . PageManager::fetchIDFromHandle($page) . '/'
                );

                // If the pages index is nested, the Breadcrumb should link to the
                // Pages Index filtered by parent
            } elseif (Symphony::Configuration()->get('pages_table_nest_children', 'symphony') == 'yes') {
                $page = Widget::Anchor(
                    General::sanitize(PageManager::fetchTitleFromHandle($page)),
                    SYMPHONY_URL . '/blueprints/pages/?parent=' . PageManager::fetchIDFromHandle($page)
                );

                // If there is no nesting on the Pages Index, the breadcrumb is
                // not a link, just plain text
            } else {
                $page = new XMLElement('span', General::sanitize(PageManager::fetchTitleFromHandle($page)));
            }
        }

        if (!$preserve_last) {
            array_pop($pages);
        }

        $this->insertBreadcrumbs(array_merge(
            array(
                Widget::Anchor(
                    Widget::SVGIcon('arrow') . __('Pages'),
                    SYMPHONY_URL . '/blueprints/pages/'
                )
            ),
            $pages
        ));
    }

    public function __viewIndex()
    {
        $this->setPageType('table');
        $this->setTitle(__('%1$s &ndash; %2$s', array(__('Pages'), __('Symphony'))));

        $nesting = Symphony::Configuration()->get('pages_table_nest_children', 'symphony') == 'yes';

        if ($nesting && isset($_GET['parent']) && is_numeric($_GET['parent'])) {
            $parent = PageManager::fetchPageByID((int)$_GET['parent'], array('title', 'id'));
        }

        $this->appendSubheading(
            isset($parent)
                ? General::sanitize($parent['title'])
                : __('Pages'),
            Widget::Anchor(
                Widget::SVGIcon('add'),
                Administration::instance()->getCurrentPageURL() . 'new/' . ($nesting && isset($parent) ? "?parent={$parent['id']}" : null),
                __('Create a new page'),
                'create button',
                null,
                array('accesskey' => 'c')
            )
        );

        if (isset($parent)) {
            $this->insertBreadcrumbsUsingPageIdentifier($parent['id'], false);
        }

        $aTableHead = array(
            array(__('Name'), 'col'),
            array(__('Template'), 'col'),
            array('<abbr title="' . __('Universal Resource Locator') . '">' . __('URL') . '</abbr>', 'col'),
            array(__('Parameters'), 'col'),
            array(__('Type'), 'col')
        );
        $aTableBody = array();

        $pagesQuery = (new PageManager)->select()->includeTypes();
        if ($nesting) {
            $aTableHead[] = array(__('Children'), 'col');
            $pagesQuery->where(['parent' => isset($parent) ? $parent['id'] : null]);
        }

        $pages = $pagesQuery->execute()->rows();

        if (empty($pages)) {
            $aTableBody = array(Widget::TableRow(array(
                Widget::TableData(__('None found.'), 'inactive', null, count($aTableHead))
            ), 'odd'));
        } else {
            foreach ($pages as $page) {
                $class = array();

                $page_title = ($nesting ? $page['title'] : PageManager::resolvePageTitle($page['id']));
                $page_url = URL . '/' . PageManager::resolvePagePath($page['id']) . '/';
                $page_edit_url = Administration::instance()->getCurrentPageURL() . 'edit/' . $page['id'] . '/';
                $page_template = PageManager::createFilePath($page['path'], $page['handle']);

                $col_title = Widget::TableData(
                    Widget::Anchor(General::sanitize($page_title), $page_edit_url, $page['handle'])
                );
                $col_title->appendChild(
                    Widget::Label(
                        __('Select Page %s', [General::sanitize($page_title)]),
                        null,
                        'accessible',
                        null,
                        ['for' => 'page-' . $page['id']]
                    )
                );
                $col_title->appendChild(Widget::Input('items['.$page['id'].']', 'on', 'checkbox', array(
                    'id' => 'page-' . $page['id']
                )));
                $col_title->setAttribute('data-title', __('Name'));

                $col_template = Widget::TableData($page_template . '.xsl');
                $col_template->setAttribute('data-title', __('Template'));

                $col_url = Widget::TableData(
                    Widget::Anchor(
                        $page_url,
                        $page_url,
                        null,
                        null,
                        null,
                        array('target' => '_blank')
                    )
                );
                $col_url->setAttribute('data-title', __('URL'));

                if ($page['params']) {
                    $col_params = Widget::TableData(trim(General::sanitize($page['params']), '/'));
                } else {
                    $col_params = Widget::TableData(__('None'), 'inactive');
                }
                $col_params->setAttribute('data-title', __('Parameters'));

                if (!empty($page['type'])) {
                    $col_types = Widget::TableData(implode(', ', array_map(['General', 'sanitize'], $page['type'])));
                } else {
                    $col_types = Widget::TableData(__('None'), 'inactive');
                }
                $col_types->setAttribute('data-title', __('Type'));

                if (in_array($page['id'], $this->_hilights)) {
                    $class[] = 'failed';
                }

                $columns = array($col_title, $col_template, $col_url, $col_params, $col_types);

                if ($nesting) {
                    if (PageManager::hasChildPages($page['id'])) {
                        $col_children = Widget::TableData(
                            Widget::Anchor(PageManager::getChildPagesCount($page['id']) . ' &rarr;',
                            SYMPHONY_URL . '/blueprints/pages/?parent=' . $page['id'])
                        );
                    } else {
                        $col_children = Widget::TableData(__('None'), 'inactive');
                    }

                    $columns[] = $col_children;
                }

                $aTableBody[] = Widget::TableRow(
                    $columns,
                    implode(' ', $class)
                );
            }
        }

        $table = Widget::Table(
            Widget::TableHead($aTableHead), null,
            Widget::TableBody($aTableBody), 'orderable selectable',
            null, array('role' => 'directory', 'aria-labelledby' => 'symphony-subheading', 'data-interactive' => 'data-interactive')
        );

        $this->Form->appendChild($table);

        $tableActions = new XMLElement('div');
        $tableActions->setAttribute('class', 'actions');

        $options = array(
            array(null, false, __('With Selected...')),
            array('delete', false, __('Delete'), 'confirm', null, array(
                'data-message' => __('Are you sure you want to delete the selected pages?')
            ))
        );

        /**
         * Allows an extension to modify the existing options for this page's
         * With Selected menu. If the `$options` parameter is an empty array,
         * the 'With Selected' menu will not be rendered.
         *
         * @delegate AddCustomActions
         * @since Symphony 2.3.2
         * @param string $context
         * '/blueprints/pages/'
         * @param array $options
         *  An array of arrays, where each child array represents an option
         *  in the With Selected menu. Options should follow the same format
         *  expected by `Widget::__SelectBuildOption`. Passed by reference.
         */
        Symphony::ExtensionManager()->notifyMembers('AddCustomActions', '/blueprints/pages/', array(
            'options' => &$options
        ));

        if (!empty($options)) {
            $tableActions->appendChild(Widget::Apply($options));
            $this->Form->appendChild($tableActions);
        }
    }

    public function __viewNew()
    {
        $this->__viewEdit();
    }

    public function __viewEdit()
    {
        $this->setPageType('form');
        $fields = array("title"=>null, "handle"=>null, "parent"=>null, "params"=>null, "type"=>null, "data_sources"=>null);
        $existing = $fields;
        $canonical_link = '/blueprints/pages/';
        $nesting = (Symphony::Configuration()->get('pages_table_nest_children', 'symphony') == 'yes');

        // Verify page exists:
        if ($this->_context['action'] === 'edit') {
            if (!$page_id = $this->_context['id']) {
                redirect(SYMPHONY_URL . '/blueprints/pages/');
            }

            $existing = PageManager::fetchPageByID($page_id);
            $canonical_link .= 'edit/' . $page_id . '/';

            if (!$existing) {
                Administration::instance()->errorPageNotFound();
            } else {
                $existing['type'] = PageManager::fetchPageTypes($page_id);
            }
        } else {
            $canonical_link .= 'new/';
        }

        // Status message:
        if (isset($this->_context['flag'])) {
            $flag = $this->_context['flag'];
            $parent_link_suffix = $message = '';
            $time = Widget::Time();

            if (isset($_REQUEST['parent']) && is_numeric($_REQUEST['parent'])) {
                $parent_link_suffix = "?parent=" . $_REQUEST['parent'];
            } elseif ($nesting && isset($existing) && !is_null($existing['parent'])) {
                $parent_link_suffix = '?parent=' . $existing['parent'];
            }

            switch ($flag) {
                case 'saved':
                    $message = __('Page updated at %s.', array($time->generate()));
                    break;
                case 'created':
                    $message = __('Page created at %s.', array($time->generate()));
            }

            $this->pageAlert(
                $message
                . ' <a href="' . SYMPHONY_URL . '/blueprints/pages/new/' . $parent_link_suffix . '" accesskey="c">'
                . __('Create another?')
                . '</a> <a href="' . SYMPHONY_URL . '/blueprints/pages/" accesskey="a">'
                . __('View all Pages')
                . '</a>',
                Alert::SUCCESS
            );
        }

        // Find values:
        if (isset($_POST['fields'])) {
            $fields = $_POST['fields'];
        } elseif ($this->_context['action'] === 'edit') {
            $fields = $existing;

            if (!is_null($fields['type'])) {
                $fields['type'] = implode(', ', $fields['type']);
            }

            $fields['data_sources'] = preg_split('/,/i', $fields['data_sources'], -1, PREG_SPLIT_NO_EMPTY);
            $fields['events'] = preg_split('/,/i', $fields['events'], -1, PREG_SPLIT_NO_EMPTY);
        } elseif (isset($_REQUEST['parent']) && is_numeric($_REQUEST['parent'])) {
            $fields['parent'] = $_REQUEST['parent'];
            $canonical_link .= '?parent=' . urlencode($_REQUEST['parent']);
        }

        $title = $fields['title'];

        if (trim($title) == '') {
            $title = $existing['title'];
        }

        $this->setTitle(
            __(
                $title
                    ? '%1$s &ndash; %2$s &ndash; %3$s'
                    : '%2$s &ndash; %3$s',
                array(General::sanitize($title), __('Pages'), __('Symphony'))
            )
        );
        $this->addElementToHead(new XMLElement('link', null, array(
            'rel' => 'canonical',
            'href' => SYMPHONY_URL . $canonical_link,
        )));

        $page_id = isset($page_id) ? $page_id : null;

        if (!empty($title) && !is_null($page_id)) {
            $page_url = URL . '/' . PageManager::resolvePagePath($page_id) . '/';

            $this->appendSubheading(
                General::sanitize($title),
                [
                    Widget::Anchor(
                        Widget::SVGIcon('view'),
                        $page_url,
                        __('View Page on Frontend'),
                        'button',
                        null,
                        ['target' => '_blank','accesskey' => 'v']
                    )
                ]
            );
        } else {
            $this->appendSubheading(!empty($title) ? General::sanitize($title) : __('Untitled'));
        }

        if (isset($page_id)) {
            $this->insertBreadcrumbsUsingPageIdentifier($page_id, false);
        } else {
            $_GET['parent'] = isset($_GET['parent']) ? $_GET['parent'] : null;
            $this->insertBreadcrumbsUsingPageIdentifier((int)$_GET['parent'], true);
        }

        // $formInner = new XMLElement('div', null, array('class' => 'inner'));

        // Actions ------------------------------------------------------------
        $div = new XMLElement('div');
        $div->setAttribute('class', 'actions');

        $saveBtn = new XMLElement('button', Widget::SVGIcon('save'));
        $saveBtn->setAttributeArray(array('name' => 'action[save]', 'class' => 'button', 'title' => $this->_context['action'] === 'edit' ? __('Save Changes') : __('Create Page'), 'type' => 'submit', 'accesskey' => 's'));
        $div->appendChild($saveBtn);

        if ($this->_context['action'] === 'edit') {
            $button = new XMLElement('button', Widget::SVGIcon('delete'));
            $button->setAttributeArray(array('name' => 'action[delete]', 'class' => 'button confirm delete', 'title' => __('Delete this page'), 'accesskey' => 'd', 'data-message' => __('Are you sure you want to delete this page?')));
            $div->appendChild($button);
        }

        $this->ContentsActions->appendChild($div);

        // Title --------------------------------------------------------------

        $fieldset = new XMLElement('fieldset');
        $fieldset->setAttribute('class', 'settings');
        $fieldset->appendChild(new XMLElement('legend', __('Page Settings')));

        $label = Widget::Label(__('Name'));
        $label->appendChild(Widget::Input(
            'fields[title]', General::sanitize($fields['title'])
        ));

        if (isset($this->_errors['title'])) {
            $label = Widget::Error($label, $this->_errors['title']);
        }

        $fieldset->appendChild($label);

        // Handle -------------------------------------------------------------

        $group = new XMLElement('div');
        $group->setAttribute('class', 'two columns');
        $column = new XMLElement('div');
        $column->setAttribute('class', 'column');

        $label = Widget::Label(__('Handle'));
        $label->appendChild(Widget::Input(
            'fields[handle]', $fields['handle']
        ));

        if (isset($this->_errors['handle'])) {
            $label = Widget::Error($label, $this->_errors['handle']);
        }

        $column->appendChild($label);

        // Parent ---------------------------------------------------------

        $label = Widget::Label(__('Parent Page'));

        $pages = (new PageManager)
            ->select(['id'])
            ->where(['id' => ['!=' => General::intval($page_id)]])
            ->sort('title')
            ->execute()
            ->rows();

        $options = array(
            array('', false, '/')
        );

        if (!empty($pages)) {
            foreach ($pages as $page) {
                $options[] = array(
                    $page['id'], $fields['parent'] == $page['id'],
                    '/' . PageManager::resolvePagePath($page['id'])
                );
            }

            usort($options, array($this, '__compare_pages'));
        }

        $label->appendChild(Widget::Select(
            'fields[parent]', $options
        ));
        $column->appendChild($label);
        $group->appendChild($column);

        // Parameters ---------------------------------------------------------

        $column = new XMLElement('div');
        $column->setAttribute('class', 'column');

        $label = Widget::Label(__('Parameters'));
        $label->appendChild(Widget::Input(
            'fields[params]', $fields['params'], 'text', array('placeholder' => 'param1/param2')
        ));
        $column->appendChild($label);

        // Type -----------------------------------------------------------

        $label = Widget::Label(__('Type'));
        $label->appendChild(Widget::Input('fields[type]', $fields['type']));

        if (isset($this->_errors['type'])) {
            $label = Widget::Error($label, $this->_errors['type']);
        }

        $column->appendChild($label);

        $tags = new XMLElement('ul');
        $tags->setAttribute('class', 'tags');
        $tags->setAttribute('data-interactive', 'data-interactive');

        $types = PageManager::fetchAvailablePageTypes();

        foreach ($types as $type) {
            $tags->appendChild(new XMLElement('li', General::sanitize($type)));
        }

        $column->appendChild($tags);
        $group->appendChild($column);
        $fieldset->appendChild($group);
        $this->Form->appendChild($fieldset);

        // Events -------------------------------------------------------------

        $fieldset = new XMLElement('fieldset');
        $fieldset->setAttribute('class', 'settings');
        $fieldset->appendChild(new XMLElement('legend', __('Page Resources')));

        $group = new XMLElement('div');
        $group->setAttribute('class', 'two columns');

        $label = Widget::Label(__('Events'));
        $label->setAttribute('class', 'column');

        $events = ResourceManager::fetch(ResourceManager::RESOURCE_TYPE_EVENT, array(), array(), 'name ASC');
        $options = array();

        if (is_array($events) && !empty($events)) {
            if (!isset($fields['events'])) {
                $fields['events'] = array();
            }

            foreach ($events as $name => $about) {
                $options[] = array(
                    $name, in_array($name, $fields['events']), $about['name']
                );
            }
        }

        $label->appendChild(Widget::Select('fields[events][]', $options, array('multiple' => 'multiple')));
        $group->appendChild($label);

        // Data Sources -------------------------------------------------------

        $label = Widget::Label(__('Data Sources'));
        $label->setAttribute('class', 'column');

        $datasources = ResourceManager::fetch(ResourceManager::RESOURCE_TYPE_DS, array(), array(), 'name ASC');
        $options = array();

        if (is_array($datasources) && !empty($datasources)) {
            if (!isset($fields['data_sources'])) {
                $fields['data_sources'] = array();
            }

            foreach ($datasources as $name => $about) {
                $options[] = array(
                    $name, in_array($name, $fields['data_sources']), $about['name']
                );
            }
        }

        $label->appendChild(Widget::Select('fields[data_sources][]', $options, array('multiple' => 'multiple')));
        $group->appendChild($label);
        $fieldset->appendChild($group);
        $this->Form->appendChild($fieldset);

        // Controls -----------------------------------------------------------

        /**
         * After all Page related Fields have been added to the DOM, just before the
         * actions.
         *
         * @delegate AppendPageContent
         * @param string $context
         *  '/blueprints/pages/'
         * @param XMLElement $form
         * @param array $fields
         * @param array $errors
         */
        Symphony::ExtensionManager()->notifyMembers(
            'AppendPageContent',
            '/blueprints/pages/',
            [
                'form'      => &$this->Form,
                'fields'    => &$fields,
                'errors'    => $this->_errors,
            ]
        );

        $this->Header->setAttribute('class', 'spaced-bottom');
        $this->Contents->setAttribute('class', 'centered-content');
        // $this->Form->appendChild($formInner);

        if (isset($_REQUEST['parent']) && is_numeric($_REQUEST['parent'])) {
            $this->Form->appendChild(new XMLElement('input', null, array('type' => 'hidden', 'name' => 'parent', 'value' => $_REQUEST['parent'])));
        }
    }

    public function __compare_pages($a, $b)
    {
        return strnatcasecmp($a[2], $b[2]);
    }

    public function __actionIndex()
    {
        $checked = (is_array($_POST['items'])) ? array_keys($_POST['items']) : null;

        if (is_array($checked) && !empty($checked)) {
            /**
             * Extensions can listen for any custom actions that were added
             * through `AddCustomPreferenceFieldsets` or `AddCustomActions`
             * delegates.
             *
             * @delegate CustomActions
             * @since Symphony 2.3.2
             * @param string $context
             *  '/blueprints/pages/'
             * @param array $checked
             *  An array of the selected rows. The value is usually the ID of the
             *  the associated object.
             */
            Symphony::ExtensionManager()->notifyMembers('CustomActions', '/blueprints/pages/', array(
                'checked' => $checked
            ));

            switch ($_POST['with-selected']) {
                case 'delete':
                    $this->__actionDelete($checked, SYMPHONY_URL . '/blueprints/pages/');
                    break;
            }
        }
    }

    public function __actionNew()
    {
        $this->__actionEdit();
    }

    public function __actionEdit()
    {
        if ($this->_context['action'] !== 'new' && !$page_id = $this->_context['id']) {
            redirect(SYMPHONY_URL . '/blueprints/pages/');
        }

        $parent_link_suffix = null;

        if (isset($_REQUEST['parent']) && is_numeric($_REQUEST['parent'])) {
            $parent_link_suffix = '?parent=' . $_REQUEST['parent'];
        }

        if (@array_key_exists('delete', $_POST['action'])) {
            $this->__actionDelete($page_id, SYMPHONY_URL  . '/blueprints/pages/' . $parent_link_suffix);
        }

        if (@array_key_exists('save', $_POST['action'])) {
            $fields = $_POST['fields'];
            $this->_errors = array();

            if (!isset($fields['title']) || trim($fields['title']) == '') {
                $this->_errors['title'] = __('This is a required field');
            }

            if (trim($fields['type']) != '' && preg_match('/(index|404|403)/i', $fields['type'])) {
                $types = preg_split('/\s*,\s*/', strtolower($fields['type']), -1, PREG_SPLIT_NO_EMPTY);

                if (in_array('index', $types) && PageManager::hasPageTypeBeenUsed($page_id, 'index')) {
                    $this->_errors['type'] = __('An index type page already exists.');
                } elseif (in_array('404', $types) && PageManager::hasPageTypeBeenUsed($page_id, '404')) {
                    $this->_errors['type'] = __('A 404 type page already exists.');
                } elseif (in_array('403', $types) && PageManager::hasPageTypeBeenUsed($page_id, '403')) {
                    $this->_errors['type'] = __('A 403 type page already exists.');
                }
            }

            if (trim($fields['handle']) == '') {
                $fields['handle'] = $fields['title'];
            }

            $fields['handle'] = PageManager::createHandle($fields['handle']);

            if (empty($fields['handle']) && !isset($this->_errors['title'])) {
                $this->_errors['handle'] = __('Please ensure handle contains at least one Latin-based character.');
            }

            /**
             * Just after the Symphony validation has run, allows Developers
             * to run custom validation logic on a Page
             *
             * @delegate PagePostValidate
             * @since Symphony 2.2
             * @param string $context
             * '/blueprints/pages/'
             * @param array $fields
             *  The `$_POST['fields']` array. This should be read-only and not changed
             *  through this delegate.
             * @param array $errors
             *  An associative array of errors, with the key matching a key in the
             *  `$fields` array, and the value being the string of the error. `$errors`
             *  is passed by reference.
             */
            Symphony::ExtensionManager()->notifyMembers(
                'PagePostValidate',
                '/blueprints/pages/',
                ['fields' => $fields, 'errors' => &$this->_errors]
            );

            if (empty($this->_errors)) {

                if ($fields['params']) {
                    $fields['params'] = trim(preg_replace('@\/{2,}@', '/', $fields['params']), '/');
                }

                // Clean up type list
                $types = preg_split('/\s*,\s*/', $fields['type'], -1, PREG_SPLIT_NO_EMPTY);
                $types = array_map('trim', $types);
                unset($fields['type']);

                $fields['parent'] = ($fields['parent'] != __('None') ? $fields['parent'] : null);
                $fields['data_sources'] = is_array($fields['data_sources']) ? implode(',', $fields['data_sources']) : null;
                $fields['events'] = is_array($fields['events']) ? implode(',', $fields['events']) : null;
                $fields['path'] = null;

                if ($fields['parent']) {
                    $fields['path'] = PageManager::resolvePagePath((integer)$fields['parent']);
                }

                // Check for duplicates:
                $current = PageManager::fetchPageByID($page_id);

                if (empty($current)) {
                    $fields['sortorder'] = PageManager::fetchNextSortOrder();
                }

                $pageQuery = (new PageManager)
                    ->select()
                    ->handle($fields['handle'])
                    ->path($fields['path'])
                    ->limit(1);

                if (!empty($current)) {
                    $pageQuery->where(['id' => ['!=' => $page_id]]);
                }

                $duplicate = $pageQuery->execute()->next();

                // If duplicate
                if ($duplicate) {
                    $this->_errors['handle'] = __('A page with that handle already exists');

                    // Create or move files:
                } else {
                    // New page?
                    if (empty($current)) {
                        $file_created = PageManager::createPageFiles(
                            $fields['path'],
                            $fields['handle']
                        );

                        // Existing page, potentially rename files
                    } else {
                        $file_created = PageManager::createPageFiles(
                            $fields['path'],
                            $fields['handle'],
                            $current['path'],
                            $current['handle']
                        );
                    }

                    // If the file wasn't created, it's usually permissions related
                    if (!$file_created) {
                        return $this->pageAlert(
                            __('Page Template could not be written to disk.')
                            . ' ' . __('Please check permissions on %s.', array('<code>/workspace/pages</code>')),
                            Alert::ERROR
                        );
                    }

                    // Insert the new data:
                    if (empty($current)) {
                        /**
                         * Just prior to creating a new Page record in `tbl_pages`, provided
                         * with the `$fields` associative array. Use with caution, as no
                         * duplicate page checks are run after this delegate has fired
                         *
                         * @delegate PagePreCreate
                         * @since Symphony 2.2
                         * @param string $context
                         * '/blueprints/pages/'
                         * @param array $fields
                         *  The `$_POST['fields']` array passed by reference
                         */
                        Symphony::ExtensionManager()->notifyMembers('PagePreCreate', '/blueprints/pages/', array('fields' => &$fields));

                        if (!$page_id = PageManager::add($fields)) {
                            $this->pageAlert(
                                __('Unknown errors occurred while attempting to save.')
                                . '<a href="' . SYMPHONY_URL . '/system/log/">'
                                . __('Check your activity log')
                                . '</a>.',
                                Alert::ERROR
                            );
                        } else {
                            /**
                             * Just after the creation of a new page in `tbl_pages`
                             *
                             * @delegate PagePostCreate
                             * @since Symphony 2.2
                             * @param string $context
                             * '/blueprints/pages/'
                             * @param integer $page_id
                             *  The ID of the newly created Page
                             * @param array $fields
                             *  An associative array of data that was just saved for this page
                             */
                            Symphony::ExtensionManager()->notifyMembers('PagePostCreate', '/blueprints/pages/', array('page_id' => $page_id, 'fields' => &$fields));

                            $redirect = "/blueprints/pages/edit/{$page_id}/created/{$parent_link_suffix}";
                        }

                        // Update existing:
                    } else {
                        /**
                         * Just prior to updating a Page record in `tbl_pages`, provided
                         * with the `$fields` associative array. Use with caution, as no
                         * duplicate page checks are run after this delegate has fired
                         *
                         * @delegate PagePreEdit
                         * @since Symphony 2.2
                         * @param string $context
                         * '/blueprints/pages/'
                         * @param integer $page_id
                         *  The ID of the Page that is about to be updated
                         * @param array $fields
                         *  The `$_POST['fields']` array passed by reference
                         */
                        Symphony::ExtensionManager()->notifyMembers('PagePreEdit', '/blueprints/pages/', array('page_id' => $page_id, 'fields' => &$fields));

                        if (!PageManager::edit($page_id, $fields, true)) {
                            return $this->pageAlert(
                                __('Unknown errors occurred while attempting to save.')
                                . '<a href="' . SYMPHONY_URL . '/system/log/">'
                                . __('Check your activity log')
                                . '</a>.',
                                Alert::ERROR
                            );
                        } else {
                            /**
                             * Just after updating a page in `tbl_pages`
                             *
                             * @delegate PagePostEdit
                             * @since Symphony 2.2
                             * @param string $context
                             * '/blueprints/pages/'
                             * @param integer $page_id
                             *  The ID of the Page that was just updated
                             * @param array $fields
                             *  An associative array of data that was just saved for this page
                             */
                            Symphony::ExtensionManager()->notifyMembers('PagePostEdit', '/blueprints/pages/', array('page_id' => $page_id, 'fields' => $fields));

                            $redirect = "/blueprints/pages/edit/{$page_id}/saved/{$parent_link_suffix}";
                        }
                    }
                }

                // Only proceed if there was no errors saving/creating the page
                if (empty($this->_errors)) {
                    /**
                     * Just before the page's types are saved into `tbl_pages_types`.
                     * Use with caution as no further processing is done on the `$types`
                     * array to prevent duplicate `$types` from occurring (ie. two index
                     * page types). Your logic can use the PageManger::hasPageTypeBeenUsed
                     * function to perform this logic.
                     *
                     * @delegate PageTypePreCreate
                     * @since Symphony 2.2
                     * @see toolkit.PageManager#hasPageTypeBeenUsed
                     * @param string $context
                     * '/blueprints/pages/'
                     * @param integer $page_id
                     *  The ID of the Page that was just created or updated
                     * @param array $types
                     *  An associative array of the types for this page passed by reference.
                     */
                    Symphony::ExtensionManager()->notifyMembers('PageTypePreCreate', '/blueprints/pages/', array('page_id' => $page_id, 'types' => &$types));

                    // Assign page types:
                    PageManager::addPageTypesToPage($page_id, $types);

                    // Find and update children:
                    if ($this->_context['action'] === 'edit') {
                        PageManager::editPageChildren($page_id, $fields['path'] . '/' . $fields['handle']);
                    }

                    if ($redirect) {
                        redirect(SYMPHONY_URL . $redirect);
                    }
                }
            }

            // If there was any errors, either with pre processing or because of a
            // duplicate page, return.
            if (is_array($this->_errors) && !empty($this->_errors)) {
                return $this->pageAlert(
                    __('An error occurred while processing this form. See below for details.'),
                    Alert::ERROR
                );
            }
        }
    }

    public function __actionDelete($pages, $redirect)
    {
        $success = true;
        $deleted_page_ids = array();

        if (!is_array($pages)) {
            $pages = array($pages);
        }

        /**
         * Prior to deleting Pages
         *
         * @delegate PagePreDelete
         * @since Symphony 2.2
         * @param string $context
         * '/blueprints/pages/'
         * @param array $page_ids
         *  An array of Page ID's that are about to be deleted, passed
         *  by reference
         * @param string $redirect
         *  The absolute path that the Developer will be redirected to
         *  after the Pages are deleted
         */
        Symphony::ExtensionManager()->notifyMembers('PagePreDelete', '/blueprints/pages/', array('page_ids' => &$pages, 'redirect' => &$redirect));

        foreach ($pages as $page_id) {
            $page = PageManager::fetchPageByID($page_id);

            if (empty($page)) {
                $success = false;
                $this->pageAlert(
                    __('Page could not be deleted because it does not exist.'),
                    Alert::ERROR
                );

                break;
            }

            if (PageManager::hasChildPages($page_id)) {
                $this->_hilights[] = $page['id'];
                $success = false;
                $this->pageAlert(
                    __('Page could not be deleted because it has children.'),
                    Alert::ERROR
                );

                continue;
            }

            if (!PageManager::deletePageFiles($page['path'], $page['handle'])) {
                $this->_hilights[] = $page['id'];
                $success = false;
                $this->pageAlert(
                    __('One or more pages could not be deleted.')
                    . ' ' . __('Please check permissions on %s.', array('<code>/workspace/pages</code>')),
                    Alert::ERROR
                );

                continue;
            }

            if (PageManager::delete($page_id, false)) {
                $deleted_page_ids[] = $page_id;
            }
        }

        if ($success) {
            /**
             * Fires after all Pages have been deleted
             *
             * @delegate PagePostDelete
             * @since Symphony 2.3
             * @param string $context
             * '/blueprints/pages/'
             * @param array $page_ids
             *  The page ID's that were just deleted
             */
            Symphony::ExtensionManager()->notifyMembers('PagePostDelete', '/blueprints/pages/', array('page_ids' => $deleted_page_ids));
            redirect($redirect);
        }
    }
}
