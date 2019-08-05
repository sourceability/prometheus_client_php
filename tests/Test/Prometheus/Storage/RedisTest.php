<?php


namespace Prometheus\Storage;

class RedisTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @requires extension redis
     * @expectedException \Prometheus\Exception\StorageException
     * @expectedExceptionMessage Can't connect to Redis server
     */
    public function itShouldThrowAnExceptionOnConnectionFailure()
    {
        $redis = new Redis(array('host' => 'doesntexist.test'));
        $redis->flushRedis();
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
