<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StudentRequest extends FormRequest
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
    $rules = [
        'full_name' => 'required|string',
        'birth_date' => 'nullable|date',
        'birth_place' => 'nullable|string',
        'gender' => 'nullable|string',
        'school_level' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'address ' => 'nullable|string',
        'responsable' => 'nullable|array',
        'training_duration' => 'nullable|string',
        'sector' => 'nullable|string',
        'filières_formation' => 'nullable|string',
        'training_level' => 'required|string',
        'group_uuid' => 'nullable|string|exists:groups,uuid',
        'monthly_amount' => 'nullable|string',
        'registration_fee' => 'nullable|string',
        'product' => 'nullable|string',
        'frais_diplôme' => 'nullable|string',
        'annual_amount' => 'nullable|string',
        'status' => 'nullable|string',
        'date_start_at' => 'nullable|date',
        'date_fin_at' => 'nullable|date',
    ];

    if ($this->isMethod('post')) {            
        $rules['photo'] = 'nullable|file|mimes:png,jpeg,jpg|max:4096';
        $rules['CIN'] = 'nullable|string|unique:students,CIN';
        $rules['inscription_number'] = 'nullable|string|unique:students,CIN';
        $rules['id_massar'] = 'nullable|string|unique:students,id_massar';
        $rules['email'] = 'nullable|email|unique:students,email';
    }

    if ($this->isMethod('put')) {
        $uuid = $this->route('student_uuid'); 
        $rules['CIN'] = 'nullable|string|unique:students,CIN,' . $uuid . ',uuid';
        $rules['id_massar'] = 'nullable|string|unique:students,id_massar,' . $uuid . ',uuid';
        $rules['inscription_number'] = 'nullable|string|unique:students,CIN,' . $uuid . ',uuid';
        $rules['email'] = 'nullable|email|unique:students,email,' . $uuid . ',uuid';
    }

    return $rules;
}

    public function failedValidation(Validator $validator)
        {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ],400));
    }
}
