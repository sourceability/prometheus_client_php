<?php

namespace Prometheus\Storage;

use PHPUnit\Framework\TestCase;
use Prometheus\Exception\StorageException;

/**
 * @requires extension redis
 */
class RedisTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldThrowAnExceptionOnConnectionFailure()
    {
        $redis = new Redis(['host' => '/dev/null']);

        $this->expectException(StorageException::class);
        $this->expectExceptionMessage("Can't connect to Redis server");

        $redis->collect();
        $redis->flushRedis();
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionWhenInjectedRedisIsNotConnected()
    {
        $connection = new \Redis();

        $this->expectException(StorageException::class);
        $this->expectExceptionMessage('Connection to Redis server not established');

        Redis::fromExistingConnection($connection);
    }

    /**
     * @test
     */
    public function itShouldAcceptRedisInstanceAndNotConnect()
    {
        $redisInstance = $this->getMock(RedisMock::class);
        $redisInstance
            ->expects($this->never())
            ->method('connect')
            ->willReturn(null);
        $redisInstance
            ->expects($this->never())
            ->method('setOption')
            ->willReturn(null);
        $redisInstance
            ->expects($this->once())
            ->method('flushAll')
            ->willReturn(null);

        $redis = new Redis($redisInstance);
        $redis->flushRedis();
    }

}

class RedisMock
{
    public function connect($host, $port = 6379, $timeout = 0.0, $reserved = null, $retry_interval = 0, $read_timeout = 0.0) {}
    public function get($key) {}
    public function flushAll() {}
}
