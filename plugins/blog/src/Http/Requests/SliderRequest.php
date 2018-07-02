<?php

namespace Botble\Blog\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SliderRequest extends Request
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
		    'name' => 'required', 
            'images' => 'required',
        ];
    }
}
