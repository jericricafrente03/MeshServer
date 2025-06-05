<?php

namespace App\Http\Requests\SystemSettings\DeviceAdb\Apk;

use Illuminate\Foundation\Http\FormRequest;

class ApkStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // if (request()->hasFile('uri')) {
        //     $file = request()->file('uri');
        //     $extension = $file->getClientOriginalExtension();
        //     $mimeType = $file->getMimeType();
            
        //     dd([
        //         'extension' => $file->getClientOriginalExtension(),
        //         'mimeType' => $file->getMimeType()
        //     ]);
        // }
        return [
            // 'uri' => ['required', 'file', 'mimetypes:application/zip'],
            // 'uri' => ['required', 'file', 'ends_with:.apk'],
            'uri' => ['required', 'file'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('uri')) {
                $file = $this->file('uri');
                $extension = strtolower($file->getClientOriginalExtension());

                if ($extension !== 'apk') {
                    $validator->errors()->add('uri', "The file must be an APK. You uploaded '$extension', which is not allowed.");
                }
            }
        });
    }

}
