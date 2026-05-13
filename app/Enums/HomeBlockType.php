<?php

namespace App\Enums;

enum HomeBlockType: string
{
    case Categories = 'categories';
    case Kits = 'kits';
    case Products = 'products';
    case Banner = 'banner';
    case Custom = 'custom';
}
