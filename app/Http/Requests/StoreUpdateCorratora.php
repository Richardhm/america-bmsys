<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCorratora extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "nome" => ['required'],
            "telefone" => ['required'],
            "email" => ['required','email'],
            "site" => ['required'],
            "instagram" => ['required'],
            "endereco" => ['required'],
            "consultas_eletivas" => 'required',
            "consultas_urgencia" => 'required',
            "exames_simples" => 'required',
            "exames_complexos" => 'required',
            "linha_01_coletivo" => ['nullable','max:130'],
            "linha_02_coletivo" => ['nullable','max:130'],
            "linha_03_coletivo" => ['nullable','max:130'],
            "linha_01_individual" => ['nullable','max:130'],
            "linha_02_individual" => ['nullable','max:130'],
            "linha_03_individual" => ['nullable','max:130'],
            "logo" => ['required','image']
        ];
    }

    public function messages()
    {
        return [
            "nome.required" => "O campo nome e campo obrigatorio",
            "telefone.required" => "O campo telefone e campo obrigatorio",           
            "email.required" => "O campo email e campo obrigatorio",
            "email.email" => "Email invalido",
            "site.required" => "O campo site e campo obrigatorio",

            "instagram.required" => "O campo instagram e campo obrigatorio",

            "endereco.required" => "O campo endereco e campo obrigatorio",

            "consultas_eletivas.required" => "O campo consultas eletivas e campo obrigatorio",
            "consultas_eletivas.regex" => "Valor Invalido",

            "consultas_urgencia.required" => "O campo consultas de urgencias e campo obrigatorio",
            "consultas_urgencia.regex" => "Valor Invalido",

            "exames_simples.required" => "O campo exames simples e campo obrigatorio",
            "exames_simples.regex" => "Valor invalido",

            "exames_complexos.required" => "O campo exames complexos e campo obrigatorio",

            "linha_01_coletivo.max" => "O campo Linha 01 Coletivo e campo obrigatorio",
            "linha_02_coletivo.max" => "O campo Linha 02 Coletivo e campo obrigatorio",
            "linha_03_coletivo.max" => "O campo Linha 03 Coletivo e campo obrigatorio",

            "linha_01_individual.max" => "O campo Linha 01 Individual e campo obrigatorio",
            "linha_02_individual.max" => "O campo Linha 02 Individual e campo obrigatorio",
            "linha_03_individual.max" => "O campo Linha 03 Individual e campo obrigatorio",

            "logo.required" => "O campo logo e campo obrigatorio",
            "logo.image" => "Imagem Invalida"

        ];
    }


}
