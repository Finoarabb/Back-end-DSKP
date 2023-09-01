<?php
namespace App\Libraries;

use CodeIgniter\Validation\Rules;

class CustomValidationRules
{
    public function oneway($value, string $field, array $params, string $rule): bool
    {
        $request = service('request');

        $otherField = $params[0];
        $otherFieldValue = $request->getVar($otherField);

        // Check if one field is null and the other is not null
        if (($value === null && $otherFieldValue !== null) || ($value !== null && $otherFieldValue === null)) {
            return true;
        }

        return false;
    }
}
?>