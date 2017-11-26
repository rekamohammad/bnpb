<?php

namespace Botble\Contact\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Contact\Repositories\Interfaces\ContactInterface;

class HookServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 120);
        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnReadCount'], 120, 2);
        add_shortcode('contact-form', __('Contact form'), __('Add contact form'), [$this, 'form']);
    }

    /**
     * @param string $options
     * @return string
     * @author Sang Nguyen
     */
    public function registerTopHeaderNotification($options)
    {
        if (acl_get_current_user()->hasPermission('contacts.edit')) {
            $contacts = app(ContactInterface::class)->getUnread(['id', 'name', 'email', 'phone', 'created_at']);

            return $options . view('contact::partials.notification', compact('contacts'))->render();
        }
        return null;
    }

    /**
     * @param $number
     * @param $route
     * @return string
     * @author Sang Nguyen
     */
    public function getUnreadCount($number, $route)
    {
        if ($route == 'contacts.list') {
            $unread = app(ContactInterface::class)->countUnread();
            if ($unread > 0) {
                return '<span class="badge badge-success">' . $unread . '</span>';
            }
        }
        return $number;
    }

    /**
     * @return string
     */
    public function form($shortcode)
    {
        return view('contact::forms.contact', ['header' => $shortcode->header])->render();
    }
}
