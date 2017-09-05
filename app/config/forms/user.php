<?php

return [
    [
        'submit' => [
            'type'       => 'submit',
            'value'      => 'Save',
            'attributes' => [
                'class'  => 'btn btn-md btn-info btn-block text-uppercase'
            ]
        ],
        'active' => [
            'type'      => 'radio',
            'label'     => 'Active',
            'values' => [
                '1' => 'Yes',
                '0' => 'No'
            ],
            'checked' => 0
        ],
        'failed_attempts'   => [
            'type'  => 'text',
            'label' => 'Failed Attempts',
            'value' => 0,
            'attributes' => [
                'class' => 'form-control input-sm',
                'size'  => 3
            ]
        ],
        'id' => [
            'type'  => 'hidden',
            'value' => '0'
        ]
    ],
    [
        'username' => [
            'type'     => 'text',
            'label'    => 'Username',
            'required' => true,
            'attributes' => [
                'size'    => 40,
                'class'   => 'form-control'
            ]
        ],
        'password1' => [
            'type'       => 'password',
            'label'      => 'Password',
            'attributes' => [
                'size'    => 40,
                'class'   => 'form-control'
            ]
        ],
        'password2' => [
            'type'       => 'password',
            'label'      => 'Re-Type Password',
            'attributes' => [
                'size'    => 40,
                'class'   => 'form-control'
            ]
        ]
    ]
];