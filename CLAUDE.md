# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**minfraud-api-php** is MaxMind's official PHP client library for the minFraud web services:
- **minFraud Score, Insights, and Factors**: Fraud detection services that analyze transaction data and return risk scores
- **Report Transaction API**: Feedback mechanism to report fraudulent transactions for continuous model improvement

The library provides an immutable, fluent interface for building requests using `->with*()` methods, which return new objects rather than modifying existing ones.

**Key Technologies:**
- PHP 8.1+ (uses modern PHP features like readonly properties and strict types)
- GeoIP2-php for IP geolocation data
- MaxMind Web Service Common for HTTP client functionality
- PHPUnit for testing
- php-cs-fixer, phpcs, and phpstan for code quality

## Code Architecture

### Package Structure

```
MaxMind/
├── MinFraud.php           # Main client for Score/Insights/Factors
├── MinFraud/
│   ├── Model/             # Response models (Score, Insights, Factors, etc.)
│   ├── ServiceClient      # Base HTTP client functionality
│   ├── ReportTransaction  # Client for Report Transaction API
│   └── Util               # Helper utilities (email hashing, etc.)
└── Exception/             # Custom exceptions (InvalidInputException, etc.)
```

### Key Design Patterns

#### 1. **Immutable Fluent Interface**

The `MinFraud` class is immutable. All `->with*()` methods return a new cloned object:

```php
$mf = new MinFraud(1, 'LICENSE_KEY');

// Each with* call returns a NEW object
$request = $mf->withDevice(['ip_address' => '1.1.1.1'])
              ->withEmail(['domain' => 'example.com'])
              ->withBilling(['country' => 'US']);

// Original $mf is unchanged
```

**Key Points:**
- Always assign the return value when chaining methods
- Use `clone $this` when creating modified objects
- Never modify `$this->content` directly; always work on `$new`

#### 2. **Readonly Properties for Immutable Models**

All model classes use PHP 8.1+ `readonly` properties:

```php
class Score implements \JsonSerializable
{
    public readonly float $riskScore;
    public readonly string $id;
    public readonly array $warnings;
}
```

**Key Points:**
- Properties are set once in the constructor and cannot be modified
- Use `readonly` keyword for all public properties
- Nullable properties use `?Type` syntax

#### 3. **Model Inheritance Hierarchy**

Models follow clear inheritance patterns:
- `Score` → base response with risk score, warnings, disposition
- `Insights` extends `Score` → adds detailed data (IP address, credit card, email, billing/shipping addresses, device)
- `Factors` extends `Insights` → adds risk score reasons and subscores (deprecated)

#### 4. **JsonSerializable Implementation**

All model classes implement `\JsonSerializable`:

```php
public function jsonSerialize(): array
{
    $js = parent::jsonSerialize();

    if ($this->fieldName !== null) {
        $js['field_name'] = $this->fieldName;
    }

    return $js;
}
```

- Only include non-null/non-empty values in JSON output
- Use snake_case for JSON keys (matching API format)
- Properties use camelCase in PHP

#### 5. **Input Validation Pattern**

The library validates input by default (can be disabled with `validateInput: false` option):

```php
private function verifyCountryCode(string $country): void
{
    if (!preg_match('/^[A-Z]{2}$/', $country)) {
        $this->maybeThrowInvalidInputException("...");
    }
}
```

- Validation methods throw `InvalidInputException` when enabled
- `maybeThrowInvalidInputException()` respects the `validateInput` option
- Common validations: country codes, region codes, phone codes, email addresses, IP addresses

#### 6. **Dual Array/Named Arguments Pattern**

Most `->with*()` methods support both array and named arguments:

```php
// Array style (snake_case keys)
$mf->withDevice(['ip_address' => '1.1.1.1', 'session_age' => 3600]);

// Named arguments (camelCase)
$mf->withDevice(ipAddress: '1.1.1.1', sessionAge: 3600);

// Cannot mix both - throws InvalidArgumentException
```

The implementation uses `func_num_args()` to detect mixing and `$this->remove()` to extract values from the array.

## Testing Conventions

### Running Tests

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit

# Run specific test class
vendor/bin/phpunit tests/MaxMind/Test/MinFraud/Model/FactorsTest.php

# Run with coverage (if xdebug installed)
vendor/bin/phpunit --coverage-html coverage/
```

### Linting and Static Analysis

```bash
# PHP-CS-Fixer (code style)
vendor/bin/php-cs-fixer fix --verbose --diff --dry-run

# Apply fixes
vendor/bin/php-cs-fixer fix

# PHPCS (PSR-2 compliance)
vendor/bin/phpcs -p --standard=PSR2 src/

# PHPStan (static analysis)
vendor/bin/phpstan analyze

# Validate composer.json
composer validate
```

### Test Structure

Tests are organized by component:
- `tests/MaxMind/Test/MinFraud/Model/` - Response model tests
- `tests/MaxMind/Test/MinFraud/ReportTransaction/` - Report Transaction API tests

### Test Patterns

When adding new fields to models:
1. Update the test method to include the new field in the `$response` array
2. Add assertions to verify the field is properly populated
3. Test both presence and absence of the field (null/empty handling)
4. Verify JSON serialization includes the field correctly

Example:
```php
public function testFull(): void
{
    $response = [
        'risk_score' => 42.5,
        'id' => '12345678-1234-1234-1234-123456789012',
        'funds_remaining' => 100.50,
        'queries_remaining' => 5000,
    ];

    $model = new Score($response);

    $this->assertSame(42.5, $model->riskScore);
    $this->assertSame('12345678-1234-1234-1234-123456789012', $model->id);
}
```

## Working with This Codebase

### Adding New Fields to Request Methods

When adding a new input field to a `->with*()` method:

1. **Add the named parameter** to the method signature:
   ```php
   public function withEvent(
       array $values = [],
       ?string $newField = null,
   ): self {
   ```

2. **Extract from array** if using array style:
   ```php
   if (\count($values) !== 0) {
       $newField = $this->remove($values, 'new_field');
   }
   ```

3. **Validate the input** if needed:
   ```php
   if ($newField !== null) {
       if (!\in_array($newField, ['valid1', 'valid2'], true)) {
           $this->maybeThrowInvalidInputException("...");
       }
       $values['new_field'] = $newField;
   }
   ```

4. **Update the request content**:
   ```php
   $new = clone $this;
   $new->content['event'] = $values;
   return $new;
   ```

5. **Update PHPDoc** with full documentation
6. **Add tests** for the new field
7. **Update CHANGELOG.md**

### Adding New Fields to Response Models

When adding a new field to a response model:

1. **Add the readonly property** with proper type hints and PHPDoc:
   ```php
   /**
    * @var string|null description of the field
    */
   public readonly ?string $fieldName;
   ```

2. **Update the constructor** to set the field from the response array:
   ```php
   $this->fieldName = $response['field_name'] ?? null;
   ```

3. **Update `jsonSerialize()`** to include the field:
   ```php
   if ($this->fieldName !== null) {
       $js['field_name'] = $this->fieldName;
   }
   ```

4. **Add comprehensive PHPDoc** describing the field
5. **Update tests** to include the new field in test data and assertions
6. **Update CHANGELOG.md** with the change

### Adding New Models

When creating a new model class:

1. **Determine the appropriate base class** (Score, Insights, or standalone)
2. **Use `readonly` properties** for all public fields
3. **Implement `\JsonSerializable`** interface
4. **Follow the constructor pattern**: accept `array $response` and optional `array $locales`
5. **Provide comprehensive PHPDoc** for all properties
6. **Add corresponding tests** with full coverage

### Adding New Validation

When adding input validation:

1. **Create a validation method** following the pattern:
   ```php
   private function verifyFieldName(string $value): void
   {
       if (!preg_match('/pattern/', $value)) {
           $this->maybeThrowInvalidInputException("error message");
       }
   }
   ```

2. **Call it from the `->with*()` method** before setting the value
3. **Use `maybeThrowInvalidInputException()`** to respect the `validateInput` option
4. **Add tests** for both valid and invalid inputs

### CHANGELOG.md Format

Always update `CHANGELOG.md` for user-facing changes.

**Important**: Do not add a date to changelog entries until release time.

- If there's an existing version entry without a date, add your changes there
- If creating a new version entry, do not include a date
- The release date will be added when the version is released

```markdown
3.5.0
------------------

* A new `fieldName` input has been added to the `/event` object.
* Added `new_processor` to the payment processor validation.
```

## Common Pitfalls and Solutions

### Problem: Modifying Immutable Objects

Attempting to modify `$this` directly breaks immutability.

**Solution**: Always clone before modifying:
```php
$new = clone $this;
$new->content['device'] = $values;
return $new;
```

### Problem: Mixing Array and Named Arguments

Users cannot use both array and named arguments simultaneously.

**Solution**: Check argument count and throw clear exception:
```php
if (\count($values) !== 0) {
    if (\func_num_args() !== 1) {
        throw new \InvalidArgumentException(
            'You may only provide the $values array or named arguments, not both.'
        );
    }
}
```

### Problem: Incorrect Validation Lists

Adding new enums (event types, payment methods, etc.) requires updating validation lists.

**Solution**: When the API adds new values, update the validation arrays in the corresponding `->with*()` methods and document in CHANGELOG.md.

### Problem: Missing JSON Serialization

New fields not appearing in JSON output.

**Solution**: Always update `jsonSerialize()` to include new fields:
- Check if the value is not null/empty before adding
- Use snake_case for JSON keys to match API format
- Call parent's `jsonSerialize()` first if extending

## Code Style Requirements

- **PSR-2 compliance** enforced by phpcs
- **PHP-CS-Fixer** rules defined in `.php-cs-fixer.php`
- **Strict types** (`declare(strict_types=1)`) in all files
- **Yoda style disabled** - use normal comparison order (`$var === $value`)
- **Strict comparison** required (`===` and `!==` instead of `==` and `!=`)
- **No trailing whitespace**
- **Unix line endings (LF)**

## Development Workflow

### Setup
```bash
composer install
```

### Before Committing
```bash
# Run all checks
vendor/bin/php-cs-fixer fix
vendor/bin/phpcs -p --standard=PSR2 src/
vendor/bin/phpstan analyze
vendor/bin/phpunit
```

### Version Requirements
- **PHP 8.1+** required
- Uses modern PHP features (readonly, named arguments, etc.)
- Target compatibility should match current supported PHP versions (8.1-8.4)

## Additional Resources

- [API Documentation](https://maxmind.github.io/minfraud-api-php/)
- [minFraud Web Services Docs](https://dev.maxmind.com/minfraud/)
- [Report Transaction API Docs](https://dev.maxmind.com/minfraud/report-transaction/)
- GitHub Issues: https://github.com/maxmind/minfraud-api-php/issues
