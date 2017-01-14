<?php declare(strict_types = 1);

namespace Adeira\Connector\PhysicalUnits\Speed\Units;

class Kmh implements ISpeedUnit
{

	public function unitCode(): string
	{
		return 'kmh';
	}

	public function getConversionTable(): array
	{
		return [
			'mph' => 1 / 1.609344, //exact
			'ms' => 1 / 3.6, //exact
		];
	}

}