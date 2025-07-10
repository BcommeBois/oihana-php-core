<?php

namespace oihana\core\files ;

use PHPUnit\Framework\TestCase;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

use oihana\exceptions\DirectoryException;

class CreateDirectoryTest extends TestCase
{
    private vfsStreamDirectory $root;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup('testDir' , null,
        [
            'existingDir' => [],
            'existingFile.txt' => 'This is a file'
        ]) ;
    }

    /**
     * @throws DirectoryException
     */
    public function testDeleteNonEmptyDirectory()
    {
        // Create a directory structure
        vfsStream::create([
            'dirToDelete' => [
                'subdir' => [
                    'file1.txt' => 'content',
                    'file2.txt' => 'content'
                ],
                'file3.txt' => 'content'
            ]
        ], $this->root);

        $directoryPath = vfsStream::url('testDir/dirToDelete');

        // Call the function
        $result = deleteDirectory($directoryPath);

        // Assertions
        $this->assertTrue($result);
        $this->assertFalse($this->root->hasChild('dirToDelete'));
    }

    public function testNullOrEmptyDirectoryPath():void
    {
        $this->expectException(DirectoryException::class);
        $this->expectExceptionMessage('Directory path cannot be null or empty.');

        createDirectory(null);
        createDirectory('');
        createDirectory('   ');
    }
    //
    // public function testDirectoryCreationFails():void
    // {
    //     // Créer un fichier temporaire qui bloquera la création du répertoire
    //     $tempFile = tempnam(sys_get_temp_dir(), 'test_create_dir_fail_');
    //     file_put_contents($tempFile, 'test content');
    //
    //     $this->expectException(DirectoryException::class);
    //     $this->expectExceptionMessageMatches('/Failed to create directory/');
    //
    //     // Supprimer les avertissements PHP pendant ce test
    //     set_error_handler(function( $errno , $errstr )
    //     {
    //         if ( str_contains( $errstr , 'mkdir(): File exists' ) )
    //         {
    //             return true; // Ignorer cet avertissement
    //         }
    //         return false; // Laisser les autres erreurs être gérées normalement
    //     });
    //
    //     try
    //     {
    //         createDirectory( $tempFile ) ;
    //         $this->fail( 'Expected DirectoryException was not thrown' ) ;
    //     }
    //     catch ( DirectoryException $e )
    //     {
    //         // C'est l'erreur que nous souhaitons
    //     }
    //     catch ( Exception $e )
    //     {
    //         $this->fail('Unexpected exception: ' . $e->getMessage() );
    //     }
    //     finally
    //     {
    //         restore_error_handler();
    //         @unlink( $tempFile ) ;
    //     }
    // }

    // public function testDirectoryAlreadyExistsButNotWritable():void
    // {
    //     if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
    //     {
    //         $this->markTestSkipped('Permission tests are not reliable on Windows.');
    //     }
    //
    //     $tempDir = sys_get_temp_dir() . '/test_existing_unwritable_' . uniqid() ;
    //     mkdir( $tempDir );
    //
    //     clearstatcache();
    //     if ( is_writable( $tempDir ) )
    //     {
    //         $this->markTestSkipped( 'Could not make directory non-writable for test' );
    //     }
    //     // Utiliser une expression régulière plus flexible pour le chemin
    //     $this->expectException(DirectoryException::class);
    //     $this->expectExceptionMessageMatches('/Directory ".*" is not writable/');
    //
    //     try {
    //         createDirectory($tempDir);
    //         $this->fail('Expected DirectoryException was not thrown');
    //     } catch (DirectoryException $e) {
    //         // Nettoyer
    //         @chmod($tempDir, 0777);
    //         @rmdir($tempDir);
    //         throw $e;
    //     }
    // }

    /**
     * @throws DirectoryException
     */
    public function testRecursiveOption():void
    {
        // Test avec la création récursive activée (par défaut)
        $recursivePath = vfsStream::url('testDir/recursive/option/test');
        $result = createDirectory($recursivePath);
        $this->assertTrue(is_dir($result));

        // Test avec la création récursive désactivée
        $nonRecursivePath = vfsStream::url('testDir/nonrecursive/shouldfail');
        $this->expectException(DirectoryException::class);
        createDirectory($nonRecursivePath, 0755, false);
    }
}