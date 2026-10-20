<?php
/**
 * Unit tests for handle_image_upload() — the upload validation guard rails.
 *
 * These tests exercise the early validation branches (error codes, size
 * limits, and the real-image check). The final success path uses
 * move_uploaded_file(), which only accepts genuine HTTP uploads and so
 * cannot be triggered from the CLI test runner — that path is covered by
 * the manual/HTTP upload checks in docs/test-plan.md instead.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class ImageUploadTest extends TestCase
{
    /** Build a minimal $_FILES-style entry with sensible defaults. */
    private function file(array $overrides = []): array
    {
        return $overrides + [
            'name'     => 'test.jpg',
            'type'     => 'image/jpeg',
            'tmp_name' => '/tmp/does-not-exist.jpg',
            'error'    => UPLOAD_ERR_OK,
            'size'     => 1024,
        ];
    }

    public function testMissingErrorKeyIsRejected(): void
    {
        $result = handle_image_upload(['name' => 'x.jpg']);
        $this->assertSame('Invalid upload.', $result['error'] ?? null);
    }

    public function testArrayErrorIsRejected(): void
    {
        $result = handle_image_upload($this->file(['error' => [UPLOAD_ERR_OK]]));
        $this->assertSame('Invalid upload.', $result['error'] ?? null);
    }

    public function testIniSizeErrorReportsTooLarge(): void
    {
        $result = handle_image_upload($this->file(['error' => UPLOAD_ERR_INI_SIZE]));
        $this->assertSame('The uploaded file is too large (max 5 MB).', $result['error'] ?? null);
    }

    public function testFormSizeErrorReportsTooLarge(): void
    {
        $result = handle_image_upload($this->file(['error' => UPLOAD_ERR_FORM_SIZE]));
        $this->assertSame('The uploaded file is too large (max 5 MB).', $result['error'] ?? null);
    }

    public function testNoFileErrorIsReported(): void
    {
        $result = handle_image_upload($this->file(['error' => UPLOAD_ERR_NO_FILE]));
        $this->assertSame('No file was uploaded.', $result['error'] ?? null);
    }

    public function testUnknownErrorIsReported(): void
    {
        $result = handle_image_upload($this->file(['error' => UPLOAD_ERR_PARTIAL]));
        $this->assertSame('Sorry, the upload failed. Please try again.', $result['error'] ?? null);
    }

    public function testZeroByteFileIsRejected(): void
    {
        $result = handle_image_upload($this->file(['size' => 0]));
        $this->assertSame('The uploaded file must be smaller than 5 MB.', $result['error'] ?? null);
    }

    public function testFileOverFiveMegabytesIsRejected(): void
    {
        $result = handle_image_upload($this->file(['size' => 6 * 1024 * 1024]));
        $this->assertSame('The uploaded file must be smaller than 5 MB.', $result['error'] ?? null);
    }

    public function testMissingSizeIsRejected(): void
    {
        $file = $this->file();
        unset($file['size']);
        $result = handle_image_upload($file);
        $this->assertSame('The uploaded file must be smaller than 5 MB.', $result['error'] ?? null);
    }

    public function testNonExistentTmpFileFailsImageCheck(): void
    {
        // size passes the limit, but getimagesize() cannot read the path.
        $result = handle_image_upload($this->file([
            'tmp_name' => __DIR__ . '/no-such-file-xyz.jpg',
            'size'     => 5000,
        ]));
        $this->assertSame('That file is not a valid image.', $result['error'] ?? null);
    }

    public function testPlainTextFileFailsImageCheck(): void
    {
        // A real, readable file that is NOT an image must be rejected by the
        // getimagesize() check — proving a renamed/-text file cannot pass.
        $tmp = tempnam(sys_get_temp_dir(), 'upl');
        file_put_contents($tmp, 'this is definitely not an image, just plain text');

        $result = handle_image_upload($this->file(['tmp_name' => $tmp, 'size' => 60]));

        @unlink($tmp);

        $this->assertSame('That file is not a valid image.', $result['error'] ?? null);
    }
}
