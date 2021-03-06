<?php

namespace Botble\Blog\Http\Requests;

use Botble\Support\Http\Requests\Request;

class DioramaRequest extends Request
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
            'name' => 'required|max:120',
            'description' => 'required|max:300',
            'content' => 'required',
            'image' => 'required',
            'slug' => 'required',
        ];
    }
}
