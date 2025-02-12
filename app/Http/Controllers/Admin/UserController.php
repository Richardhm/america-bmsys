<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUser($id)
    {
        $user = User::where("id",$id)->first();
        if(!$user || $user->id != $id) {
            return redirect()->back();
        }
        return view('admin.pages.profiles.edit',[
            'user' => $user
        ]);
    }

    public function setUser(Request $request)
    {
        
        $rules = [
            "name" => "required",
            'password' => ['nullable', 'confirmed', 'min:8'],
            'image' => ['nullable','image']    
        ];
        $message = [
            "name.required" => "O campo nome e campo obrigatorio",
            "password.confirmed" => "Senhas Diferentes",
            "password.min" => "A senha deve ter no minimo 8 caracteres",
            "image.image" => "So aceitamos imagens"
        ];
        $request->validate($rules,$message);
        $user = User::find($request->user_id);
        $data = $request->all();

        if(!empty($data['password']) && $data['password'] != null) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        

        if($request->hasFile('image')) {
            if(Storage::exists($user->image)) {
                Storage::delete($user->image);
            } 
            //$data['image'] = $request->image->store("users");
            $data['image'] = $request->file('image')->store('users',"public");

        }
        $user->update($data);
        return redirect()->route('profile.getUser',$user->id)->with('success','Dados Alterados com Sucesso');
    }

}
