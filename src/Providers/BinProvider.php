<?php
namespace Pstt\Providers;

use Pstt\Interfaces\BinProviderInterface;

class BinProvider implements BinProviderInterface
{
    private const BIN_ENDPOINT = 'https://lookup.binlist.net';

    public function searchBin(string $bin): bool|string
    {
        return file_get_contents(self::BIN_ENDPOINT . '/' . $bin);
    }
}

