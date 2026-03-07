<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Support\Facades\DB; 

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPets = Pet::count();
        $totalAdocoes = AdoptionRequest::count();
        $adocoesAprovadas = AdoptionRequest::where('status', 'aprovado')->count();

        $adocoesPorStatus = AdoptionRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $petsPorEspecie = Pet::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->pluck('total', 'tipo')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPets', 'totalAdocoes', 'adocoesAprovadas', 
            'adocoesPorStatus', 'petsPorEspecie'
        ));
    }

    public function chartData(Request $request)
    {
        $periodo = $request->get('periodo', 'mensal');
        $anoAtual = now()->year;
        
        $query = AdoptionRequest::where('status', 'aprovado');

        if ($periodo !== 'anual') {
            $query->whereYear('created_at', $anoAtual);
        }

        $adocoesAprovadas = $query->get(); 

        $labels = [];
        $data = [];

        if ($periodo === 'mensal') {
            $agrupado = $adocoesAprovadas->groupBy(fn($item) => $item->created_at->format('n'));
            
            $mesesNomes = [1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = $mesesNomes[$i];
                $data[] = $agrupado->has($i) ? $agrupado->get($i)->count() : 0;
            }

        } elseif ($periodo === 'bimestral') {
            $agrupado = $adocoesAprovadas->groupBy(fn($item) => ceil($item->created_at->format('n') / 2));
            for ($i = 1; $i <= 6; $i++) {
                $labels[] = $i . 'º Bimestre';
                $data[] = $agrupado->has($i) ? $agrupado->get($i)->count() : 0;
            }

        } elseif ($periodo === 'trimestral') {
            $agrupado = $adocoesAprovadas->groupBy(fn($item) => ceil($item->created_at->format('n') / 3));
            for ($i = 1; $i <= 4; $i++) {
                $labels[] = $i . 'º Trimestre';
                $data[] = $agrupado->has($i) ? $agrupado->get($i)->count() : 0;
            }

        } elseif ($periodo === 'anual') {
            $agrupado = $adocoesAprovadas->groupBy(fn($item) => $item->created_at->format('Y'));
            $anoInicial = $anoAtual - 4;
            for ($i = $anoInicial; $i <= $anoAtual; $i++) {
                $labels[] = (string) $i;
                $data[] = $agrupado->has($i) ? $agrupado->get($i)->count() : 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}