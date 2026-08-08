<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'logo', 'dark_logo', 'favicon', 'site_title', 'site_subtitle',
        'address', 'email', 'phone', 'google_maps_url',
        'about_title', 'about_content', 'about_button_url',
        'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
        'smtp_encryption', 'mail_from_address', 'mail_from_name',
        'whatsapp_number', 'whatsapp_api_key',
        'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url',
        'copyright',
    ];
}
