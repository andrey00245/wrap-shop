<?php

return [
    /** Максимальний розмір одного зображення в Nova (кілобайти). 131072 КБ = 128 МБ. */
    'max_upload_kb' => (int) env('NOVA_MAX_IMAGE_UPLOAD_KB', 131072),

    'default-croppable' => true,
    'enable-existing-media' => false,
    'hide-media-collections' => [],
];
