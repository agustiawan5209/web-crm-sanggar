<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenyewaanRequest extends FormRequest
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
            "bukti"=> "required|image",
            // 'jenis_bayar'=> 'required|in:DP,Lunas',
            'jumlah_bayar'=> 'required|numeric',
            'quantity'=> 'nullable|numeric',
            'tgl_pembayaran'=> 'required|date',
            'tgl_pengambilan'=> 'nullable|date',
            'tgl_pengembalian'=> 'nullable|date',
        ];
    }
}
