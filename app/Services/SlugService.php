<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Tenant;

class SlugService
{
    /**
     * Generate a URL-safe slug for a tenant name and ensure uniqueness.
     *
     * @param  string  $name
     * @param  int     $maxAttempts
     * @return string
     */
    public static function generateUniqueSlug(string $name, int $maxAttempts = 100): string
    {
        $base = Str::slug($name);
        $slug = $base ?: (string) Str::uuid(); // handle names that slug to empty
        $attempt = 1;

        while (Tenant::where('slug', $slug)->exists()) {
            $attempt++;
            $slug = $base . '-' . $attempt;
            if ($attempt > $maxAttempts) {
                // fallback to uuid-based slug if collisions excessive
                return (string) Str::uuid();
            }
        }

        return $slug;
    }
}
