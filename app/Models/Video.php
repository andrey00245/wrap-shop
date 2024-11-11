<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
  protected $fillable = ['name', 'file', 'disk'];

  protected static function booted(): void
  {
    parent::booted();

    self::saving(function($model) {
      $hasLaruploadTrait = method_exists(self::class, 'bootLarupload');

      if (!$model->name) {
        $name = $hasLaruploadTrait ? $model->file->meta('name') : $model->file;

        $model->name = pathinfo($name, PATHINFO_FILENAME);
      }
    });
  }
}
