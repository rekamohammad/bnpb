<?php

namespace Botble\Theme;

use Exception;
use Form;
use Setting;

class ThemeOption
{
    /**
     * @var array
     */
    public $fields = [];

    /**
     * @var array
     */
    public $sections = [];

    /**
     * @var array
     */
    public $help = [];

    /**
     * @var array
     */
    public $args = [];

    /**
     * @var array
     */
    public $priority = [];

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var string
     */
    public $opt_name = 'theme';

    /**
     * Prepare args of theme options
     *
     * @return array|mixed
     * @author Sang Nguyen
     */
    public function constructArgs()
    {
        $args = isset($this->args[$this->opt_name]) ? $this->args[$this->opt_name] : [];

        $args['opt_name'] = $this->opt_name;
        if (!isset($args['menu_title'])) {
            $args['menu_title'] = ucfirst($this->opt_name) . ' Options';
        }
        if (!isset($args['page_title'])) {
            $args['page_title'] = ucfirst($this->opt_name) . ' Options';
        }
        if (!isset($args['page_slug'])) {
            $args['page_slug'] = $this->opt_name . '_options';
        }

        return $args;
    }

    /**
     * Prepare sections to display theme options page
     *
     * @return array
     * @author Sang Nguyen
     */
    public function constructSections()
    {
        $sections = [];
        if (!isset($this->sections[$this->opt_name])) {
            return $sections;

        }
        foreach ($this->sections[$this->opt_name] as $section_id => $section) {
            $section['fields'] = $this->constructFields($section_id);
            $priority = $section['priority'];
            while (isset($sections[$priority])) {
                $priority++;
            }
            $sections[$priority] = $section;
        }
        ksort($sections);

        return $sections;
    }

    /**
     * Prepare fields to display theme options page
     *
     * @param string $section_id
     * @return array
     * @author Sang Nguyen
     */
    public function constructFields($section_id = '')
    {
        $fields = [];
        if (!empty($this->fields[$this->opt_name])) {
            foreach ($this->fields[$this->opt_name] as $field) {
                if ($field['section_id'] == $section_id) {
                    $priority = $field['priority'];
                    while (isset($fields[$priority])) {
                        echo $priority++;
                    }
                    $fields[$priority] = $field;
                }
            }
        }
        ksort($fields);

        return $fields;
    }

    /**
     * @param string $id
     * @return bool
     * @author Sang Nguyen
     */
    public function getSection($id = '')
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($id)) {
            if (!isset($this->sections[$this->opt_name][$id])) {
                $id = strtolower(sanitize_html_class($id));
            }

            return isset($this->sections[$this->opt_name][$id]) ? $this->sections[$this->opt_name][$id] : false;
        }

        return false;
    }

    /**
     * @author Sang Nguyen
     */
    public function checkOptName()
    {
        if (empty($this->opt_name) || is_array($this->opt_name)) {
            return;
        }
        if (!isset($this->sections[$this->opt_name])) {
            $this->sections[$this->opt_name] = [];
            $this->priority[$this->opt_name]['sections'] = 1;
        }
        if (!isset($this->args[$this->opt_name])) {
            $this->args[$this->opt_name] = [];
            $this->priority[$this->opt_name]['args'] = 1;
        }
        if (!isset($this->fields[$this->opt_name])) {
            $this->fields[$this->opt_name] = [];
            $this->priority[$this->opt_name]['fields'] = 1;
        }
        if (!isset($this->help[$this->opt_name])) {
            $this->help[$this->opt_name] = [];
            $this->priority[$this->opt_name]['help'] = 1;
        }
        if (!isset($this->errors[$this->opt_name])) {
            $this->errors[$this->opt_name] = [];
        }
    }

    /**
     * @return array|mixed
     * @author Sang Nguyen
     */
    public function getSections()
    {
        $this->checkOptName();
        if (!empty($this->sections[$this->opt_name])) {
            return $this->sections[$this->opt_name];
        }

        return [];
    }

    /**
     * @param array $sections
     * @author Sang Nguyen
     */
    public function setSections($sections = [])
    {
        $this->checkOptName();
        if (!empty($sections)) {
            foreach ($sections as $section) {
                $this->setSection($section);
            }
        }
    }

    /**
     * @param array $section
     * @author Sang Nguyen
     */
    public function setSection($section = [])
    {
        $this->checkOptName();
        if (empty($section)) {
            return;
        }
        if (!isset($section['id'])) {
            if (isset($section['type']) && $section['type'] == 'divide') {
                $section['id'] = time();
            } else {
                if (isset($section['title'])) {
                    $section['id'] = strtolower($section['title']);
                } else {
                    $section['id'] = time();
                }
            }

            if (isset($this->sections[$this->opt_name][$section['id']])) {
                $orig = $section['id'];
                $index = 0;
                while (isset($this->sections[$this->opt_name][$section['id']])) {
                    $section['id'] = $orig . '_' . $index;
                }
            }
        }

        if (!empty($this->opt_name) && is_array($section) && !empty($section)) {
            if (!isset($section['id']) && !isset($section['title'])) {
                $this->errors[$this->opt_name]['section']['missing_title'] = 'Unable to create a section due to missing id and title.';

                return;
            }
            if (!isset($section['priority'])) {
                $section['priority'] = $this->getPriority('sections');
            }
            if (isset($section['fields'])) {
                if (!empty($section['fields']) && is_array($section['fields'])) {
                    $this->processFieldsArray($section['id'], $section['fields']);
                }
                unset($section['fields']);
            }
            $this->sections[$this->opt_name][$section['id']] = $section;
        } else {
            $this->errors[$this->opt_name]['section']['empty'] = 'Unable to create a section due an empty section array or the section variable passed was not an array.';

            return;
        }
    }

    /**
     * @param $type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPriority($type)
    {
        $priority = $this->priority[$this->opt_name][$type];
        $this->priority[$this->opt_name][$type] += 1;

        return $priority;
    }

    /**
     * @param string $section_id
     * @param array $fields
     * @author Sang Nguyen
     */
    public function processFieldsArray($section_id = '', $fields = [])
    {
        if (!empty($this->opt_name) && !empty($section_id) && is_array($fields) && !empty($fields)) {
            foreach ($fields as $field) {
                if (!is_array($field)) {
                    continue;
                }
                $field['section_id'] = $section_id;
                $this->setField($field);
            }
        }
    }

    /**
     * @param array $field
     * @author Sang Nguyen
     */
    public function setField($field = [])
    {
        $this->checkOptName();

        if (!empty($this->opt_name) && is_array($field) && !empty($field)) {

            if (!isset($field['priority'])) {
                $field['priority'] = $this->getPriority('fields');
            }
            if (isset($field['id'])) {
                $this->fields[$this->opt_name][$field['id']] = $field;
            }
        }
    }

    /**
     * @param string $id
     * @param bool $fields
     * @author Sang Nguyen
     */
    public function removeSection($id = '', $fields = false)
    {
        if (!empty($this->opt_name) && !empty($id)) {
            if (isset($this->sections[$this->opt_name][$id])) {
                $priority = '';

                foreach ($this->sections[$this->opt_name] as $key => $section) {
                    if ($key == $id) {
                        $priority = $section['priority'];
                        $this->priority[$this->opt_name]['sections']--;
                        unset($this->sections[$this->opt_name][$id]);
                        continue;
                    }
                    if ($priority != '') {
                        $newPriority = $section['priority'];
                        $section['priority'] = $priority;
                        $this->sections[$this->opt_name][$key] = $section;
                        $priority = $newPriority;
                    }
                }

                if (isset($this->fields[$this->opt_name]) && !empty($this->fields[$this->opt_name]) && $fields == true) {
                    foreach ($this->fields[$this->opt_name] as $key => $field) {
                        if ($field['section_id'] == $id) {
                            unset($this->fields[$this->opt_name][$key]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $id
     * @param bool $hide
     * @author Sang Nguyen
     */
    public function hideSection($id = '', $hide = true)
    {
        $this->checkOptName();

        if (!empty($this->opt_name) && !empty($id)) {
            if (isset ($this->sections[$this->opt_name][$id])) {
                $this->sections[$this->opt_name][$id]['hidden'] = $hide;
            }
        }
    }

    /**
     * @param string $id
     * @return bool
     * @author Sang Nguyen
     */
    public function getField($id = '')
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($id)) {
            return isset($this->fields[$this->opt_name][$id]) ? $this->fields[$this->opt_name][$id] : false;
        }

        return false;
    }

    /**
     * @param string $id
     * @param bool $hide
     * @author Sang Nguyen
     */
    public function hideField($id = '', $hide = true)
    {
        $this->checkOptName();

        if (!empty($this->opt_name) && !empty($id)) {
            if (isset ($this->fields[$this->opt_name][$id])) {
                if (!$hide) {
                    $this->fields[$this->opt_name][$id]['class'] = str_replace('hidden', '', $this->fields[$this->opt_name][$id]['class']);
                } else {
                    $this->fields[$this->opt_name][$id]['class'] .= 'hidden';
                }
            }
        }
    }

    /**
     * @param string $id
     * @return bool
     * @author Sang Nguyen
     */
    public function removeField($id = '')
    {
        $this->checkOptName();

        if (!empty($this->opt_name) && !empty($id)) {
            if (isset($this->fields[$this->opt_name][$id])) {
                foreach ($this->fields[$this->opt_name] as $key => $field) {
                    if ($key == $id) {
                        $priority = $field['priority'];
                        $this->priority[$this->opt_name]['fields']--;
                        unset($this->fields[$this->opt_name][$id]);
                        continue;
                    }
                    if (isset($priority) && $priority != '') {
                        $newPriority = $field['priority'];
                        $field['priority'] = $priority;
                        $this->fields[$this->opt_name][$key] = $field;
                        $priority = $newPriority;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param array $tab
     * @author Sang Nguyen
     */
    public function setHelpTab($tab = [])
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($tab)) {
            if (!isset($this->args[$this->opt_name]['help_tabs'])) {
                $this->args[$this->opt_name]['help_tabs'] = [];
            }
            if (isset($tab['id'])) {
                $this->args[$this->opt_name]['help_tabs'][] = $tab;
            } else if (is_array(end($tab))) {
                foreach ($tab as $tab_item) {
                    $this->args[$this->opt_name]['help_tabs'][] = $tab_item;
                }
            }
        }
    }

    /**
     * @param string $content
     * @author Sang Nguyen
     */
    public function setHelpSidebar($content = '')
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($content)) {
            $this->args[$this->opt_name]['help_sidebar'] = $content;
        }
    }

    /**
     * @return array|mixed
     * @author Sang Nguyen
     */
    public function getArgs()
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($this->args[$this->opt_name])) {
            return $this->args[$this->opt_name];
        }
        return [];
    }

    /**
     * @param array $args
     * @author Sang Nguyen
     */
    public function setArgs($args = [])
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($args) && is_array($args)) {
            if (isset($this->args[$this->opt_name]) && isset($this->args[$this->opt_name]['clearArgs'])) {
                $this->args[$this->opt_name] = [];
            }
            $this->args[$this->opt_name] = parse_args($args, $this->args[$this->opt_name]);
        }
    }

    /**
     * @param string $key
     * @return null
     * @author Sang Nguyen
     */
    public function getArg($key = '')
    {
        $this->checkOptName();
        if (!empty($this->opt_name) && !empty($key) && !empty($this->args[$this->opt_name])) {
            return array_get($this->args[$this->opt_name], $key);
        } else {
            return null;
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     * @author Sang Nguyen
     */
    public function setOption($key, $value = '')
    {
        Setting::set($this->opt_name . '-' . setting()->get('theme') . '-' . $key, $value);
    }

    /**
     * @param $field
     * @return mixed|string
     * @author Sang Nguyen
     */
    public function renderField($field)
    {
        try {
            if ($this->hasOption($field['attributes']['name'])) {
                $field['attributes']['value'] = $this->getOption($field['attributes']['name']);
            }

            if ($field['type'] == 'select') {
                return call_user_func_array([Form::class, $field['type']], $field['attributes']);
            }
            return call_user_func_array([Form::class, $field['type']], $field['attributes']);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param string $key
     * @return bool
     * @author Sang Nguyen
     */
    public function hasOption($key)
    {
        return setting()->has($this->opt_name . '-' . setting()->get('theme') . '-' . $key);
    }

    /**
     * @param string $key
     * @return string
     * @author Sang Nguyen
     */
    public function getOption($key = '', $default = '')
    {
        $data = setting($this->opt_name . '-' . setting()->get('theme') . '-' . $key);
        if (!empty($data)) {
            return $data;
        }
        return $default;
    }
}