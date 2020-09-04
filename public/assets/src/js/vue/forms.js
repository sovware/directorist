export default [
    {
        action: './contact-us',
        method: 'GET',
        rows: [
            [
                {
                    options: { col_size: 6 },
                    widgets: [
                        {
                            name: 'text',
                            options: {
                                name: 'first_name',
                                label: 'First Name',
                                value: '',
                            }
                            
                        },
                    ]
                },
                {
                    options: { col_size: 6 },
                    widgets: [
                        {
                            name: 'text',
                            options: {
                                name: 'last_name',
                                label: 'Last Name',
                                value: '',
                            }
                        }
                    ]
                },
            ],
            [
                {
                    options: { col_size: 8 },
                    widgets: [
                        {
                            name: 'text',
                            options: {
                                name: 'subject',
                                label: 'Subject',
                                value: '',
                            }
                            
                        },
                    ]
                },
                {
                    options: { col_size: 4 },
                    widgets: [
                        {
                            name: 'text',
                            options: {
                                name: 'subject',
                                label: 'Subject',
                                value: '',
                            }
                            
                        },
                    ]
                },
            ],
            [
                {
                    options: { col_size: 12 },
                    widgets: [
                        {
                            name: 'textarea',
                            options: {
                                name: 'message',
                                label: 'Message',
                                value: '',
                            }
                            
                        },
                    ]
                },
            ],
            [
                {
                    options: { col_size: 6 },
                    widgets: [
                        {
                            name: 'button',
                            options: {
                                type: 'submit',
                                size: 'full',
                                text: 'Submit',
                                style: 'primary',
                            }
                            
                        },
                    ]
                },
                {
                    options: { col_size: 6 },
                    widgets: [
                        {
                            name: 'button',
                            options: {
                                type: 'reset',
                                size: 'full',
                                text: 'Reset',
                                style: 'secondary',
                            }
                            
                        },
                    ]
                },
            ],
        ],
    }
];