<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Vente;
use Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    public function vendre(Request $request)
    {
        if (Auth::user()) {
            if ($request -> isMethod('POST')) {
                $request->validate([
                    'idcli' => 'required',
                    'idpro' => 'required',
                    'qtevente' => 'required',
                    'datevente' => 'required',
                    'prixVente' => 'required',
                ]);
                Vente::create($request->all());
                return redirect('/ventes')->with('message', 'Produit vendu avec succès');
            } else {
                $clients = Client::latest()->paginate(100);
                $produits = Produit::latest()->paginate(100);

                return view('ventes.form', ['clients'=>$clients, 'produits'=>$produits]);
            }
        } else {
            return redirect('/login');
        }
    }

    public function list()
    {
        $ventes = DB::table('ventes')
            ->join('produits', 'ventes.idpro', '=', 'produits.idpro')
            ->join('clients', 'ventes.idcli', '=', 'clients.idcli')
            ->select('ventes.*', 'produits.*', 'clients.*')
            ->get();

        return view('ventes.list', ['ventes'=>$ventes]);
    }

}
