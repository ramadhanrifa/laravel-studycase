<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class userController extends Controller
{
    public function index()
    {
        $user = User::all();
        
        return view('user.index', compact('user'));
    }


    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required',
            'role' => 'required',
        ],[
            'name.required'=> 'nama user wajib diisi',
            'name.min'=> 'nama user minimal 3 huruf',
            'email.email' => 'penulisan email haruf valid contoh @gmail',
            'role.required'=> 'bagian role wajib diisi',
            
        ]);

        $password = substr(str_replace(' ', '', $request->name), 0, 3) . substr(str_replace(' ', '', $request->email), 0, 3);

        User::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'role' =>$request->role,
            'password' => Hash::make($password), // Corrected line
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data user');
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required',
            'role' => 'required',
        ]);

        User::where('id', $id)->update([
            'name' =>$request->name,
            'email' =>$request->email,
            'role' =>$request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.home')->with('success', 'Berhasil mengubah user!');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'user berhasil dihapus!');
    }



    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|',
            'password' => 'required',
        ], [
            "email.required" => 'harap masukan email yang valid',
            "password.required" => "harap masukan password",
        ]
    
    );
        
        $user = $request->only(['email', 'password']);
        if(Auth::attempt($user)){
            return redirect()->route('home.page');      
        }else{
            return redirect()->back()->with('failed', 'Proses login gagal, silahkan coba kembali dengan daya yang benar!');

        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah logout! <br> terimakasih telah menggunakan website ini!!');
    }
}
