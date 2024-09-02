<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenyewaanRequest extends FormRequest
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
            'slug'=> 'required|exists:penyewaans,id',
            'status'=> 'required|string|in:Dalam Penyewaan,Di Batalkan,SELESAI',
            'keterangan'=> 'nullable|string|max:255',
            'tgl_pengembalian'=> 'nullable|date',
        ];
    }
}
