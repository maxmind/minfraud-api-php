#!/usr/bin/env php
<?php

declare(strict_types=1);

// This just tests that it can load the MinFraud class and its dependencies
// from the phar.
require_once 'minfraud.phar';

use MaxMind\MinFraud;

$reader = new MinFraud(1, 'ABCD567890');
