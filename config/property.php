<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supply Head Property Entry
    |--------------------------------------------------------------------------
    |
    | Supply heads add properties on behalf of owners and often only have part
    | of the information at hand. When "enforce_required_fields" is disabled,
    | every field configured as mandatory in PropertyFieldConfig is treated as
    | optional for the supply-head add/edit form only — both in the browser
    | (no asterisk, no [required] attribute, no wizard step blocking) and on
    | the server. Field officer and owner forms are unaffected either way.
    |
    */

    'supply_head' => [
        'enforce_required_fields' => (bool) env('SUPPLY_HEAD_ENFORCE_REQUIRED_FIELDS', false),
    ],

];
