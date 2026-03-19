<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_number',
        'address',
        'gender',
        'occupation',
        'photo'
    ];

    public $timestamps = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function adminlte_image()
{
    if (!empty($this->photo)) {
        return asset('profile_photos/' . $this->photo);
    }

    return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
}

    public function adminlte_desc()
    {
        return $this->email;
    }

    public function adminlte_profile_url()
    {
        return route('profile.edit');
    }
}