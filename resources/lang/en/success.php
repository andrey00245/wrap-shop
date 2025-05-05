<?php
return [
    'update-account' => 'Successful: Your account has been successfully updated.',
    'update-password' => 'Your password has been successfully updated.',
    'add-address' => 'Your address was successfully added',
    'update-address' => 'Your address was successfully updated',
    'delete-address' => 'Your address was successfully deleted',
    'checkout' => [
        'your_order' => 'Your order has been accepted!',
        'your_order_description' => '<p>Your order has been accepted!</p>
            <p>Order history can be found in <a href="' . route("account") . '">Personal Area</a>.
            To view the history, please go to <a href="' . route("order") . '"> Order History </a>.</p >
            <p style="display:none">if your purchase is related to downloads, you can go to the <a href="#" >uploads</a> page in your personal cabinet to view them .</p >
            <p >if you have any questions, please <a href="' . route("contacts") . '">contact us</a>.</p>
            <p><strong>Thank you for shopping in our online store!</strong></p>',
        'continue' => 'Continue',
    ],
];
