<?php

namespace oihana\files ;

use oihana\files\exceptions\DirectoryException;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class ListFilesTest extends TestCase
{
    private string $testDir;

    /**
     * @throws DirectoryException
     */
    protected function setUp(): void
    {
        $this->testDir = createDirectory( sys_get_temp_dir() . '/oihana/ListFilesTest_' . uniqid() ) ;

        // Nettoyer avant chaque test
        array_map('unlink', glob($this->testDir . '/*'));

        // Créer des fichiers tests
        file_put_contents($this->testDir . '/foo.php'       , '<?php // foo' ) ;
        file_put_contents($this->testDir . '/bar.blade.php' , 'blade content' ) ;
        file_put_contents($this->testDir . '/test123.php'   , '<?php // test123' ) ;
        file_put_contents($this->testDir . '/.hiddenfile'   , 'hidden' ) ;
        file_put_contents($this->testDir . '/text.txt'      , 'text' ) ;

        createDirectory  ($this->testDir . '/subdir') ;
        file_put_contents($this->testDir . '/subdir/ignore.php', '<?php // ignore' ) ;
    }

    /**
     * @throws DirectoryException
     */
    protected function tearDown(): void
    {
        deleteDirectory( $this->testDir  ) ;
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithoutFilter()
    {
        $files = listFiles($this->testDir);

        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);

        // Les fichiers listés : foo.php, bar.blade.php, test123.php (pas les dotfiles ni dossiers)
        $this->assertContains('foo.php', $names);
        $this->assertContains('bar.blade.php', $names);
        $this->assertContains('test123.php', $names);
        $this->assertNotContains('.hiddenfile', $names);
        $this->assertNotContains('ignore.php', $names); // car dans subdir (non récursif)
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithGlobPattern()
    {
        // ------- *.php

        $names = listFiles( $this->testDir, [ 'patterns' => '*.php' ] ) ;
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $names);

        $this->assertContains('foo.php', $names);
        $this->assertContains('test123.php', $names);
        $this->assertContains('bar.blade.php', $names);
        $this->assertNotContains('text.txt', $names);

        // ------- *.txt

        $names = listFiles( $this->testDir, [ 'patterns' => '*.txt' ] ) ;
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $names );

        $this->assertNotContains('foo.php', $names);
        $this->assertContains('text.txt', $names);
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithRegexPattern()
    {
        $files = listFiles($this->testDir, [ 'patterns' =>'/^test\d+\.php$/i' ] );
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);
        $this->assertEquals(['test123.php'], $names);
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithMixedPatterns()
    {
        $patterns = ['*.php', '/^bar.*\.blade\.php$/i'];
        $files =  listFiles($this->testDir, [ 'patterns' => $patterns ] );
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);

        $this->assertContains('foo.php', $names);
        $this->assertContains('test123.php', $names);
        $this->assertContains('bar.blade.php', $names);
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithMapper()
    {
        $files = listFiles($this->testDir, [ 'patterns' => '*.php' , 'filter' => fn(SplFileInfo $f) => $f->getFilename() ] );
        $this->assertContains('foo.php', $files);
        $this->assertContains('test123.php', $files);
        $this->assertContains('bar.blade.php', $files);
    }

    public function testListFilesThrowsOnInvalidDirectory()
    {
        $this->expectException(DirectoryException::class);
        listFiles('/path/to/invalid/dir' ) ;
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesRecursive()
    {
        $files = listFiles($this->testDir, ['recursive' => true]);
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);

        $this->assertContains('foo.php', $names);
        $this->assertContains('ignore.php', $names); // Dans subdir, car récursif
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesIncludeDotFiles()
    {
        $files = listFiles($this->testDir, ['includeDots' => true]);
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);

        $this->assertContains('.hiddenfile', $names);
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithFollowLinks()
    {
        // Créer un lien symbolique dans le temp dir
        $link = $this->testDir . '/link_to_subdir';
        if (!file_exists($link)) {
            symlink($this->testDir . '/subdir', $link);
        }

        $files = listFiles($this->testDir, ['recursive' => true, 'followLinks' => true]);
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);

        $this->assertContains('ignore.php', $names);

        // Nettoyage du lien après test
        unlink($link);
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesSorting()
    {
        $filesByName = listFiles($this->testDir, ['sort' => 'name']);
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $filesByName);
        $sortedNames = $names;
        sort($sortedNames);
        $this->assertEquals($sortedNames, $names);

        $filesByMTime = listFiles($this->testDir, ['sort' => 'mtime']);
        $namesByMTime = array_map(fn(SplFileInfo $f) => $f->getFilename(), $filesByMTime);
        $this->assertCount(count($names), $namesByMTime); // Simple vérification, difficile de prévoir ordre exact sans sleep
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithCallbackMapping()
    {
        $files = listFiles($this->testDir, [
            'patterns' => '*.php',
            'filter'   => fn(SplFileInfo $f) => strtoupper($f->getFilename()),
        ]);
        $this->assertContains('FOO.PHP', $files);
        $this->assertContains('TEST123.PHP', $files);
    }

    /**
     * @throws DirectoryException
     */
    public function testListFilesWithMultiplePatterns()
    {
        $patterns = ['*.php', '*.txt'];
        $files = listFiles($this->testDir, ['patterns' => $patterns]);
        $names = array_map(fn(SplFileInfo $f) => $f->getFilename(), $files);

        $this->assertContains('foo.php', $names);
        $this->assertContains('text.txt', $names);
    }
}