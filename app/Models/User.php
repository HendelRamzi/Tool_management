<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Observers\UserObserver;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * This function will take the first later of the user name. 
     * exp: John Doe -> JD
     * @return void
     */
    public static function GenerateUserName(string $fullname, Model $user = null)
    {
        // $nameParts = explode(' ', $fullname);  // Split the name into parts 
        // $initials = '';
        // foreach ($nameParts as $part) {
        //     $initials .= ucfirst($part[0]);  // Get the first letter of each part
        // }


        // Now logic, need to be tested
        $nameParts = preg_split('/\s+/', trim($fullname));
        $initials = '';
        foreach ($nameParts as $part) {
            $initials .= strtoupper($part[0]);
        }

        if (is_null($user)) {
            return $initials; // Return the initials without if not saving to database.
        }

        $user->name = $initials;
        $user->save();
    }


    public function fullName(): Attribute
    {
        return Attribute::get(function () {
            return $this->personal !== null ? $this->personal->first_name . ' ' . $this->personal->last_name : "/";
        });
    }

  
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }


    public function getFilamentName(): string
    {
        return $this->personal ? "{$this->personal->first_name} {$this->personal->last_name}" : $this->name;
    }

    public function personal()
    {
        return $this->morphOne(Personal::class, 'personal');
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }
}
