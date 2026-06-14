<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'slug',
        'store_name',
        'store_description',
        'whatsapp_number',
        'instagram_url',
        'tiktok_url',
        'shopee_url',
        'tokopedia_url',
        'store_logo',
        'google_id',
        'avatar',
        'plan',
    ];

    public static function generateUniqueSlug($source, $column = 'slug', $ignoreId = null)
    {
        $slug = Str::slug($source);
        $original = $slug;
        $counter = 2;
        $query = static::where($column, $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        while ($query->exists()) {
            $slug = $original . '-' . $counter++;
            $query = static::where($column, $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }
        return $slug;
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = static::generateUniqueSlug($user->name, 'slug');
            }
            if (empty($user->store_slug)) {
                $source = $user->store_name ?: $user->name;
                $user->store_slug = static::generateUniqueSlug($source, 'store_slug');
            }
        });

        static::saving(function ($user) {
            if ($user->exists && $user->isDirty('store_name')) {
                $source = $user->store_name ?: $user->name;
                $user->store_slug = static::generateUniqueSlug($source, 'store_slug', $user->id);
            }
        });
    }

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

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function catalogVisits(): HasMany
    {
        return $this->hasMany(CatalogVisit::class);
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('sort_order');
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MerchantSubscription::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(MerchantSubscription::class)->where('status', 'active');
    }

    public function inclusiveProgramApplications(): HasMany
    {
        return $this->hasMany(InclusiveProgramApplication::class);
    }

    public function isPro(): bool
    {
        return $this->plan === 'pro';
    }

    public function isFree(): bool
    {
        return $this->plan === 'free';
    }

    public function canCreateProduct(): bool
    {
        if ($this->isPro()) {
            return true;
        }

        return $this->products()->count() < 10;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMerchant(): bool
    {
        return $this->role === 'merchant';
    }
}
