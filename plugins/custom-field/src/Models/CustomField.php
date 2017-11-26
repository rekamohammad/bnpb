<?php

namespace Botble\CustomField\Models;

use Eloquent;
use Botble\CustomField\Models\Contracts\CustomFieldContract;
use Exception;

class CustomField extends Eloquent implements CustomFieldContract
{
    protected $table = 'custom_fields';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function useCustomFields()
    {
        return $this->morphTo();
    }

    /**
     * Get $this->resolved_value
     * @return array|mixed
     */
    public function getResolvedValueAttribute()
    {
        switch ($this->type) {
            case 'repeater':
                try {
                    return json_decode($this->value, true);
                } catch (Exception $exception) {
                    return [];
                }
                break;
            default:
                return $this->value;
                break;
        }
    }
}
