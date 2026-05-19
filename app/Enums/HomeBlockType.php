<?php

namespace App\Enums;

enum HomeBlockType: string
{
    case Categories = 'categories';
    case Products = 'products';
    case Banner = 'banner';
    case Custom = 'custom';
}
