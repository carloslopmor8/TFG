<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function proyectos_asignados() {
        $proyectosAssignedToUser = Proyecto::whereHas('usuarios', function ($query) {
            $query->where('user_id', $this->id);
        })->get();
    
        $proyectosCreatedByUser = Proyecto::where('user_id', $this->id)->get();
    
        $proyectos = $proyectosAssignedToUser->concat($proyectosCreatedByUser);
    
        return $proyectos;
    }
    
    public function proyectos_admin() {
        $proyectosAdminByUser = Proyecto::whereHas('usuarios', function ($query) {
            $query->where('user_id', $this->id)->where('is_admin', true);
        })->get();
    
        $proyectosCreatedByUser = Proyecto::where('user_id', $this->id)->get();
    
        $proyectos = $proyectosAdminByUser->concat($proyectosCreatedByUser);
    
        return $proyectos;
    }
    
}