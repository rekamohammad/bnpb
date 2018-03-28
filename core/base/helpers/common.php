<?php

use Botble\Base\Facades\AdminBarFacade;
use Botble\Base\Facades\DashboardMenuFacade;
use Botble\Base\Facades\PageTitleFacade;
use Botble\Base\Repositories\Interfaces\PluginInterface;
use Botble\Base\Supports\Editor;
use Botble\Base\Supports\PageTitle;

if (!function_exists('table_actions')) {
    /**
     * @param $edit
     * @param $delete
     * @param $item
     * @return string
     * @author Sang Nguyen
     */
    function table_actions($edit, $delete, $item)
    {
        return view('bases::elements.tables.actions', compact('edit', 'delete', 'item'))->render();
    }
}

if (!function_exists('restore_action')) {
    /**
     * @param $restore
     * @param $item
     * @return string
     * @author Sang Nguyen
     */
    function restore_action($restore, $item)
    {
        return view('bases::elements.tables.restore', compact('restore', 'item'))->render();
    }
}

if (!function_exists('anchor_link')) {
    /**
     * @param $link
     * @param $name
     * @param array $options
     * @return string
     * @author Sang Nguyen
     */
    function anchor_link($link, $name, array $options = [])
    {
        $options = HTML::attributes($options);
        return view('bases::elements.tables.link', compact('link', 'name', 'options'))->render();
    }
}

if (!function_exists('table_checkbox')) {
    /**
     * @param $id
     * @return string
     * @author Sang Nguyen
     */
    function table_checkbox($id)
    {
        return view('bases::elements.tables.checkbox', compact('id'))->render();
    }
}

if (!function_exists('table_status')) {
    /**
     * @param $selected
     * @param array $statuses
     * @return string
     * @internal param $status
     * @internal param null $activated_text
     * @internal param null $deactivated_text
     * @author Sang Nguyen
     */
    function table_status($selected, $statuses = [])
    {
        if (empty($statuses) || !is_array($statuses)) {
            $statuses = [
                0 => [
                    'class' => 'label-danger',
                    'text' => trans('bases::tables.deactivated'),
                ],
                1 => [
                    'class' => 'label-success',
                    'text' => trans('bases::tables.activated'),
                ],
            ];
        }
        return view('bases::elements.tables.status', compact('selected', 'statuses'))->render();
    }
}

if (!function_exists('table_featured')) {
    /**
     * @param $is_featured
     * @param null $featured_text
     * @param null $not_featured_text
     * @return string
     * @author Tedozi Manson <github.com/duyphan2502>
     */
    function table_featured($is_featured, $featured_text = null, $not_featured_text = null)
    {
        return view('bases::elements.tables.is_featured', compact('is_featured', 'featured_text', 'not_featured_text'))->render();
    }
}

/**
 * @return boolean
 * @author Sang Nguyen
 */
function check_database_connection()
{
    try {
        DB::connection()->reconnect();
        return true;
    } catch (Exception $ex) {
        return false;
    }
}

if (!function_exists('language_flag')) {
    /**
     * @return string
     * @param $flag
     * @param $name
     * @author Sang Nguyen
     */
    function language_flag($flag, $name = null)
    {
        return HTML::image(url(BASE_LANGUAGE_FLAG_PATH . $flag . '.png'), $name, ['title' => $name]);
    }
}

function sanitize_html_class($class, $fallback = '')
{
    //Strip out any % encoded octets
    $sanitized = preg_replace('|%[a-fA-F0-9][a-fA-F0-9]|', '', $class);

    //Limit to A-Z,a-z,0-9,_,-
    $sanitized = preg_replace('/[^A-Za-z0-9_-]/', '', $sanitized);

    if ('' == $sanitized && $fallback) {
        return sanitize_html_class($fallback);
    }
    /**
     * Filters a sanitized HTML class string.
     *
     * @since 2.8.0
     *
     * @param string $sanitized The sanitized HTML class.
     * @param string $class HTML class before sanitization.
     * @param string $fallback The fallback string.
     */
    return apply_filters('sanitize_html_class', $sanitized, $class, $fallback);
}

if (!function_exists('parse_args')) {
    /**
     * @param $args
     * @param string $defaults
     * @return array
     */
    function parse_args($args, $defaults = '')
    {
        if (is_object($args)) {
            $result = get_object_vars($args);
        } else {
            $result =& $args;
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $result);
        }
        return $result;
    }
}

if (!function_exists('is_plugin_active')) {
    /**
     * @param $alias
     * @return bool
     */
    function is_plugin_active($alias)
    {
        $plugin = app(PluginInterface::class)->getFirstBy(['alias' => $alias]);
        if (!empty($plugin) && $plugin->status == 1) {
            return true;
        }
        return false;
    }
}

if (!function_exists('render_editor')) {
    /**
     * @param $name
     * @param null $value
     * @param bool $with_short_code
     * @return string
     * @author Sang Nguyen
     */
    function render_editor($name, $value = null, $with_short_code = false)
    {
        $editor = new Editor();
		return $editor->render($name, $value);
        /** return $editor->render($name, $value, $with_short_code); */
    }
}

if (!function_exists('is_in_admin')) {
    /**
     * @return bool
     */
    function is_in_admin()
    {
        $segment = request()->segment(1);
        if ($segment === config('cms.admin_dir')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('admin_bar')) {
    /**
     * @return Botble\Base\Supports\AdminBar
     */
    function admin_bar()
    {
        return AdminBarFacade::getFacadeRoot();
    }
}

if (!function_exists('page_title')) {
    /**
     * @return PageTitle
     */
    function page_title()
    {
        return PageTitleFacade::getFacadeRoot();
    }
}

if (!function_exists('dashboard_menu')) {
    /**
     * @return \Botble\Base\Supports\DashboardMenu
     */
    function dashboard_menu()
    {
        return DashboardMenuFacade::getFacadeRoot();
    }
}

if (!function_exists('html_attribute_element')) {
    /**
     * @param $key
     * @param $value
     * @return string
     * @author Sang Nguyen
     */
    function html_attribute_element($key, $value)
    {
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key != 'value') {
            return $value ? $key : '';
        }

        if (!empty($value)) {
            return $key . '="' . e($value) . '"';
        }
    }
}

if (!function_exists('html_attributes_builder')) {
    /**
     * @param array $attributes
     * @return string
     * @author Sang Nguyen
     */
    function html_attributes_builder(array $attributes)
    {
        $html = [];

        foreach ((array)$attributes as $key => $value) {
            $element = html_attribute_element($key, $value);

            if (!empty($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }
}

if (!function_exists('scan_language_keys')) {
    /**
     * Scan all __() function then save key to /storage/languages.json
     * @author Sang Nguyen
     * @param $key
     */
    function scan_language_keys($key)
    {
        if (!empty($key)) {
            $languages = [];
            $stored_file = storage_path('languages.json');
            if (file_exists($stored_file)) {
                $languages = get_file_data($stored_file, true);
            }
            $languages[$key] = $key;
            save_file_data($stored_file, $languages, true);
        }
    }
}

if (!function_exists('remove_query_string_var')) {
    /**
     * @param $url
     * @param $key
     * @return bool|mixed|string
     * @author Sang Nguyen
     */
    function remove_query_string_var($url, $key)
    {
        if (!is_array($key)) {
            $key = [$key];
        }
        foreach ($key as $item) {
            $url = preg_replace('/(.*)(?|&)' . $item . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
            $url = substr($url, 0, -1);
        }
        return $url;
    }
}
