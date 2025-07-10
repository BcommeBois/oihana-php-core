<?php

namespace oihana\core\files ;

use PHPUnit\Framework\TestCase;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

use oihana\exceptions\DirectoryException;

final class GetDirectoryTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {

        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'oihana_' . uniqid();
        mkdir($this->tmpDir, 0o777, true);
    }

    protected function tearDown(): void
    {
        if ( is_dir($this->tmpDir ) )
        {
            @chmod($this->tmpDir, 0o777);
            @rmdir($this->tmpDir);
        }
    }

    /**
     * @throws DirectoryException
     */
    public function testReturnsNormalizedPathWithoutTrailingSeparator(): void
    {
        $withTrailing = $this->tmpDir . DIRECTORY_SEPARATOR;
        $result       = getDirectory($withTrailing);
        $this->assertSame($this->tmpDir, $result, 'Le séparateur de fin doit être supprimé.');
    }

    /**
     * @throws DirectoryException
     */
    public function testReturnsSamePathWhenAlreadyNormalized(): void
    {
        $result = getDirectory($this->tmpDir);
        $this->assertSame($this->tmpDir, $result, 'Un chemin déjà normalisé doit être retourné tel quel.');
    }

    public function testThrowsExceptionWhenDirectoryDoesNotExist(): void
    {
        $this->expectException(DirectoryException::class);
        getDirectory($this->tmpDir . '_missing');
    }

    public function testThrowsExceptionWhenDirectoryIsNotReadable(): void
    {
        // Retire les droits de lecture pour provoquer l’erreur
        chmod($this->tmpDir, 0o222);

        $this->expectException(DirectoryException::class);
        getDirectory($this->tmpDir);
    }
}