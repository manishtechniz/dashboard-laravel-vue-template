<?php

namespace App\Http\Controllers\Admin;

class AdminTestController extends Controller
{
    public function test()
    {
        return [
            [
                'id'        => 1,
                'name'      => 'Acme Corporation',
                'code'      => 'ACM-1001',
                'industry'  => 'Technology',
                'country'   => 'USA',
                'status'    => 'Active',
            ],

            [
                'id'        => 2,
                'name'      => 'Global Trade Ltd',
                'code'      => 'GTL-1002',
                'industry'  => 'Import/Export',
                'country'   => 'Germany',
                'status'    => 'Pending',
            ],

            [
                'id'        => 3,
                'name'      => 'Sunrise Retail',
                'code'      => 'SRT-1003',
                'industry'  => 'Retail',
                'country'   => 'India',
                'status'    => 'Inactive',
            ],

            [
                'id'        => 4,
                'name'      => 'Blue Ocean Logistics',
                'code'      => 'BOL-1004',
                'industry'  => 'Logistics',
                'country'   => 'Singapore',
                'status'    => 'Active',
            ],

            [
                'id'        => 5,
                'name'      => 'NextGen Solutions',
                'code'      => 'NGS-1005',
                'industry'  => 'Software',
                'country'   => 'Canada',
                'status'    => 'Pending',
            ],
        ];
        // return [
        //     'genders' => [
        //         [
        //             'label' => 'Dyanmic Male',
        //             'value' => 'M',
        //         ],

        //         [
        //             'label' => 'Dyanmic FeMale',
        //             'value' => 'F',
        //         ],
        //     ],

        //     'desgin_config' => [
        //         'sections' => [
        //             [
        //                 'left' => [
        //                     [
        //                         'icons' => '',
        //                         'label' => 'Personal details',
        //                         'fields' => [

        //                             [
        //                                 'code' => 'FirstName',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'FirstName_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'First Name',
        //                                 'for' => '',

        //                                 'operation_validation_values' => [
        //                                     'email' => [
        //                                         'Email' => 'email',
        //                                     ],

        //                                     'mobile' => [
        //                                         'MobileNo' => [
        //                                             'required' => false,
        //                                             'max' => 4,
        //                                         ],
        //                                     ],
        //                                 ],

        //                                 'field_hidden_values' => [
        //                                     'hide_email' => ['Email'],
        //                                     'hide_mobile' => ['MobileNo'],
        //                                 ],
        //                             ],

        //                             [
        //                                 'code' => 'MiddleName',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'MiddleName_id',
        //                                 'default_rules' => null,
        //                                 'placeholder' => '',
        //                                 'label' => 'Middle Name',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'LastName',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'LastName_id',
        //                                 'default_rules' => null,
        //                                 'placeholder' => '',
        //                                 'label' => 'Last Name',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'Email',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'LastName_id',
        //                                 'default_rules' => 'required|email',
        //                                 'placeholder' => '',
        //                                 'label' => 'Email',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'MobileNo',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'MobileNo_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Mobile No',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'password',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'password_id',
        //                                 'default_rules' => 'required|min:6',
        //                                 'placeholder' => '',
        //                                 'label' => 'Password',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'confirm_password',
        //                                 'type' => 'text',
        //                                 'default_value' => '',
        //                                 'id' => 'password_id',
        //                                 'default_rules' => 'required|min:6',
        //                                 'placeholder' => '',
        //                                 'label' => 'Confirm Password',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'DOB',
        //                                 'type' => 'date',
        //                                 'default_value' => '',
        //                                 'id' => 'DOB_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Date of Birth',
        //                                 'for' => '',
        //                             ],

        //                             [
        //                                 'code' => 'Gender',
        //                                 'type' => 'radio',
        //                                 'default_value' => '',
        //                                 'id' => 'Gender_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Gender',
        //                                 'for' => '',

        //                                 'option_type' => 'static', // static or dynamic
        //                                 'dynamic_option_key' => 'genders',

        //                                 'options' => [
        //                                     [
        //                                         'label' => 'Male',
        //                                         'value' => 'M',
        //                                     ],

        //                                     [
        //                                         'label' => 'FeMale',
        //                                         'value' => 'F',
        //                                     ],
        //                                 ],
        //                             ],

        //                             [
        //                                 'code' => 'Address',
        //                                 'type' => 'textarea',
        //                                 'default_value' => '',
        //                                 'id' => 'Address_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Address',
        //                                 'for' => '',
        //                                 'is_full_width' => true,
        //                             ],

        //                             [
        //                                 'code' => 'image',
        //                                 'type' => 'file',
        //                                 'default_value' => '',
        //                                 'id' => 'image_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Image',
        //                                 'for' => '',
        //                             ],
        //                         ],
        //                     ],

        //                     [
        //                         'icons' => '',
        //                         'label' => 'Other details',
        //                         'fields' => [
        //                             [
        //                                 'code' => 'Area',
        //                                 'type' => 'select',
        //                                 'default_value' => null,
        //                                 'id' => 'Area_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Area',
        //                                 'for' => '',

        //                                 'option_label_key' => 'label',
        //                                 'option_value_key' => 'value',
        //                                 'option_type' => 'static', // static or dynamic
        //                                 'dynamic_option_key' => '',

        //                                 'options' => [
        //                                     [
        //                                         'label' => 'Option 1',
        //                                         'value' => 'option1',
        //                                     ],
        //                                     [
        //                                         'label' => 'Option 2',
        //                                         'value' => 'option2',
        //                                     ],
        //                                 ],
        //                             ],

        //                             [
        //                                 'code' => 'Wharehouse',
        //                                 'type' => 'select',
        //                                 'default_value' => null,
        //                                 'id' => 'Wharehouse_id',
        //                                 'default_rules' => 'required',
        //                                 'placeholder' => '',
        //                                 'label' => 'Wharehouse',
        //                                 'for' => '',

        //                                 'option_label_key' => 'label',
        //                                 'option_value_key' => 'value',
        //                                 'option_type' => 'dynamic', // static or dynamic
        //                                 'dynamic_option_key' => '',

        //                                 'options' => [
        //                                     [
        //                                         'label' => 'Option 1',
        //                                         'value' => 'option1',
        //                                     ],
        //                                     [
        //                                         'label' => 'Option 2',
        //                                         'value' => 'option2',
        //                                     ],
        //                                 ],
        //                             ],
        //                         ],
        //                     ],
        //                 ],

        //                 'right' => [
        //                     [
        //                         'icons' => '',
        //                         'label' => 'Active Status',
        //                         'fields' => [
        //                             [
        //                                 'code' => 'Active',
        //                                 'type' => 'switch',
        //                                 'short_description' => 'Toggle this user can log into the system',
        //                                 'default_value' => '',
        //                                 'id' => 'Active_id',
        //                                 'default_rules' => null,
        //                                 'placeholder' => '',
        //                                 'label' => 'Status',
        //                                 'for' => '',
        //                                 'handler' => '
        //                                         function(value) {
        //                                             return !! value ? "ACTIVE" : "INACTIVE";
        //                                         }
        //                                     '
        //                             ],
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //     ],
        // ];
    }


    // public function  quickTable()
    // {
    //         $query = Customer::query();

    //         // Search
    //         if ($request->search) {

    //             $query->where(function ($q) use ($request) {

    //                 $q->where('name', 'like', '%' . $request->search . '%')
    //                     ->orWhere('industry', 'like', '%' . $request->search . '%')
    //                     ->orWhere('country', 'like', '%' . $request->search . '%');
    //             });
    //         }

    //         // Sorting
    //         if ($request->sort_field) {

    //             $query->orderBy(
    //                 $request->sort_field,
    //                 $request->sort_order ?? 'asc'
    //             );
    //         }

    //         return response()->json(
    //             $query->paginate(
    //                 $request->per_page ?? 10
    //             )
    //         );
    //     }
}
