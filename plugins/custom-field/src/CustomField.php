<?php

namespace Botble\CustomField;

use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Closure;

class CustomField
{
    /**
     * @var array
     */
    protected $ruleGroups = [
        'Basic' => [
            'items' => [

            ],
        ],
        'Other' => [
            'items' => [

            ],
        ]
    ];

    /**
     * @var array|string
     */
    protected $rules = [];

    /**
     * @param $groupName
     * @return $this
     */
    public function registerRuleGroup($groupName)
    {
        $this->ruleGroups[$groupName] = [
            'items' => []
        ];
        return $this;
    }

    /**
     * @param string $group
     * @param string $title
     * @param string $slug
     * @param Closure|array $data
     * @return $this
     */
    public function registerRule($group, $title, $slug, $data)
    {
        if (!isset($this->ruleGroups[$group])) {
            $this->registerRuleGroup($group);
        }
        $this->ruleGroups[$group]['items'][$slug] = [
            'title' => $title,
            'slug' => $slug,
            'data' => (!isset($this->ruleGroups[$group]['items'][$slug])) ? $data : array_merge($this->ruleGroups[$group]['items'][$slug]['data'], $data)
        ];
        return $this;
    }

    /**
     * Render data
     * @return string
     */
    public function render()
    {
        return view('custom-field::_script-templates.rules', [
            'ruleGroups' => $this->resolveGroups()
        ])->render();
    }

    /**
     * Resolve all rule data from closure into array
     * @return array
     */
    protected function resolveGroups()
    {
        foreach ($this->ruleGroups as &$group) {
            foreach ($group['items'] as &$item) {
                if ($item['data'] instanceof Closure) {
                    $item['data'] = call_user_func($item['data']);
                }
                if (!is_array($item['data'])) {
                    $item['data'] = [];
                }
            }
        }
        return $this->ruleGroups;
    }

    /**
     * @param array|string $rules
     * @return $this
     */
    public function setRules($rules)
    {
        if (!is_array($rules)) {
            $this->rules = json_decode($rules, true);
        } else {
            $this->rules = $rules;
        }
        return $this;
    }

    /**
     * @param string|array $ruleName
     * @param $value
     * @return $this
     */
    public function addRules($ruleName, $value = null)
    {
        if (is_array($ruleName)) {
            $rules = $ruleName;
        } else {
            $rules = [$ruleName => $value];
        }
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    /**
     * @param $ruleGroups
     * @return bool
     */
    protected function checkRules($ruleGroups)
    {
        if (!$ruleGroups) {
            return false;
        }
        foreach ($ruleGroups as $group) {
            if ($this->checkEachRule($group)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $ruleGroup
     * @return bool
     */
    protected function checkEachRule($ruleGroup)
    {
        foreach ($ruleGroup as $rule) {
            if (!isset($this->rules[$rule['name']])) {
                continue;
            }
            if ($rule['type'] == '==') {
                if(is_array($this->rules[$rule['name']])) {
                    return in_array($rule['value'], $this->rules[$rule['name']]);
                }
                return $rule['value'] == $this->rules[$rule['name']];
            } else {
                if (is_array($this->rules[$rule['name']])) {
                    return !in_array($rule['value'], $this->rules[$rule['name']]);
                }
                return $rule['value'] != $this->rules[$rule['name']];
            }
        }
        return false;
    }

    /**
     * @param $screen
     * @param $object_id
     * @return array
     */
    public function exportCustomFieldsData($screen, $object_id)
    {
        $fieldGroups = app(FieldGroupInterface::class)->advancedGet([
            'condition' => [
                'status' => 1,
            ],
            'order_by' => [
                'status' => 'ASC',
            ],
            'select' => ['id', 'title', 'rules'],
        ]);

        $result = [];

        foreach ($fieldGroups as $row) {
            if ($this->checkRules(json_decode($row->rules, true))) {
                $result[] = [
                    'id' => $row->id,
                    'title' => $row->title,
                    'items' => app(FieldGroupInterface::class)->getFieldGroupItems($row->id, null, true, $screen, $object_id),
                ];
            }
        }

        return $result;
    }
}