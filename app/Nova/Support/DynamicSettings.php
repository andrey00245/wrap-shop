<?php

namespace App\Nova\Support;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Valuestore\Valuestore;

class DynamicSettings
{
  protected $store;

  public function __construct()
  {
    $this->store = Valuestore::make(
      config('nova-settings-tool.path', storage_path('app/settings.json'))
    );
  }

  public function getPhone(): string
  {
    return $this->store->get('phone') ?: '#';
  }

  public function getAditionalPhone(): string
  {
    return $this->store->get('phone_aditional') ?: '#';
  }

  public function getPhoneView(): string
  {
    return $this->store->get('phone_view') ?: '#';
  }
  public function getAditionalPhoneView(): string
  {
    return $this->store->get('phone_aditional_view') ?: '#';
  }

  public function getTelegramUrl(): string
  {
    return $this->store->get('telegram') ?: '#';
  }

  public function getInstagramUrl(): string
  {
    return $this->store->get('instagram') ?: '#';
  }

  public function getEmail(): string
  {
    return $this->store->get('email') ?: '#';
  }

  public function getAddress(): string
  {
    return $this->store->get('address_'.LaravelLocalization::getCurrentLocale()) ?: '#';
  }

  public function getGoogleMapsUrl(): string
  {
    return $this->store->get('google_map_link') ?: '#';
  }

}
