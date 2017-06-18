<?php

namespace WoXuanWang\Upload\Requests;

use Illuminate\Foundation\Http\FormRequest;
use WoXuanWang\Upload\Models\Upload;

class UpdateUploadRequest extends FormRequest
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

    public function all()
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
}
