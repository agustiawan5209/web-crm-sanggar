<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiskonRequest extends FormRequest
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
        return [
            'slug'=> 'required|exists:diskons,id',
            'nama'=> 'required|string|max:255',
            'keterangan'=> 'required|string|max:255',
            'jasa_id'=> 'nullable',
            'alat_id'=> 'nullable',
            'jenis'=> 'required|in:Get,Keep',
            'jumlah'=> 'required|numeric|between:0,100',
            'kadaluarsa'=> 'nullable|date',
        ];
    }
}
