<?php

return [
    [
        'username' => [
            'type'       => 'text',
            'required'   => true,
            'attributes' => [
                'placeholder' => 'Username',
                'class'       => 'form-control login-field'
            ]
        ],
        'password' => [
            'type'       => 'password',
            'required'   => true,
            'attributes' => [
                'placeholder' => 'Password',
                'class'       => 'form-control login-field'
            ]
        ]
    ],
    [
        'submit' => [
            'type'  => 'submit',
            'value' => 'Login',
            'attributes' => [
                'class'  => 'btn btn-lg btn-info btn-block text-uppercase'
            ]
        ]
    ]
];
