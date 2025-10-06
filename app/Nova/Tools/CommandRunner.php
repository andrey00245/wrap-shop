<?php

namespace App\Nova\Tools;

use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class CommandRunner extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('command-runner', __DIR__.'/../../../public/vendor/command-runner/tool.js');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function menu(\Illuminate\Http\Request $request)
    {
        return MenuSection::make('Команди')
            ->path('/command-runner')
            ->icon('terminal');
    }
}

