<?php

namespace Portal\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use League\Flysystem\Exception;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = null;

        if($this->route()){
            $id = $this->route()->parameter('permissao');
        }
        $rules = [
            'inherit_id'=>'numeric',
            'name'=>'required|unique:permissions,name'.(isset($id)?','.$id:''),
            'slug'=>'array'
        ];

        /**
         * @param $slugs
         */
        $slugs = $this->get('slug',[]);
        $buildRulesItems = function (array $slugs) use (&$rules){
            $t = &$rules;
            foreach ($slugs as $key => $val){
                $t["slug.$key"] = 'required|boolean';
            }
        };
        if(!empty($slugs)){
            $buildRulesItems($slugs);
        }
        return $rules;
    }
}
