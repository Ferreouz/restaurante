<?php

namespace App\Http\Controllers;

// use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\UsuariosPedidos;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\DB;

class UsersAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    // public function get_cpf($cpf){
    //     return UsuariosPedidos::where('cpf', $cpf)->first();
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'nome_completo' => 'required',
        //     'endereco' => 'required'
        // ]);
        // return UsuariosPedidos::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return UsuariosPedidos::where('id_bot', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'id_bot' => 'required',
            'whatsapp' => 'required'
        ]);
        $user_id = auth()->user()->id;

        $user = UsuariosPedidos::where([
            'whatsapp' =>  $request->whatsapp,
            'id_bot' => $request->id_bot,
            'user_id' => $user_id, 
        ])
        ->update( $request->all());
       
        return json_encode(['message' => 'Editado com sucesso!']);
    }

   
}
