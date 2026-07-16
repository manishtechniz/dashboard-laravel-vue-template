<?php

return [
        'components' => [
        'layouts' => [
            'header' => [
                'account-title' => 'Account',
                'app-version' => 'Version : :version',
                'logout' => 'Logout',
                'my-account' => 'My Account',
                'notifications' => 'Notifications',
                'visit-shop' => 'Visit Shop',

                'mega-search' => [
                    'categories' => 'Categories',
                    'customers' => 'Customers',
                    'explore-all-categories' => 'Explore all categories',
                    'explore-all-customers' => 'Explore all customers',
                    'explore-all-matching-categories' => 'Explore all categories matching “:query” (:count)',
                    'explore-all-matching-customers' => 'Explore all customers matching “:query” (:count)',
                    'explore-all-matching-orders' => 'Explore all Orders matching “:query” (:count)',
                    'explore-all-matching-products' => 'Explore all products matching “:query” (:count)',
                    'explore-all-orders' => 'Explore all Orders',
                    'explore-all-products' => 'Explore all products',
                    'orders' => 'Orders',
                    'products' => 'Products',
                    'sku' => 'SKU: :sku',
                    'title' => 'Mega Search',
                ],
            ],

            'sidebar' => [
                'attribute-families' => 'Attribute Families',
                'attributes' => 'Attributes',
                'booking-product' => 'Bookings',
                'campaigns' => 'Campaigns',
                'catalog' => 'Catalog',
                'categories' => 'Categories',
                'channels' => 'Channels',
                'cms' => 'CMS',
                'collapse' => 'Collapse',
                'communications' => 'Communications',
                'configure' => 'Configure',
                'currencies' => 'Currencies',
                'custom-fields' => 'Custom Fields',
                'customers' => 'Customers',
                'dashboard' => 'Dashboard',
                'data-transfer' => 'Data Transfer',
                'discount' => 'Discount',
                'email-templates' => 'Email Templates',
                'events' => 'Events',
                'exchange-rates' => 'Exchange Rates',
                'gdpr-data-requests' => 'GDPR Data Requests',
                'groups' => 'Groups',
                'imports' => 'Imports',
                'inventory-sources' => 'Inventory Sources',
                'invoices' => 'Invoices',
                'locales' => 'Locales',
                'marketing' => 'Marketing',
                'mode' => 'Dark Mode',
                'newsletter-subscriptions' => 'Newsletter Subscriptions',
                'orders' => 'Orders',
                'products' => 'Products',
                'promotions' => 'Promotions',
                'reasons' => 'Reasons',
                'refunds' => 'Refunds',
                'reporting' => 'Reporting',
                'requests' => 'Requests',
                'reviews' => 'Reviews',
                'rma' => 'RMA',
                'roles' => 'Roles',
                'rules' => 'Rules',
                'sales' => 'Sales',
                'search-seo' => 'Search & SEO',
                'search-synonyms' => 'Search Synonyms',
                'search-terms' => 'Search Terms',
                'settings' => 'Settings',
                'shipments' => 'Shipments',
                'sitemaps' => 'Sitemaps',
                'statuses' => 'Statuses',
                'tax-categories' => 'Tax Categories',
                'tax-rates' => 'Tax Rates',
                'taxes' => 'Taxes',
                'themes' => 'Themes',
                'transactions' => 'Transactions',
                'url-rewrites' => 'URL Rewrites',
                'users' => 'Users',
            ],

            'powered-by' => [
                'description' => 'Powered by :bagisto, an open-source project by :webkul.',
            ],
        ],

        'datagrid' => [
            'index' => [
                'must-select-a-mass-action' => 'You must select a mass action.',
                'must-select-a-mass-action-option' => 'You must select a mass action\'s option.',
                'no-records-selected' => 'No records have been selected.',
            ],

            'toolbar' => [
                'length-of' => ':length of',
                'of' => 'of',
                'per-page' => 'Per Page',
                'results' => ':total Results',
                'selected' => ':total Selected',

                'mass-actions' => [
                    'select-action' => 'Select Action',
                    'select-option' => 'Select Option',
                    'submit' => 'Submit',
                ],

                'filter' => [
                    'apply-filters-btn' => 'Apply Filters',
                    'back-btn' => 'Back',
                    'create-new-filter' => 'Create New Filter',
                    'custom-filters' => 'Custom Filters',
                    'delete-error' => 'Something went wrong while deleting the filter, please try again.',
                    'delete-success' => 'Filter has been deleted successfully.',
                    'empty-description' => 'There is no selected filters available to save. Please select filters to save.',
                    'empty-title' => 'Add Filters to Save',
                    'name' => 'Name',
                    'quick-filters' => 'Quick Filters',
                    'save-btn' => 'Save',
                    'save-filter' => 'Save Filter',
                    'saved-success' => 'Filter has been saved successfully.',
                    'selected-filters' => 'Selected Filters',
                    'title' => 'Filter',
                    'update' => 'Update',
                    'update-filter' => 'Update Filter',
                    'updated-success' => 'Filter has been updated successfully.',
                ],

                'search' => [
                    'title' => 'Search',
                ],
            ],

            'filters' => [
                'select' => 'Select',
                'title' => 'Filters',

                'dropdown' => [
                    'searchable' => [
                        'atleast-two-chars' => 'Type atleast 2 characters...',
                        'no-results' => 'No result found...',
                    ],
                ],

                'custom-filters' => [
                    'clear-all' => 'Clear All',
                    'title' => 'Custom Filters',
                ],

                'boolean-options' => [
                    'false' => 'False',
                    'true' => 'True',
                ],

                'date-options' => [
                    'last-month' => 'Last Month',
                    'last-six-months' => 'Last 6 Months',
                    'last-three-months' => 'Last 3 Months',
                    'this-month' => 'This Month',
                    'this-week' => 'This Week',
                    'this-year' => 'This Year',
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                ],
            ],

            'table' => [
                'actions' => 'Actions',
                'no-records-available' => 'No Records Available.',
                'no-records-hint' => 'Try adjusting your filters, or check back later once data is added.',
            ],
        ],

        'modal' => [
            'confirm' => [
                'agree-btn' => 'Agree',
                'disagree-btn' => 'Disagree',
                'message' => 'Are you sure you want to perform this action?',
                'title' => 'Are you sure?',
            ],
        ],

        'products' => [
            'search' => [
                'add-btn' => 'Add Selected Product',
                'empty-info' => 'No products available for search term.',
                'empty-title' => 'No products found',
                'product-image' => 'Product Image',
                'qty' => ':qty Available',
                'sku' => 'SKU - :sku',
                'title' => 'Select Products',
            ],
        ],

        'media' => [
            'images' => [
                'add-image-btn' => 'Add Image',
                'ai-add-image-btn' => 'Magic AI',
                'ai-btn-info' => 'Generate Image',
                'allowed-types' => 'png, jpeg, jpg',
                'not-allowed-error' => 'Only images files (.jpeg, .jpg, .png, ..) are allowed.',

                'ai-generation' => [
                    'apply' => 'Apply',
                    'generate' => 'Generate',
                    'generating' => 'Generating...',
                    'high' => 'High',
                    'landscape' => 'Landscape (3:2)',
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'model' => 'Model',
                    'number-of-images' => 'Number of Images',
                    'portrait' => 'Portrait (2:3)',
                    'prompt' => 'Prompt',
                    'quality' => 'Quality',
                    'regenerate' => 'Regenerate',
                    'regenerating' => 'Regenerating...',
                    'size' => 'Size',
                    'square' => 'Square (1:1)',
                    'title' => 'AI Image Generation',
                ],

                'placeholders' => [
                    'front' => 'Front',
                    'next' => 'Next',
                    'size' => 'Size',
                    'square' => 'Square (1:1)',
                    'use-cases' => 'Use Cases',
                    'zoom' => 'Zoom',
                ],
            ],

            'videos' => [
                'add-video-btn' => 'Add Video',
                'allowed-types' => 'mp4, webm, mkv',
                'not-allowed-error' => 'Only videos files (.mp4, .mov, .ogg ..) are allowed.',
            ],
        ],

        'tinymce' => [
            'ai-btn-tile' => 'Magic AI',

            'ai-generation' => [
                'apply' => 'Apply',
                'enabled' => 'Enabled',
                'generate' => 'Generate',
                'generated-content' => 'Generated Content',
                'generated-content-info' => 'AI-generated content may be misleading. Review the generated content before applying.',
                'generating' => 'Generating...',
                'model' => 'Model',
                'prompt' => 'Prompt',
                'title' => 'AI Assistance',
            ],

            'errors' => [
                'file-extension-mismatch' => 'File extension does not match file type.',
                'file-upload-failed' => 'File upload failed.',
                'http-error' => 'HTTP error.',
                'invalid-file-type' => 'Invalid file type. Allowed types: JPEG, PNG, GIF, WebP, SVG',
                'invalid-json' => 'Invalid JSON.',
                'no-file-uploaded' => 'No file uploaded.',
                'upload-failed' => 'Image upload failed due to a XHR Transport error.',
            ],
        ],
    ],
];
