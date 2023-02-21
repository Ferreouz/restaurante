<?php

namespace App\Http\Livewire;

use App\Models\Pedido;
use Livewire\Component;
use App\Helpers\MyHelpers;

class ModalPedido extends Component
{
    protected $listeners = ['ModalPedido:updateModal'=>'updateModal'];
    public $pedido;

    public function render()
    {
        return view('livewire.modal-pedido');
    }
    public function updateModal(Int $pedidoID)
    {
        // $this->pedido = Pedido::where('id', $pedidoID)->firstOrFail();
        $this->pedido = Pedido::with(['produtos_pedidos','usuarios_pedidos'])
        ->where('id', $pedidoID)
        ->first();
        // dd($this->pedido);
        $this->dispatchBrowserEvent('modal-loaded',[]);
    }

    public function imprimirNovamente()
    {
        if($this->pedido){
            $lista = MyHelpers::formatarImpressao($this->pedido);
            $this->dispatchBrowserEvent('pedidoAdicionado',['arrayPedido' => [
                'numero'=>$lista['id'],
                'nome' => $lista['nome'],
                'data' => $lista['data'],
                'whatsapp' => $lista['whatsapp'],
                'endereco'=>$lista['endereco'],
                'retirar_entregar'=>$lista['retirar_entregar'],
                'ListaPedido'=>$lista['ListaPedido'],
                'ValorTotalcomFrete'=>$lista['ValorTotalcomFrete'],
                'FormaPgto'=> $lista['FormaPgto'],
                'troco' => $lista['troco'],
            ]]);
        }
    }

}
