<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaylaterPenyewaanRequest extends FormRequest
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
            'jumlah_bayar'=> 'required|numeric',
            'quantity'=> 'nullable|numeric',
            'lokasi'=> 'required|string|max:255',
            'tgl_penyewaan'=> 'nullable|date',
            'ongkir'=> 'required',
        ];
    }
}
