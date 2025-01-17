<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CategoryMenu extends Component
{
    public $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function render()
    {
        return view('components.category-menu');
    }
}
