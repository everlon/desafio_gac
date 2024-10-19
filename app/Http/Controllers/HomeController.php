<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\CarteiraService;

class HomeController extends Controller
{
    public function dashboard(CarteiraService $carteiraService)
    {
        $user = Auth::user();

        $balance = $carteiraService->getBalance($user);

        return view('dashboard', ['saldo' => $balance]);
    }
}
