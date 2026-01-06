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

    'cows' => [
        'name' => 'Cows',
        'index_title' => 'Cows List',
        'new_title' => 'New Cow',
        'create_title' => 'Create Cow',
        'edit_title' => 'Edit Cow',
        'show_title' => 'Show Cow',
        'inputs' => [
            'number' => 'Number',
            'name' => 'Name',
            'gender' => 'Gender',
            'parent_id' => 'Parent Id',
            'mother_id' => 'Mother Id',
            'farm_id' => 'Farm',
            'owner' => 'Owner',
            'sold' => 'Sold',
            'picture' => 'Picture',
            'born' => 'Born',
        ],
    ],

    'cow_solds' => [
        'name' => 'Cow Solds',
        'index_title' => 'Solds List',
        'new_title' => 'New Sold',
        'create_title' => 'Create Sold',
        'edit_title' => 'Edit Sold',
        'show_title' => 'Show Sold',
        'inputs' => [
            'date' => 'Date',
            'pounds' => 'Pounds',
            'kilograms' => 'Kilograms',
            'price' => 'Price',
            'number_sold' => 'Number Sold',
        ],
    ],

    'cow_histories' => [
        'name' => 'Cow Histories',
        'index_title' => ' List',
        'new_title' => 'New Cow history',
        'create_title' => 'Create cow_history',
        'edit_title' => 'Edit cow_history',
        'show_title' => 'Show cow_history',
        'inputs' => [
            'history_id' => 'History',
        ],
    ],

    'cow_types' => [
        'name' => 'Cow Types',
        'index_title' => 'CowTypes List',
        'new_title' => 'New Cow type',
        'create_title' => 'Create CowType',
        'edit_title' => 'Edit CowType',
        'show_title' => 'Show CowType',
        'inputs' => [
            'name' => 'Name',
            'gender' => 'Gender',
        ],
    ],

    'farms' => [
        'name' => 'Farms',
        'index_title' => 'Farms List',
        'new_title' => 'New Farm',
        'create_title' => 'Create Farm',
        'edit_title' => 'Edit Farm',
        'show_title' => 'Show Farm',
        'inputs' => [
            'name' => 'Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'description' => 'Description',
            'cattle_brand' => 'Cattle Brand',
        ],
    ],

    'farm_cows' => [
        'name' => 'Farm Cows',
        'index_title' => 'Cows List',
        'new_title' => 'New Cow',
        'create_title' => 'Create Cow',
        'edit_title' => 'Edit Cow',
        'show_title' => 'Show Cow',
        'inputs' => [
            'number' => 'Number',
            'name' => 'Name',
            'gender' => 'Gender',
            'parent_id' => 'Parent Id',
            'mother_id' => 'Mother Id',
            'owner' => 'Owner',
            'picture' => 'Picture',
            'sold' => 'Sold',
            'born' => 'Born',
        ],
    ],

    'farm_users' => [
        'name' => 'Farm Users',
        'index_title' => ' List',
        'new_title' => 'New Farm user',
        'create_title' => 'Create farm_user',
        'edit_title' => 'Edit farm_user',
        'show_title' => 'Show farm_user',
        'inputs' => [
            'user_id' => 'User',
        ],
    ],

    'histories' => [
        'name' => 'Histories',
        'index_title' => 'Histories List',
        'new_title' => 'New History',
        'create_title' => 'Create History',
        'edit_title' => 'Edit History',
        'show_title' => 'Show History',
        'inputs' => [
            'date' => 'Date',
            'weight' => 'Weight',
            'cow_type_id' => 'Cow Type',
            'comments' => 'Comments',
            'picture' => 'Picture',
        ],
    ],

    'history_medicines' => [
        'name' => 'History Medicines',
        'index_title' => ' List',
        'new_title' => 'New History medicine',
        'create_title' => 'Create history_medicine',
        'edit_title' => 'Edit history_medicine',
        'show_title' => 'Show history_medicine',
        'inputs' => [
            'medicine_id' => 'Medicine',
            'cc' => 'Cc',
        ],
    ],

    'history_cows' => [
        'name' => 'History Cows',
        'index_title' => ' List',
        'new_title' => 'New Cow history',
        'create_title' => 'Create cow_history',
        'edit_title' => 'Edit cow_history',
        'show_title' => 'Show cow_history',
        'inputs' => [
            'cow_id' => 'Cow',
        ],
    ],

    'manufacturers' => [
        'name' => 'Manufacturers',
        'index_title' => 'Manufacturers List',
        'new_title' => 'New Manufacturer',
        'create_title' => 'Create Manufacturer',
        'edit_title' => 'Edit Manufacturer',
        'show_title' => 'Show Manufacturer',
        'inputs' => [
            'name' => 'Name',
        ],
    ],

    'manufacturer_medicines' => [
        'name' => 'Manufacturer Medicines',
        'index_title' => 'Medicines List',
        'new_title' => 'New Medicine',
        'create_title' => 'Create Medicine',
        'edit_title' => 'Edit Medicine',
        'show_title' => 'Show Medicine',
        'inputs' => [
            'name' => 'Name',
            'expiration_date' => 'Expiration Date',
            'code' => 'Code',
            'cc' => 'Cc',
            'cost' => 'Cost',
            'market_id' => 'Market',
        ],
    ],

    'markets' => [
        'name' => 'Markets',
        'index_title' => 'Markets List',
        'new_title' => 'New Market',
        'create_title' => 'Create Market',
        'edit_title' => 'Edit Market',
        'show_title' => 'Show Market',
        'inputs' => [
            'name' => 'Name',
            'phone' => 'Phone',
            'direction' => 'Direction',
        ],
    ],

    'market_medicines' => [
        'name' => 'Market Medicines',
        'index_title' => 'Medicines List',
        'new_title' => 'New Medicine',
        'create_title' => 'Create Medicine',
        'edit_title' => 'Edit Medicine',
        'show_title' => 'Show Medicine',
        'inputs' => [
            'name' => 'Name',
            'manufacturer_id' => 'Manufacturer',
            'expiration_date' => 'Expiration Date',
            'code' => 'Code',
            'cc' => 'Cc',
            'cost' => 'Cost',
        ],
    ],

    'medicines' => [
        'name' => 'Medicines',
        'index_title' => 'Medicines List',
        'new_title' => 'New Medicine',
        'create_title' => 'Create Medicine',
        'edit_title' => 'Edit Medicine',
        'show_title' => 'Show Medicine',
        'inputs' => [
            'name' => 'Name',
            'manufacturer_id' => 'Manufacturer',
            'expiration_date' => 'Expiration Date',
            'code' => 'Code',
            'cc' => 'Cc',
            'total_cc' => 'Total CC',
            'discarded' => 'Discarded',
            'cost' => 'Cost',
            'market_id' => 'Market',
        ],
    ],

    'medicine_histories' => [
        'name' => 'Medicine Histories',
        'index_title' => ' List',
        'new_title' => 'New History medicine',
        'create_title' => 'Create history_medicine',
        'edit_title' => 'Edit history_medicine',
        'show_title' => 'Show history_medicine',
        'inputs' => [
            'history_id' => 'History',
            'cc' => 'Cc',
        ],
    ],

    'solds' => [
        'name' => 'Solds',
        'index_title' => 'Solds List',
        'new_title' => 'New Sold',
        'create_title' => 'Create Sold',
        'edit_title' => 'Edit Sold',
        'show_title' => 'Show Sold',
        'inputs' => [
            'date' => 'Date',
            'cow_id' => 'Cow',
            'pounds' => 'Pounds',
            'kilograms' => 'Kilograms',
            'price' => 'Price',
            'number_sold' => 'Number Sold',
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
        ],
    ],

    'user_farms' => [
        'name' => 'User Farms',
        'index_title' => ' List',
        'new_title' => 'New Farm user',
        'create_title' => 'Create farm_user',
        'edit_title' => 'Edit farm_user',
        'show_title' => 'Show farm_user',
        'inputs' => [
            'farm_id' => 'Farm',
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
