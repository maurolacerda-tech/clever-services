<?php

namespace Modules\Banners\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['name', 'image', 'url', 'status', 'target', 'order', 'summary_01', 'summary_02', 'menu_id'];

    const STATUS = [
        'active' => 'Ativo',
        'inactive' => 'Inativo'
    ];

    const TARGET = [
        '_blank' => 'Outra página',
        '_self' => 'Mesmo quadro',
        '_parent' => 'Página pai',
        '_top' => 'Quadro topo'
    ];

}