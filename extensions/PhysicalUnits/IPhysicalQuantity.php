<?php declare(strict_types = 1);

namespace Adeira\Connector\PhysicalUnits;

interface IPhysicalQuantity
{

	public function convert(IUnit $toUnit): IPhysicalQuantity;

}