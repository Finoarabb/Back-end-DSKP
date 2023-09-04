<?php
namespace App\Libraries;

use CodeIgniter\Validation\Rules;

class CustomValidationRules
{
    public function oneway($value, string $field, array $params): bool
    {
        $request = service('request');
        if(!array_key_exists($field, $params)) return false;
        $otherField = $params[$field];
        
        // Check if one field is null and the other is not null
        if (empty($otherField) ^ empty($value)) {
            return true;
        }

        return false;
    }
}
?>