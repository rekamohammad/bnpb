<?php

namespace Botble\Base\Supports;

class Action extends ActionHookEvent
{

    /**
     * Filters a value
     * @param  string $action Name of action
     * @param  array $args Arguments passed to the filter
     * @author Tor Morten Jensen <tormorten@tormorten.no>
     */
    public function fire($action, $args)
    {
        if ($this->getListeners()) {
            foreach ($this->getListeners() as $hook => $listeners) {
                foreach ($listeners as $arguments) {
                    if ($hook === $action) {
                        $parameters = [];
                        for ($i = 0; $i < $arguments['arguments']; $i++) {
                            if (isset($args[$i])) {
                                $parameters[] = $args[$i];
                            }
                        }
                        call_user_func_array($this->getFunction($arguments['callback']), $parameters);
                    }
                }
            }
        }
    }
}
