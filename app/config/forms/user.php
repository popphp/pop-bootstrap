<?php

return [
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
        'password' => [
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
    ],
    [
        'active' => [
            'type'      => 'radio',
            'label'     => 'Active',
            'values' => [
                '1' => 'Yes',
                '0' => 'No'
            ],
            'checked' => 0
        ],
        'attempts'   => [
            'type'  => 'text',
            'label' => 'Failed Attempts',
            'value' => 0,
            'attributes' => [
                'class' => 'form-control form-control-inline input-sm',
                'size'  => 2
            ]
        ],
        'id' => [
            'type'  => 'hidden',
            'value' => '0'
        ]
    ],
    [
        'submit' => [
            'type'       => 'submit',
            'value'      => 'Save',
            'attributes' => [
                'class'  => 'btn btn-md btn-info btn-block btn-save text-uppercase'
            ]
        ]
    ]
];