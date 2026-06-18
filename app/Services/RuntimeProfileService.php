<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class RuntimeProfileService
{
    public function activeProfiles(): array
    {
        return Database::select('SELECT * FROM runtime_profiles WHERE is_active = 1 ORDER BY name');
    }

    public function defaultProfile(): ?array
    {
        return Database::first('SELECT * FROM runtime_profiles WHERE is_active = 1 ORDER BY id LIMIT 1');
    }

    public function forChallenge(array $challenge): ?array
    {
        if (!empty($challenge['runtime_profile_id'])) {
            $profile = Database::first(
                'SELECT * FROM runtime_profiles WHERE id = :id AND is_active = 1',
                ['id' => (int) $challenge['runtime_profile_id']]
            );
            if ($profile) {
                return $profile;
            }
        }

        return $this->defaultProfile();
    }
}

