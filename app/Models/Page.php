<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Datlechin\FilamentMenuBuilder\Contracts\MenuPanelable;

class Page extends Model implements MenuPanelable
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getMenuPanelName(): string
    {
        return 'Pages';
    }

    public function getMenuPanelTitleColumn(): string
    {
        return 'title';
    }

    public function getMenuPanelUrlUsing(): callable
    {
        return fn (self $model) => url('page/' . $model->slug);
    }

    public function getMenuPanelModifyQueryUsing(): callable
    {
        return fn ($query) => $query->where('is_active', true);
    }
}
