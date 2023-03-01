<?php

namespace App\Http\Livewire;

use App\Models\Bebidas;
use Livewire\Component;
use App\Models\Produtos;
use App\Helpers\MyHelpers;
use App\Models\ConfigUser;
use App\Models\Guarnicoes;
use App\Models\ListaProdutos;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConfigPedidos extends Component
{
    public $userLogado;
    public function mount()
    {
        $this->userLogado = auth()->user()->id;
        $this->bebidaPath = 'storage/bebidas_'.  $this->userLogado  . '.png';

        //Lista
        $lps = ListaProdutos::all();

        foreach($lps as $lp){
            $this->listaProdutos[] = 
                [
                    'id' => $lp->id,
                    'nome' => $lp->nome,
                    'imagem' => $lp->imagem,
                    'ativado_hoje' => $lp->ativado_hoje,
                ];
        }
        // dump($this->currentLista);
        // Bebibas
        $objs = Bebidas::all();
        foreach($objs as $obj){
            $this->bebidas[] = 
                [
                    'id' => $obj->id,
                    'nome' => $obj->nome,
                    'valor' => number_format( $obj->valor/ 100, 2, ',',''),
                    'ativado_hoje' => $obj->ativado_hoje,
                    'botaoSalvar' => 0,
                   
                ];
        }

        $ps =  Produtos::all();
        foreach($ps as $p){
            $this->produtos[] = 
                [
                    'id' => $p->id,
                    'lista_id' => $p->lista_id,
                    'tipo' => $p->tipo,
                    'detalhes' => $p->detalhes,
                    'valor' =>  number_format( $p->valor/ 100, 2, ',',''),
                    'ativado_hoje' => $p->ativado_hoje,
                    'botaoSalvar' => 0,
                ];
	    }
       
        $config = ConfigUser::where('chave', 'guarnicoes')->first();

        if($config){
            $this->guarnicoes_option = true;
            $guarnicoes = Guarnicoes::all();

            foreach($guarnicoes as $guarnicao){
                $this->guarnicoes[] = 
                    [
                        'id' => $guarnicao->id,
                        'nome' => $guarnicao->nome,
                        'botaoSalvar' => 0,
                    ];
            }
        }
     
       
    }

    //Image Section
    use WithFileUploads;
    public $photo,$photoStatus,$photoBebida;
    public $bebidaPath;

    public function removeImage($path)
    {  
        $path = substr($path, 8);//remove "storage/"
        if(Storage::disk('public')->exists($path)){
            Storage::disk('public')->delete($path);
        }
    }

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:2048', // 1MB Max
        ],
        //mensagens:
        [
            'photo.image' => 'Somente arquivos tipo imagem!',
            'photo.max' => 'Tamanho excedeu o limite!'
        ]);
        //se n tiver lista selecionada
        if($this->currentLista){
            $imagemAntiga = ListaProdutos::where('id' ,$this->currentLista)->first(['imagem']);
            $path = $this->photo->store('public');//upload
            $path = substr($path, 7);//path sem 'public/'
            $path = "storage/". $path;
            $update = ListaProdutos::where('id' ,$this->currentLista)
            ->update(['imagem' => $path]);
            if($update){
               $this->removeImage($imagemAntiga->imagem); 
               return redirect('/config')->with('message', 'Imagem salva!');
            }
        }
        
    }
    public function saveImageBebida()
    {
        $this->validate([
            'photoBebida' => 'image|max:2048', // 1MB Max
        ],
        //mensagens:
        [
            'photoBebida.image' => 'Somente arquivos tipo imagem!',
            'photoBebida.max' => 'Tamanho excedeu o limite!'
        ]);
        //se n tiver lista selecionada
        $file_name = 'bebidas_'. $this->userLogado . '.png';
        $this->removeImage($this->bebidaPath); 
        $path = $this->photoBebida->storeAs('public', $file_name);//upload
        return redirect('/config')->with('message', 'Imagem salva!');
        
        
    }
    //END Image Section
    
    
    public $listaProdutos = [];
    public $currentLista;

    public function updateLista(){
        
        if($this->currentLista){
            ListaProdutos::where('id', '<>', $this->currentLista)
            ->update(['ativado_hoje' => 0]);

            ListaProdutos::where('id', $this->currentLista)
            ->update(['ativado_hoje' => 1]);

            return redirect('/config')->with('message', 'Essa lista foi ativada!');
        }
    }

   
    //END SESSAO LISTA

    //produtos section
    public $produtos = [];

    public function addProduto()
    {
        $this->produtos[] = [
            'id' => '',
            'lista_id' => $this->currentLista,
            'tipo' =>'',
            'detalhes' => '',
            'valor' => 0,
            'ativado_hoje' => 1,
            'botaoSalvar' => 1,
        ];

    }

    public function botaoSalvarProdutos($index)
    {
        $this->produtos[$index]['botaoSalvar'] = 1;
    }

    public function salvarProdutos($index){
        try{
       
            if($this->produtos[$index]['id'] == ""){
                Produtos::insert(  [
                    'user_id'=> $this->userLogado,
                    'lista_id' => $this->produtos[$index]['lista_id'],
                    'tipo' => $this->produtos[$index]['tipo'],
                    'detalhes' => $this->produtos[$index]['detalhes'],
                    'valor' =>  MyHelpers::formatPreco($this->produtos[$index]['valor']),
                    'ativado_hoje' => $this->produtos[$index]['ativado_hoje'],
                ]);
                $this->produtos[$index]['id'] = DB::getPdo()->lastInsertId();
            }else {
                DB::table('produtos')
                ->updateOrInsert(
                    ['id' => $this->produtos[$index]['id'], 'user_id' => $this->userLogado ],
                    [
                        'user_id' => $this->userLogado,
                        'lista_id' => $this->produtos[$index]['lista_id'],
                        'tipo' => $this->produtos[$index]['tipo'],
                        'detalhes' => $this->produtos[$index]['detalhes'],
                        'valor' => MyHelpers::formatPreco($this->produtos[$index]['valor']),
                        'ativado_hoje' => $this->produtos[$index]['ativado_hoje'],
                    ]
                );
            }
            $this->produtos[$index]['botaoSalvar'] = 0;
        }catch(Exception $e){

        }//
    }
    public function deleteProduto($index)
    {
        try{
            $deleted = Produtos::where('id', $this->produtos[$index]['id'])->delete();
            
            unset($this->produtos[$index]);
            $this->produtos = array_values($this->produtos);
        }catch(\Exception $e){
            //
        }
   
    }
    // END produtos section

    //SESSAO BEBIDAS
    public $bebidas = [];
    public function addBebida()
    {
        $this->bebidas[] = [
            'id' => '',
            'nome' =>'',
            'valor' => 0,
            'ativado_hoje' => 1,
            'botaoSalvar' => 1,
        ];

    }

    public function botaoSalvarBebidas($index)
    {
        $this->bebidas[$index]['botaoSalvar'] = 1;
    }

    public function salvarBebidas($index){
        try{
       
            if($this->bebidas[$index]['id'] == ""){
                Bebidas::insert(  [
                    'user_id' =>  $this->userLogado,
                    'nome' => $this->bebidas[$index]['nome'],
                    'valor' =>  MyHelpers::formatPreco($this->bebidas[$index]['valor']),
                    'ativado_hoje' => $this->bebidas[$index]['ativado_hoje'],
                ]);
                $this->bebidas[$index]['id'] = DB::getPdo()->lastInsertId();
            }else {
                DB::table('lista_bebidas')
                ->updateOrInsert(
                    ['id' => $this->bebidas[$index]['id'], 'user_id' =>  $this->userLogado ],
                    [
                        'user_id' =>  $this->userLogado,
                        'nome' => $this->bebidas[$index]['nome'],
                        'valor' => MyHelpers::formatPreco($this->bebidas[$index]['valor']),
                        'ativado_hoje' => $this->bebidas[$index]['ativado_hoje'],
                    ]
                );
            }
             $this->bebidas[$index]['botaoSalvar'] = 0;

        }catch(Exception $e){

        }//
    }
    
    public function deleteBebida($index)
    {
        try{
            $deleted = Bebidas::where('id', $this->bebidas[$index]['id'])->delete();

            unset($this->bebidas[$index]);
            $this->bebidas = array_values($this->bebidas);
        }catch(\Exception $e){
            //
        }
   
    }
    // END SESSAO BEIBDAS
    
    public function removeProduto($index)
    {
        unset($this->produtos[$index]);
        $this->produtos = array_values($this->produtos);
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
    //Guarnicoes
    public $guarnicoes = [];
    public $guarnicoes_option = false;

    public function addGuarnicao()
    {
        $this->guarnicoes[] = [
            'id' => '',
            'nome' =>'',
            'botaoSalvar' => 1,
        ];

    }
    public function botaoSalvarGuarnicao($index)
    {
        $this->guarnicoes[$index]['botaoSalvar'] = 1;
    }

    public function salvarGuarnicao($index){
        try{
       
            if($this->guarnicoes[$index]['id'] == ""){
                Guarnicoes::insert(  [
                    'user_id' =>  $this->userLogado,
                    'nome' => $this->guarnicoes[$index]['nome'],
                ]);
                $this->guarnicoes[$index]['id'] = DB::getPdo()->lastInsertId();
            }else {
                DB::table('guarnicoes')
                ->updateOrInsert(
                    ['id' => $this->guarnicoes[$index]['id'], 'user_id' =>  $this->userLogado ],
                    [
                        'user_id' =>  $this->userLogado,
                        'nome' => $this->guarnicoes[$index]['nome'],
                    ]
                );
            }
             $this->guarnicoes[$index]['botaoSalvar'] = 0;

        }catch(Exception $e){

        }//
    }
    public function deleteGuarnicao($index)
    {
        try{
            $deleted = Guarnicoes::where('id', $this->guarnicoes[$index]['id'])->delete();

            unset($this->guarnicoes[$index]);
            $this->guarnicoes = array_values($this->guarnicoes);
        }catch(\Exception $e){
            //
        }
   
    }


    public function render()
    {
        return view('livewire.config-pedidos');
    }
}
