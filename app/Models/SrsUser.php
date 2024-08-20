<?php

namespace App\Models;

use App\Models\SrsHoa;
use App\Models\cp\CpPermission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SrsUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'srs_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_logged_in',
        'location',
        'login_at',
        'ip_address',
        'passcode',
        'generated_at',
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
        'login_at' => 'datetime',
        'generated_at' => 'datetime',
    ];

    public function isSuperAdmin()
    {
        return auth()->id() == 1;
    }

    public function isHoaApprover()
    {
        return $this->role_id == 7;
    }

    public function userRole()
    {
        // return $this->hasOne(SrsRole::class, 'id', 'role_id');
        return $this->belongsTo(SrsRole::class, 'role_id');
    }

    public function cpPermissions()
    {
        return $this->belongsToMany(CpPermission::class, 'cp_permission_user', 'user_id', 'permission_id')->withTimestamps();
    }

    public function hoa()
    {
        return $this->belongsToMany(SrsHoa::class, 'map_usr2hoa', 'user_id', 'hoa_id')->withPivot('is_active')->withTimestamps()->wherePivot('is_active', 1);

        // return $this->hasOne(SrsHoa::class, 'id', 'hoa_id');
    }

    public function dailyReports()
    {
        return $this->hasMany(CashierReports::class, 'cashier_id', 'id');
    }
}
