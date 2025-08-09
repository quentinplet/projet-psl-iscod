<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Si vous n'avez AUCUNE page web rendue par Laravel, vous pouvez laisser ce fichier vide
// ou y laisser une route de base pour catch-all ou un message simple pour les requêtes directes
// qui n'atteignent pas votre frontend SPA (Single Page Application).

// Exemple de route par défaut si quelqu'un accède à la racine sans le frontend Vue.js chargé:
Route::get('/', function () {
    return response('API Only - Access via your frontend application.', 200);
});

// Ou simplement le laisser vide