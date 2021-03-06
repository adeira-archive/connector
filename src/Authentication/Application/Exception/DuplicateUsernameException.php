<?php declare(strict_types = 1);

namespace Adeira\Connector\Authentication\Application\Exception;

use Adeira\Connector\Authentication\DomainModel\User\User;

final class DuplicateUsernameException extends \Exception
{

	public function __construct(User $user)
	{
		parent::__construct("User with username '{$user->nickname()}' already exists.");
	}

}
