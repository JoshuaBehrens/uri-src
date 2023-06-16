<?php

/**
 * League.Uri (https://uri.thephpleague.com)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Uri\Components;

use League\Uri\Contracts\UriComponentInterface;
use League\Uri\Contracts\UriInterface;
use League\Uri\Exceptions\SyntaxError;
use League\Uri\Http;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface as Psr7UriInterface;
use Stringable;

/**
 * @group port
 * @coversDefaultClass \League\Uri\Components\Port
 */
final class PortTest extends TestCase
{
    public function testPortSetter(): void
    {
        self::assertSame('443', Port::fromInt(443)->toString());
    }

    /**
     * @dataProvider getToIntProvider
     */
    public function testToInt(
        UriComponentInterface|Stringable|int|string|null $input,
        ?int $expected,
        ?string $string_expected,
        string $uri_expected
    ): void {
        $port = null === $input ? Port::new() : Port::fromNumber($input);

        self::assertSame($expected, $port->toInt());
        self::assertSame($string_expected, $port->value());
        self::assertSame($uri_expected, $port->getUriComponent());
    }

    public static function getToIntProvider(): array
    {
        return [
            [null, null, null, ''],
            [23, 23, '23', ':23'],
            ['23', 23, '23', ':23'],
            [new class() {
                public function __toString()
                {
                    return '23';
                }
            }, 23, '23', ':23'],
            [Port::fromInt(23), 23, '23', ':23'],
        ];
    }

    public function testFailedPortException(): void
    {
        $this->expectException(SyntaxError::class);

        Port::fromNumber(-1);
    }

    /**
     * @dataProvider getURIProvider
     */
    public function testCreateFromUri(UriInterface|Psr7UriInterface $uri, ?string $expected): void
    {
        $port = Port::fromUri($uri);

        self::assertSame($expected, $port->value());
    }

    public static function getURIProvider(): iterable
    {
        return [
            'PSR-7 URI object' => [
                'uri' => Http::fromString('http://example.com:443'),
                'expected' => '443',
            ],
            'PSR-7 URI object with no fragment' => [
                'uri' => Http::fromString('toto://example.com'),
                'expected' => null,
            ],
            'League URI object' => [
                'uri' => Uri::fromString('http://example.com:443'),
                'expected' => '443',
            ],
            'League URI object with no fragment' => [
                'uri' => Uri::fromString('toto://example.com'),
                'expected' => null,
            ],
        ];
    }

    public function testCreateFromAuthority(): void
    {
        $uri = Uri::fromString('http://example.com:443');
        $auth = Authority::fromUri($uri);

        self::assertEquals(Port::fromUri($uri), Port::fromAuthority($auth));
    }

    public function testCreateFromIntSucceeds(): void
    {
        self::assertEquals(0, Port::fromInt(0)->value());
    }

    public function testCreateFromIntFails(): void
    {
        $this->expectException(SyntaxError::class);
        Port::fromInt(-1)->value();  /* @phpstan-ignore-line */
    }
}
