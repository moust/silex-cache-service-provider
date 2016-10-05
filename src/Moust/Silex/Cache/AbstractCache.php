<?php

/*
 * This file is part of CacheServiceProvider.
 *
 * (c) Quentin Aupetit <qaupetit@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Moust\Silex\Cache;
const ONE_MINUTE = 60;
const ONE_HOUR = 3600;
const TWO_HOURS = 7200;
const SIX_HOURS = 21600;
const TWELVE_HOURS = 43200;
const ONE_DAY = 86400;
const TWO_DAYS = 172800;
const ONE_WEEK = 604800;
const THIRTY_DAYS = 2592000;
const ONE_MONTH = 2592000;
const ONE_YEAR = 31536000;

abstract class AbstractCache implements CacheInterface
{
	public function __construct(array $options = array())
	{
	}
}
