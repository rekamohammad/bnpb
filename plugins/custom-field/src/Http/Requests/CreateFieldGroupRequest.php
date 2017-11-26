<?php

namespace Botble\CustomField\Http\Requests;

use Botble\Support\Http\Requests\Request;

class CreateFieldGroupRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        return [
            'order' => 'integer|min:0',
            'rules' => 'json|required',
            'title' => 'string|required|max:255',
            'status' => 'required|in:0,1',
        ];
    }
}
