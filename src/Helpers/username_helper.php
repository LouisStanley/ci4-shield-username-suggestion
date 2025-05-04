<?php

if (! function_exists('generate_username_suggestions')) {
    /**
     * Generate username suggestions based on email or other data
     *
     * @param string $email Email address to use for suggestions
     * @param int    $count Number of suggestions to generate
     *
     * @return array List of suggested usernames
     */
    function generate_username_suggestions(string $email, int $count = 5): array
    {
        $suggestions = [];
        // Extract the username part from email
        $username = strstr($email, '@', true);

        if (empty($username)) {
            return $suggestions;
        }

        // Clean the username (remove special characters)
        $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);

        // Get the User Provider to check username availability
        $users = auth()->getProvider();

        // Add the base username if it's available
        if (! is_username_taken($username)) {
            $suggestions[] = $username;
        }

        // Add variants with numbers
        for ($i = 0; $i < $count; $i++) {
            $variant = $username . mt_rand(1, 999);
            if (! is_username_taken($variant)) {
                $suggestions[] = $variant;
            }
        }

        // Add variants with year
        $yearVariant = $username . date('Y');
        if (! is_username_taken($yearVariant)) {
            $suggestions[] = $yearVariant;
        }

        // Return only the requested number of suggestions
        return array_slice($suggestions, 0, $count);
    }
}

if (! function_exists('is_username_taken')) {
    /**
     * Check if a username is already taken
     *
     * @param string $username Username to check
     *
     * @return bool True if username is taken, false otherwise
     */
    function is_username_taken(string $username): bool
    {
        $users = auth()->getProvider();

        return $users->where('username', $username)->first() !== null;
    }
}
