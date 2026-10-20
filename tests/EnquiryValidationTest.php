<?php
/**
 * Unit tests for validate_enquiry() - the server-side enquiry validation
 * extracted from submit-enquiry.php. These tests are pure (no database,
 * no session), so they run fast and hermetically.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/bootstrap.php';

final class EnquiryValidationTest extends TestCase
{
    /** A minimal, fully valid submission used as a base for each test. */
    private function validInput(): array
    {
        return [
            'name'         => 'Jane Doe',
            'email'        => 'jane@example.com',
            'phone'        => '+44 1463 123456',
            'service_type' => 'Wedding Photography',
            'event_date'   => '2025-06-15',
            'location'     => 'Inverness',
            'budget'       => '£1000 - £2000',
            'message'      => 'We would love a quote for our wedding, please.',
            'consent'      => '1',
        ];
    }

    public function testValidSubmissionHasNoErrors(): void
    {
        $result = validate_enquiry($this->validInput());
        $this->assertSame([], $result['errors']);
    }

    public function testMissingNameIsReported(): void
    {
        $input = $this->validInput();
        $input['name'] = '   '; // whitespace only should be treated as empty
        $result = validate_enquiry($input);
        $this->assertArrayHasKey('name', $result['errors']);
    }

    public function testNameLongerThan100CharsIsReported(): void
    {
        $input = $this->validInput();
        $input['name'] = str_repeat('a', 101);
        $result = validate_enquiry($input);
        $this->assertArrayHasKey('name', $result['errors']);
    }

    public function testNameExactly100CharsIsAccepted(): void
    {
        $input = $this->validInput();
        $input['name'] = str_repeat('a', 100);
        $result = validate_enquiry($input);
        $this->assertArrayNotHasKey('name', $result['errors']);
    }

    public function testMissingEmailIsReported(): void
    {
        $input = $this->validInput();
        $input['email'] = '';
        $this->assertArrayHasKey('email', validate_enquiry($input)['errors']);
    }

    /** @dataProvider invalidEmails */
    public function testInvalidEmailIsReported(string $email): void
    {
        $input = $this->validInput();
        $input['email'] = $email;
        $this->assertArrayHasKey('email', validate_enquiry($input)['errors']);
    }

    public static function invalidEmails(): array
    {
        return [
            'no @'          => ['not-an-email'],
            'missing local' => ['@example.com'],
            'missing tld'   => ['jane@example'],
            'has spaces'    => ['jane doe@example.com'],
        ];
    }

    /** @dataProvider validEmails */
    public function testValidEmailIsAccepted(string $email): void
    {
        $input = $this->validInput();
        $input['email'] = $email;
        $this->assertArrayNotHasKey('email', validate_enquiry($input)['errors']);
    }

    public static function validEmails(): array
    {
        return [
            'simple'    => ['jane@example.com'],
            'plus tag'  => ['jane+wedding@example.co.uk'],
            'subdomain' => ['jane@mail.example.com'],
        ];
    }
}
