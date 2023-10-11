<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreUserRequest;
use App\Models\ItensPedido;

class UserController extends Controller
{
    protected $user;
    CONST USER_ID = 1;
    CONST IS_CANCEL = true;

    public function __construct()
    {
        
    }

    public function getGroup()
    {
        $group = DB::table('users_group')
            ->select('*')
                ->get();

        return response()->json($group);
    }

    public function create(StoreUserRequest $request)
    {
        $request->validated();

        $values = $request->all();
        $create = new User($values);
        $create->password = Hash::make($request->password);

        try {

            DB::beginTransaction();
            $create->save();
            DB::commit();

            return response()->json("Usuario criado com sucesso !");

        }catch(Exception $e){

            echo "Usuario não pode ser cadastrado";
            DB::rollBack();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msgerr' => ['senha ou usuario incoreto']
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function CancelPermission(Request $request, $item_pedido, $item_id)
    {
        
        $password = User::where('id', self::USER_ID)
            ->first();

        $item = ItensPedido::where('item_pedido', $item_pedido)
            ->where('item_id', $item_id)
                ->first();

        if (Hash::check($request->password, $password->password)):
            DB::table('itens_pedido')
                ->where('item_pedido', $item_pedido)
                    ->where('item_id', $item_id)
                        ->update([
                            'item_delete'=> self::IS_CANCEL,
                            'item_total' => $item->item_total * 0
                        ]);

            return response()->json("Item cancelado com sucesso");
        endif;

        return response()->json("Senha invalida !");
    }
}
