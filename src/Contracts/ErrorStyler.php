<?php

namespace Tetthys\Input\Contracts;

/**
 * Decide which CSS class (if any) should be appended when a field has errors.
 */
interface ErrorStyler
{
    /**
     * @param string $name The logical field name (e.g., "email", "roles")
     * @param list<string> $messages The error messages for the field (may be empty)
     * @return string|null Return the class to append (e.g., "is-invalid"), or null/"" to append nothing.
     */
    public function classFor(string $name, array $messages): ?string;
}
