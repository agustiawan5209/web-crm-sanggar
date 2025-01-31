<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukAlatRequest extends FormRequest
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
            'slug' => 'required|exists:produk_alats,id',
            'nama' => 'required|string|max:100',
            'keterangan' => 'required|string',
            'harga' => 'required|decimal:0,999999999',
            'biaya_ongkir' => 'required|decimal:0,999999999',
            'stok' => 'required|numeric',
            'status'=> 'required|in:0,1',

        ];
    }
}
