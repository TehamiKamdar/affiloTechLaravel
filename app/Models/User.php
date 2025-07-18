<?php
namespace App\Models;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use ProtoneMedia\LaravelVerifyNewEmail\PendingUserEmail;
use Spatie\Permission\Traits\HasRoles;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;

class User extends Authenticatable
{
    use MustVerifyNewEmail, Notifiable, HasRoles, SoftDeletes;

    const SUPER_ADMIN = "\x73\x75\x70\x65\x72\x5f\x61\x64\x6d\x69\x6e";
    const ADMIN = "\x61\x64\x6d\x69\x6e";
    const STAFF = "\x73\x74\x61\x66\x66";
    const INTERNAL_EMPLOYEE = "\x69\x6e\x74\x65\x72\x6e\x61\x6c\x5f\x65\x6d\x70\x6c\x6f\x79\x65\x65";
    const ADVERTISER = "\x61\x64\x76\x65\x72\x74\x69\x73\x65\x72";
    const PUBLISHER = "\x70\x75\x62\x6c\x69\x73\x68\x65\x72";

    const STATUS_PENDING = "\x70\x65\x6e\x64\x69\x6e\x67";
    const STATUS_ACTIVE = "\x61\x63\x74\x69\x76\x65";
    const STATUS_HOLD = "\x68\x6f\x6c\x64";
    const STATUS_REJECT = "\x72\x65\x6a\x65\x63\x74\x65\x64";
    
    
     const PENDING = "\x70\x65\x6e\x64\x69\x6e\x67";
    const ACTIVE = "\x61\x63\x74\x69\x76\x65";
    const HOLD = "\x68\x6f\x6c\x64";
    const REJECT = "\x72\x65\x6a\x65\x63\x74\x65\x64";

    protected $dates = [
        "updated_at", 
        "created_at"
    ];

    protected $fillable = [
        "publisher_id",
        "name",
        "user_name",
        "email",
        "password",
        "status",
        "verification_code",
        "is_completed",
        "force_logout",
        "uid",
        "active_website_id",
        "active_website_status",
        "new_email",
        "profile_complete_percentage",
        "profile_complete_section",
        "type",
        "api_token",
        "email_verified_at","recaptcha_response"
    ];

    protected $hidden = [
        "password", 
        "remember_token"
    ];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uid = self::generateUniqueId();
        });
    }

    public static function generateUniqueId()
    {
        do {
            $uniqueId = mt_rand(10000000, 99999999);
        } while (self::where("uid", $uniqueId)->exists());
        
        return $uniqueId;
    }
public function getRoleName()
    {
        return $this->roles->pluck('title')[0] ?? null;
    }
 public function getAllowed()
    {
        return $this->getRoleName() != Role::SUPER_ADMIN_ROLE && $this->getRoleName() != Role::ADMIN_ROLE;
    }
    public function getIsPublisherAttribute()
    {
        return $this->getRoleNames()[0] == self::PUBLISHER;
    }

    public function getIsAdvertiserAttribute()
    {
        return $this->getRoleNames()[0] == self::ADVERTISER;
    }

    public function getCheckWebsiteStatusActiveAttribute()
    {
        return $this->active_website_status == Website::ACTIVE;
    }

    public function getIsAdminAttribute()
    {
        return $this->getRoleNames()[0] == self::ADMIN || $this->getRoleNames()[0] == self::SUPER_ADMIN;
    }

    public function generateVerificationCode()
    {
        $this->verification_code = rand(100000, 999999);
        $this->save();
    }

    public function sendEmailVerificationNotificationCode()
    {
         Mail::to($this->email)->send(new VerifyEmail($this->verification_code));
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function websites()
    {
        return $this->hasMany(Website::class, "user_id", "id");
    }

    public function active_website()
    {
        return $this->hasOne(Website::class, "id", "active_website_id");
    }

    public function publisher()
    {
        return $this->hasOne(Publisher::class, "user_id", "publisher_id");
    }

    public function mediakits()
    {
        return $this->hasMany(Mediakit::class, "user_id", "id");
    }
}
