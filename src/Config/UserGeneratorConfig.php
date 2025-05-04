<?php

namespace LouisStanley\Ci4ShieldUsernameSuggest\Config;

use CodeIgniter\Config\BaseConfig;

class UserGeneratorConfig extends BaseConfig
{
    /**
     * Number of suggestions to generate
     */
    public $suggestionCount = 5;
}
