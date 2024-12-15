<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Settings Path
  |--------------------------------------------------------------------------
  |
  | Path to the JSON file where settings are stored.
  |
  */

  'path' => storage_path('app/settings.json'),

  /*
  |--------------------------------------------------------------------------
  | Sidebar Label
  |--------------------------------------------------------------------------
  |
  | The text that Nova displays for this tool in the navigation sidebar.
  |
  */

  'sidebar-label' => 'Налаштування',

  /*
  |--------------------------------------------------------------------------
  | Title
  |--------------------------------------------------------------------------
  |
  | The browser/meta page title for the tool.
  |
  */

  'title' => 'Налаштування',

  /*
  |--------------------------------------------------------------------------
  | Settings
  |--------------------------------------------------------------------------
  |
  | The good stuff :). Each setting defined here will render a field in the
  | tool. The only required key is `key`, other available keys include `type`,
  | `label`, `help`, `placeholder`, `language`, and `panel`.
  |
  */

  'settings' => [

    [
      'key' => 'phone',
      'label' => 'Номер телефона для дзвінка',
      'panel' => 'Контакти',
    ],
    [
      'key' => 'phone_view',
      'label' => 'Номер телефона для відображення',
      'panel' => 'Контакти',
    ],

    [
      'key' => 'phone_aditional',
      'label' => 'Додатковий номер телефона для дзвінка',
      'panel' => 'Контакти',
    ],

    [
      'key' => 'phone_aditional_view',
      'label' => 'Додатковий номер для відображення',
      'panel' => 'Контакти',
    ],
    [
      'key' => 'telegram',
      'label' => 'Посилання на телеграм',
      'panel' => 'Контакти',
    ],
    [
      'key' => 'instagram',
      'label' => 'Посилання на інстаграм',
      'panel' => 'Контакти',
    ],
    [
      'key' => 'email',
      'label' => 'Email',
      'panel' => 'Контакти',
    ],
    [
      'key' => 'address_uk',
      'label' => 'Адреса офісу[UK]',
      'panel' => 'Адреса',
    ],
    [
      'key' => 'address_en',
      'label' => 'Адреса офісу[EN]',
      'panel' => 'Адреса',
    ],
    [
      'key' => 'address_ru',
      'label' => 'Адреса офісу[RU]',
      'panel' => 'Адреса',
    ],
    [
      'key' => 'google_map_link',
      'label' => 'Посилання на гугл карти',
      'panel' => 'Адреса',
    ],

    [
      'key' => 'video_banner_title_uk',
      'label' => 'Відеобанер заголовок[UK]',
      'panel' => 'Відеобанер',
    ],
    [
      'key' => 'video_banner_title_ru',
      'label' => 'Відеобанер заголовок[RU]',
      'panel' => 'Відеобанер',
    ],
    [
      'key' => 'video_banner_title_en',
      'label' => 'Відеобанер заголовок[EN]',
      'panel' => 'Відеобанер',
    ],

    [
      'key' => 'video_banner_desc_uk',
      'label' => 'Відеобанер опис[UK]',
      'panel' => 'Відеобанер',
      'type' => 'textarea'
    ],
    [
      'key' => 'video_banner_desc_ru',
      'label' => 'Відеобанер опис[RU]',
      'panel' => 'Відеобанер',
      'type' => 'textarea'

    ],
    [
      'key' => 'video_banner_desc_en',
      'label' => 'Відеобанер опис[EN]',
      'panel' => 'Відеобанер',
      'type' => 'textarea'

    ],


    [
      'key' => 'slogan_title_uk',
      'label' => 'Відеобанер заголовок[UK]',
      'panel' => 'Слоган',
    ],
    [
      'key' => 'slogan_title_ru',
      'label' => 'Відеобанер заголовок[RU]',
      'panel' => 'Слоган',
    ],
    [
      'key' => 'slogan_title_en',
      'label' => 'Відеобанер заголовок[EN]',
      'panel' => 'Слоган',
    ],

    [
      'key' => 'slogan_desc_uk',
      'label' => 'Відеобанер опис[UK]',
      'panel' => 'Слоган',
      'type' => 'textarea'

    ],
    [
      'key' => 'slogan_desc_ru',
      'label' => 'Відеобанер опис[RU]',
      'panel' => 'Слоган',
      'type' => 'textarea'

    ],
    [
      'key' => 'slogan_desc_en',
      'label' => 'Відеобанер опис[EN]',
      'panel' => 'Слоган',
      'type' => 'textarea'

    ],

  ],

];
