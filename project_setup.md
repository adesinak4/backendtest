
# Project Setup and Documentation

## 1. Clone the Repository
Clone the repository to your local machine:
```bash
git clone https://github.com/your-repo/app.git
cd app
```

## 2. Install Dependencies
Install the dependencies using Composer:
```bash
composer install
```

## 3. Environment Configuration
Copy the example environment configuration file and edit it with your environment details:
```bash
cp .env.example .env
```
Update the `.env` file with your database details and other necessary configuration.

## 4. Generate Application Key
Run the following command to generate the application key:
```bash
php artisan key:generate
```

## 5. Run Migrations
Run the database migrations to set up the necessary tables:
```bash
php artisan migrate
```

## 6. Seeding the Database
Optionally, you can seed the database with default data:
```bash
php artisan db:seed
```

## 7. Run the Application
Start the Laravel development server:
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`.

## 8. Running Tests

### Unit Tests
To write unit tests in Laravel, PHPUnit comes built-in.

**Example: Unit Test for Transaction Creation**
```php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_transaction_can_be_created()
    {
        // Create a user (maker)
        $user = User::factory()->create(['role' => 'maker']);

        // Acting as that user
        $this->actingAs($user);

        // Create a transaction
        $response = $this->post('/transactions', [
            'type' => 'credit',
            'description' => 'Initial deposit',
            'amount' => 1000
        ]);

        // Assert the transaction was created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'credit',
            'description' => 'Initial deposit',
            'amount' => 1000,
            'status' => 'pending'
        ]);

        // Assert the response was successful
        $response->assertStatus(201);
    }
}
```

### Feature Test for Role-based Functionality

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_maker_can_access_create_transaction_page()
    {
        // Create users with different roles
        $maker = User::factory()->create(['role' => 'maker']);
        $checker = User::factory()->create(['role' => 'checker']);

        // Maker should be able to access the page
        $this->actingAs($maker)
            ->get('/transactions/create')
            ->assertStatus(200);

        // Checker should be forbidden
        $this->actingAs($checker)
            ->get('/transactions/create')
            ->assertStatus(403);
    }
}
```

### Running Tests
Run the tests using PHPUnit:
```bash
php artisan test
```

## 9. Notifications & Emails
To enable notifications or emails, ensure you have a mail configuration set up in the `.env` file:
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Use Mailtrap for testing or configure it to use any other mail provider.
