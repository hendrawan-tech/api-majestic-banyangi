<?php

return [
    'common' => [
        'actions' => 'Actions',
        'create' => 'Create',
        'edit' => 'Edit',
        'update' => 'Update',
        'new' => 'New',
        'cancel' => 'Cancel',
        'attach' => 'Attach',
        'detach' => 'Detach',
        'save' => 'Save',
        'delete' => 'Delete',
        'delete_selected' => 'Delete selected',
        'search' => 'Search...',
        'back' => 'Back to Index',
        'are_you_sure' => 'Are you sure?',
        'no_items_found' => 'No items found',
        'created' => 'Successfully created',
        'saved' => 'Saved successfully',
        'removed' => 'Successfully removed',
    ],

    'comments' => [
        'name' => 'Comments',
        'index_title' => 'Comments List',
        'new_title' => 'New Comment',
        'create_title' => 'Create Comment',
        'edit_title' => 'Edit Comment',
        'show_title' => 'Show Comment',
        'inputs' => [
            'comment' => 'Comment',
            'destination_id' => 'Destination',
            'user_id' => 'User',
        ],
    ],

    'likes' => [
        'name' => 'Likes',
        'index_title' => 'Likes List',
        'new_title' => 'New Like',
        'create_title' => 'Create Like',
        'edit_title' => 'Edit Like',
        'show_title' => 'Show Like',
        'inputs' => [
            'user_id' => 'User',
            'post_id' => 'Post',
        ],
    ],

    'payments' => [
        'name' => 'Payments',
        'index_title' => 'Payments List',
        'new_title' => 'New Payment',
        'create_title' => 'Create Payment',
        'edit_title' => 'Edit Payment',
        'show_title' => 'Show Payment',
        'inputs' => [
            'name' => 'Name',
            'number' => 'Number',
            'bank' => 'Bank',
            'image' => 'Image',
        ],
    ],

    'users' => [
        'name' => 'Users',
        'index_title' => 'Users List',
        'new_title' => 'New User',
        'create_title' => 'Create User',
        'edit_title' => 'Edit User',
        'show_title' => 'Show User',
        'inputs' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'phone_number' => 'Phone Number',
        ],
    ],

    'orders' => [
        'name' => 'Orders',
        'index_title' => 'Orders List',
        'new_title' => 'New Order',
        'create_title' => 'Create Order',
        'edit_title' => 'Edit Order',
        'show_title' => 'Show Order',
        'inputs' => [
            'code' => 'Code',
            'product_id' => 'Product',
            'user_id' => 'User',
            'payment_id' => 'Payment',
            'quantity' => 'Quantity',
            'total' => 'Total',
            'date' => 'Date',
            'status' => 'Status',
        ],
    ],

    'products' => [
        'name' => 'Products',
        'index_title' => 'Products List',
        'new_title' => 'New Product',
        'create_title' => 'Create Product',
        'edit_title' => 'Edit Product',
        'show_title' => 'Show Product',
        'inputs' => [
            'title' => 'Title',
            'category' => 'Category',
            'address' => 'Address',
            'description' => 'Description',
            'image' => 'Image',
            'price' => 'Price',
        ],
    ],

    'roles' => [
        'name' => 'Roles',
        'index_title' => 'Roles List',
        'create_title' => 'Create Role',
        'edit_title' => 'Edit Role',
        'show_title' => 'Show Role',
        'inputs' => [
            'name' => 'Name',
        ],
    ],

    'permissions' => [
        'name' => 'Permissions',
        'index_title' => 'Permissions List',
        'create_title' => 'Create Permission',
        'edit_title' => 'Edit Permission',
        'show_title' => 'Show Permission',
        'inputs' => [
            'name' => 'Name',
        ],
    ],
];
