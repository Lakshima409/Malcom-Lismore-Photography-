<?php
/**
 * Integration tests — these touch the live database (read-only).
 *
 * Each test skips gracefully when MySQL is unavailable, so the suite still
 * runs green in environments without a database while giving real coverage
 * when XAMPP/MySQL is up. All queries are SELECT-only: no data is written.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class IntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        if (!tests_db_available()) {
            $this->markTestSkipped('Database not reachable — skipping integration test.');
        }
    }

    public function testDbReturnsAPdoInstance(): void
    {
        $this->assertInstanceOf(PDO::class, db());
    }

    public function testCategoriesAreSeeded(): void
    {
        $categories = get_categories();
        $this->assertNotEmpty($categories, 'Expected at least one category.');

        foreach ($categories as $cat) {
            $this->assertArrayHasKey('slug', $cat);
            $this->assertArrayHasKey('name', $cat);
        }
    }

    public function testGetPhotosReturnsRowsAndTotal(): void
    {
        $result = get_photos(null, 5, 0);

        $this->assertArrayHasKey('rows', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertIsInt($result['total']);
        $this->assertGreaterThanOrEqual(0, $result['total']);
        $this->assertLessThanOrEqual(5, count($result['rows']));
        // You can never return more rows than exist in total.
        $this->assertLessThanOrEqual($result['total'], count($result['rows']));
    }

    public function testGetPhotosByCategoryFiltersCorrectly(): void
    {
        $categories = get_categories();
        if (empty($categories)) {
            $this->markTestSkipped('No categories to filter by.');
        }

        $slug      = $categories[0]['slug'];
        $filtered  = get_photos($slug, 50, 0);

        // Every returned row must belong to the requested category.
        foreach ($filtered['rows'] as $row) {
            $this->assertSame($slug, $row['category_slug']);
        }
    }

    public function testServicesAreSeededForPricesPage(): void
    {
        $services = get_services();
        $this->assertNotEmpty($services, 'Expected at least one active service.');

        foreach ($services as $service) {
            $this->assertArrayHasKey('name', $service);
            $this->assertArrayHasKey('description', $service);
        }
    }

    public function testAdminAccountExists(): void
    {
        $count = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        $this->assertGreaterThanOrEqual(1, $count, 'Expected at least one admin account.');
    }

    public function testGetPhotoReturnsNullForNonExistentId(): void
    {
        $this->assertNull(get_photo(999999));
    }
}
