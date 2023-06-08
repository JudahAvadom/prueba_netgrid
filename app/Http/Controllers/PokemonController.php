<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favoritos;
use Illuminate\Support\Facades\DB;

class PokemonController extends Controller
{
    public function savePokemon(Request $request){
        $id = Auth::id();
        $pokemon = $request->pokemon;
        $query = DB::table('favoritos')->get()->where('id',$id)->where('ref_api',$pokemon);
        if (count($query) !== 0) {
            return "Favorite already exists";
        }
        else {
            Favoritos::create([
                'id'=> $id = Auth::id(),
                'ref_api'=>$pokemon = $request->pokemon
            ]);
            return "Success";
        }
    }

    public function listPokemon(Request $request){
        $id = Auth::id();
        $query = DB::table('favoritos')->get()->where('id',$id);
        return $query;
    }

    public function removePokemon(Request $request){
        $id = Auth::id();
        $pokemon = $request->pokemon;
        DB::table('favoritos')->where('id',$id)->where('ref_api',$pokemon)->delete();
        return "Deleted";
    }
}
