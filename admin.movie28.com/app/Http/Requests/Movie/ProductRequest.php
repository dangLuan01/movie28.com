<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            //
        ];
    }
    // public function messages(): array
    // {
    //     return [
    //         'name.required' => 'Tên sản phẩm không được để trống',
    //         'name.unique' => 'Tên sản phẩm đã tồn tại',
    //         'name.max' => 'Tên sản phẩm không được quá 255 ký tự',
    //         'description.required' => 'Mô tả sản phẩm không được để trống',
    //         'description.max' => 'Mô tả sản phẩm không được quá 255 ký tự',
    //         'price.required' => 'Giá sản phẩm không được để trống',
    //         'price.numeric' => 'Giá sản phẩm phải là số',
    //         'price.min' => 'Giá sản phẩm không được nhỏ hơn 0',
    //         'price.max' => 'Giá sản phẩm không được lớn hơn 999999999',
    //         'quantity.required' => 'Số lượng sản phẩm không được để trống',
    //         'quantity.numeric' => 'Số lượng sản phẩm phải là số',
    //         'quantity.min' => 'Số lượng sản phẩm không được nhỏ hơn 0',
    //         'quantity.max' => 'Số lượng sản phẩm không được lớn hơn 999999999',
    //         'image.required' => 'Ảnh sản phẩm không được để trống',
    //         'image.image' => 'Ảnh sản phẩm phải là ảnh',
    //         'image.mimes' => 'Ảnh sản phẩm phải có định dạng jpeg, png, jpg, gif, svg',
    //         'image.max' => 'Ảnh sản phẩm không được lớn hơn 2048',
    //         'genre_id.required' => 'Thể loại sản phẩm không được để trống',
    //         'genre_id.numeric' => 'Thể loại sản phẩm phải là số',
    //         'genre_id.exists' => 'Thể loại sản phẩm không tồn tại',
    //         'country_id.required' => 'Quốc gia sản phẩm không được để trống',
    //         'country_id.numeric' => 'Quốc gia sản phẩm phải là số',
    //         'country_id.exists' => 'Quốc gia sản phẩm không tồn tại',
    //     ];
    // }
}
