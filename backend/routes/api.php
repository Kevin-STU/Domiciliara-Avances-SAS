<?php
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('update-password', [LoginController::class, 'updatePassword']);
    
    Route::get('historias', [HistoriaController::class, 'index']);
    Route::post('historias', [HistoriaController::class, 'store']);
    Route::get('historias/{id}', [HistoriaController::class, 'show']);
    Route::put('historias/{id}', [HistoriaController::class, 'update']);
    Route::delete('historias/{id}', [HistoriaController::class, 'destroy']);

    //btener la lista de pacientes
    Route::get('patients', [UserController::class, 'getPatients']);
    
    //actualizar la información del usuario
    Route::put('user', [UserController::class, 'update']);

    //Rutas para el paciente
    Route::get('patient/historias', [HistoriaController::class, 'getHistoriasForPatient']);
    Route::post('patient/historias/{id}/asistida', [HistoriaController::class, 'markAsAssisted']);

    //Ruta para obtener notificaciones
    Route::get('notifications', function (Request $request) {
        return $request->user()->notifications;
    });
});


