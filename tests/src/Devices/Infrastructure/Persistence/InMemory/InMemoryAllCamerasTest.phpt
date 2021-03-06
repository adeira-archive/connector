<?php declare(strict_types = 1);

namespace Adeira\Connector\Tests\Devices\Infrastructure\Persistence\InMemory;

use Adeira\Connector\Authentication\DomainModel\Owner\Owner;
use Adeira\Connector\Authentication\DomainModel\User\User;
use Adeira\Connector\Authentication\DomainModel\User\UserId;
use Adeira\Connector\Devices\DomainModel\Camera\Camera;
use Adeira\Connector\Devices\DomainModel\Camera\CameraId;
use Adeira\Connector\Devices\DomainModel\Camera\Stream;
use Adeira\Connector\Devices\Infrastructure\Persistence\InMemory\InMemoryAllCameras;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require getenv('BOOTSTRAP');

/**
 * @testCase
 */
final class InMemoryAllCamerasTest extends \Adeira\Connector\Tests\TestCase
{

	public function test_that_add_works()
	{
		$owner = new Owner(new User(UserId::createFromString('00000000-0000-0000-0000-000000000000'), 'username'));
		$repository = new InMemoryAllCameras;
		Assert::count(0, $repository->belongingTo($owner)->hydrate());
		$repository->add(Camera::create(CameraId::create(), $owner, 'Camera 1', Stream::create('rtsp://a', Uuid::uuid4(), 'hls')));
		$repository->add(Camera::create(CameraId::create(), $owner, 'Camera 2', Stream::create('rtsp://b', Uuid::uuid4(), 'hls')));
		$repository->add(Camera::create(CameraId::create(), $owner, 'Camera 3', Stream::create('rtsp://c', Uuid::uuid4(), 'hls')));
		Assert::count(3, $repository->belongingTo($owner)->hydrate());
	}

	public function test_that_remove_works()
	{
		$owner = new Owner(new User(UserId::createFromString('00000000-0000-0000-0000-000000000000'), 'username'));
		$repository = new InMemoryAllCameras;
		$repository->add($cam1 = Camera::create(CameraId::create(), $owner, 'Camera 1', Stream::create('rtsp://a', Uuid::uuid4(), 'hls')));
		$repository->add($cam2 = Camera::create(CameraId::create(), $owner, 'Camera 2', Stream::create('rtsp://b', Uuid::uuid4(), 'hls')));
		$repository->add($cam3 = Camera::create(CameraId::create(), $owner, 'Camera 3', Stream::create('rtsp://c', Uuid::uuid4(), 'hls')));
		Assert::count(3, $repository->belongingTo($owner)->hydrate());

		$repository->remove($cam1);
		Assert::count(2, $repository->belongingTo($owner)->hydrate());
		$repository->remove($cam2);
		Assert::count(1, $repository->belongingTo($owner)->hydrate());
		$repository->remove($cam3);
		Assert::count(0, $repository->belongingTo($owner)->hydrate());
		$repository->remove($cam3);
		Assert::count(0, $repository->belongingTo($owner)->hydrate());
	}

	public function test_that_withId_works()
	{
		$owner = new Owner(new User(UserId::createFromString('00000000-0000-0000-0000-000000000000'), 'username'));
		$repository = new InMemoryAllCameras;
		$repository->add(Camera::create($id = CameraId::create(), $owner, 'Camera 1', Stream::create('rtsp://a', Uuid::uuid4(), 'hls')));
		Assert::type(Camera::class, $repository->withId($owner, $id));
		Assert::null($repository->withId($owner, CameraId::create()));
		Assert::null($repository->withId(
			$owner = new Owner(new User(UserId::create(), 'another username')),
			CameraId::create())
		);
	}

	public function test_that_belongingTo_works()
	{
		$owner1 = new Owner(new User(UserId::createFromString('00000000-0000-0000-0000-000000000001'), 'username1'));
		$owner2 = new Owner(new User(UserId::createFromString('00000000-0000-0000-0000-000000000002'), 'username2'));
		$repository = new InMemoryAllCameras;
		Assert::count(0, $repository->belongingTo($owner1)->hydrate());
		Assert::count(0, $repository->belongingTo($owner2)->hydrate());
		$repository->add(Camera::create(CameraId::create(), $owner1, 'Camera 1', Stream::create('rtsp://a', Uuid::uuid4(), 'hls')));
		$repository->add(Camera::create(CameraId::create(), $owner2, 'Camera 2', Stream::create('rtsp://b', Uuid::uuid4(), 'hls')));
		$repository->add(Camera::create(CameraId::create(), $owner1, 'Camera 3', Stream::create('rtsp://c', Uuid::uuid4(), 'hls')));
		Assert::count(2, $repository->belongingTo($owner1)->hydrate());
		Assert::count(1, $repository->belongingTo($owner2)->hydrate());
	}

}

(new InMemoryAllCamerasTest)->run();
