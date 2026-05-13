<?php

namespace App\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

class SafeFileLoader extends FileLoader
{
    public function __construct(Filesystem $files, array|string $path)
    {
        parent::__construct($files, $path);
    }

    /**
     * Load a local namespaced translation group for overrides with type-safety.
     */
    protected function loadNamespaceOverrides(array $lines, $locale, $group, $namespace)
    {
        return collect($this->paths)
            ->reduce(function ($output, $path) use ($lines, $locale, $group, $namespace) {
                $file = "{$path}/vendor/{$namespace}/{$locale}/{$group}.php";

                if ($this->files->exists($file)) {
                    $override = $this->files->getRequire($file);

                    if (is_array($override)) {
                        $lines = array_replace_recursive($lines, $override);
                    }
                }

                return $lines;
            }, []);
    }

    /**
     * Load a locale from a given path with type-safety.
     */
    protected function loadPaths(array $paths, $locale, $group)
    {
        return collect($paths)
            ->reduce(function ($output, $path) use ($locale, $group) {
                if ($this->files->exists($full = "{$path}/{$locale}/{$group}.php")) {
                    $lines = $this->files->getRequire($full);

                    if (is_array($lines)) {
                        $output = array_replace_recursive($output, $lines);
                    }
                }

                return $output;
            }, []);
    }
}
