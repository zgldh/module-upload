<?php

namespace $NAME$\Upload\Requests;

use $NAME$\Upload\Models\Upload;
use Illuminate\Foundation\Http\FormRequest;
use zgldh\Scaffold\Traits\HasWithParameter;

class UpdateUploadRequest extends FormRequest
{
    use HasWithParameter;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function all($keys = null)
    {
        $data = parent::all();
        if (!array_get($data, 'disk')) {
            $data['disk'] = config('upload.base_storage_disk');
        }
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Upload::$rules;
    }

    public function attributes()
    {
        return __('module_upload::t.models.upload.fields');
    }
}
