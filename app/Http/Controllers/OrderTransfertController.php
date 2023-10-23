<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItensPedido;
use App\Models\Pedido;
use DateTime;
class OrderTransfertController extends Controller
{
    
    public function getTransfertOrderItems($id)
    {
        $items = DB::table('itens_pedido')
                ->select(
                    'itens_pedido.item_pedido',
                    'itens_pedido.item_id',
                    'itens_pedido.item_quantidade',
                    'menuitems.item_name'
                )
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where('item_pedido', $id)
                            ->get();

        return response()->json($items);
    }

    public function postTransfert(Request $request)
    {
        $items_id = $request->item_id;
        $item_quantidade = $request->item_quantidade;

        $waiter = Pedido::where('id', $request->item_pedido)
            ->first();

        $hoje = new DateTime();

        $order = new Pedido();
        $order->ped_tableNumber = $request->ped_tableNumber;
        $order->ped_customerName = "Transfered";
        $order->user_id = $waiter->user_id;
        $order->ped_emissao = $hoje->format("Y-m-d H:i:s");
        $order->status_id = 6;
        $order->save();

        foreach ($items_id as $key=>$ids):
            $item = ItensPedido::where('item_pedido', $waiter->id)->where('item_id', $ids)
                ->first();
                //foreach ($item_quantidade as $qty):
                    $qty = $item_quantidade[$key];
                    if ($item->item_quantidade == $qty):
                        DB::table('itens_pedido')
                            ->where('item_pedido', $request->item_pedido)
                                ->where('item_id', $ids)
                                    ->update([
                                        'item_pedido' => $order->id,
                                    ]);
                    else:
                        DB::table('itens_pedido')
                            ->where('item_pedido', $request->item_pedido)
                            ->where('item_id', $ids)
                                ->update([
                                    'item_quantidade' => $item->item_quantidade - $qty,
                                ]);     
                        $tintens = new ItensPedido();
                        $tintens->item_emissao = $item->item_emissao;
                        $tintens->item_quantidade = $qty;
                        $tintens->item_id = $item->item_id;
                        $tintens->item_price = $item->item_price;
                        $tintens->item_total = $item->item_price * $qty;
                        $tintens->item_comments = $item->item_comments;
                        $tintens->item_option = $item->item_option;
                        $tintens->item_pedido = $order->id;
                        $tintens->save();
                    endif;
                //endforeach;
        endforeach;
        
        return response()->json($item_quantidade);
    }

    public function getReport()
    {
        $hoje = new DateTime();
        $hoje = $hoje->format('Y-m-d');

        $report = DB::table('itens_pedido')
            ->select(
                'menuitems.item_name',
                DB::raw('SUM(itens_pedido.item_quantidade) AS quantidade'),
                DB::raw('SUM(.itens_pedido.item_total) AS total')
            )
                ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                    ->join('pedidos', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                        ->where('item_emissao', $hoje)
                            ->where([['itens_pedido.item_delete', false], ['pedidos.status_id', '<>', 6]])
                                ->groupby(
                                    'menuitems.item_name'
                                )
                                    ->get();

        return response()->json($report);
    }
}
