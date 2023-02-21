<?php

namespace App\Http\Livewire;

use App\Models\Bebidas;
// use App\Models\Produtos;
use Livewire\Component;
use App\Helpers\MyHelpers;
use App\Models\ListaProdutos;
use App\Models\UsuariosPedidos;
use Illuminate\Support\Facades\DB;

class FormNovo extends Component
{
    public $produtos = [];
    public $total = 0;
    public $frete = 0;

    public $listaProdutos;
    public $listaBebidas;
    public $opcao = [];

    public function mudarOpcao($index){
        $this->opcao[$index] = $this->produtos[$index]['tipo'];
    }


    public function mudarDetalhe($index){
        if($this->opcao[$index] == "Bebida"){
            foreach($this->listaBebidas as $bebida){
                if($bebida['nome'] == $this->produtos[$index]['opcao']){
                    $this->produtos[$index]['valorUnitario'] =  number_format( $bebida['valor']/ 100, 2, ',','') ;
                    $this->calcularTotal();
                }
            }
        }else{
            foreach($this->listaProdutos->produtos as $produto){
                if($produto->detalhes == $this->produtos[$index]['opcao'] && $produto->tipo == $this->opcao[$index]){
                    $this->produtos[$index]['valorUnitario'] =  number_format( $produto->valor/ 100, 2, ',','') ;
                    $this->calcularTotal();
                }
            }
        }
       
    }
    public $usuarios = [];
    public function mount()
    {
        $this->produtos = [
            [
                'tipo' => '',
                'opcao' => '',
                'valorUnitario' => '',
                'quantidade' => 1
            ],
        ];

        $this->listaProdutos = ListaProdutos::with(['produtos' => function($d){
            $d->where('ativado_hoje', 1);
        }])
        ->where('ativado_hoje', 1)
        ->first();

        $this->listaBebidas = Bebidas::where('ativado_hoje', 1)
        ->get();

        $this->opcao[] = [];
        // dd($this->listaBebidas);

        $usuarios = UsuariosPedidos::all();
        
        if($usuarios){
            foreach($usuarios as $usuario){
                $this->usuarios[$usuario->id] = [$usuario->nome. " ".$usuario->whatsapp. " ". $usuario->endereco]; 
            }
        }
        // dd($this->usuarios);
       
    }

    public function addProduto()
    {
        $this->produtos[] = [
                'tipo' => '',
                'opcao' => '',
                'valorUnitario' => '',
                'quantidade' => 1
        ];
        $this->opcao[] = [];
    }
    
    public function removeProduto($index)
    {
        unset($this->produtos[$index]);
        $this->produtos = array_values($this->produtos);

        unset($this->opcao[$index]);
        $this->opcao = array_values($this->opcao);


    }

    public function calcularTotal()
    {
        $total = 0;
        foreach($this->produtos as $index => $produto){
            if($produto['valorUnitario'] != ""){
                $v = $produto['valorUnitario'];
            } else $v = 0;

            if($produto['quantidade'] != ""){
                $q = $produto['quantidade'];
            } else $q = 0;
            
            $total = MyHelpers::formatPreco($v) * $q + $total;
        }
        if(is_numeric($total)){
            $this->total = $total + MyHelpers::formatPreco($this->frete);
        }
    }


    public function render()
    {
        return view('livewire.form-novo');
    }
}
