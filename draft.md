https://laravel.com/docs/10.x/passwords
https://www.itsolutionstuff.com/post/how-to-use-login-throttle-in-laravel-5example.html
https://morioh.com/p/41781f8cc005


To implement a password reset functionality in Laravel with a manual authentication system, you'll need to follow these steps:


1. Create a password reset table: Run the following command in your terminal to generate a migration for the password reset table:
   ```
   php artisan make:migration create_password_resets_table
   ```

   In the migration file, define the table structure with the necessary columns like `email`, `token`, and `created_at`. Example:
   ```php
   Schema::create('password_resets', function (Blueprint $table) {
       $table->string('email')->index();
       $table->string('token');
       $table->timestamp('created_at')->nullable();
   });
   ```

   Run the migration using `php artisan migrate` command to create the password reset table.

2. Create the password reset routes: In your `routes/web.php` file, add the necessary routes for password reset:
   ```php
   // Password Reset Routes
   Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
   Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
   Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
   Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');
   ```

3. Create the password reset email view: Create a new view file `resources/views/auth/passwords/email.blade.php` and design the password reset email template. This view will be used to send the password reset link to the user's email address.

4. Create the password reset controllers: Laravel provides pre-built controllers for password reset functionality. You can generate them using the following commands:
   ```
   php artisan make:controller Auth\ForgotPasswordController
   php artisan make:controller Auth\ResetPasswordController
   ```

   In the `ForgotPasswordController`, you'll need to implement the logic for sending the password reset link email. Example:
   ```php
   <?php

   namespace App\Http\Controllers\Auth;

   use App\Http\Controllers\Controller;
   use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

   class ForgotPasswordController extends Controller
   {
       use SendsPasswordResetEmails;

       // Customize if needed
   }
   ```

   In the `ResetPasswordController`, you'll need to implement the logic for resetting the password. Example:
   ```php
   <?php

   namespace App\Http\Controllers\Auth;

   use App\Http\Controllers\Controller;
   use Illuminate\Foundation\Auth\ResetsPasswords;

   class ResetPasswordController extends Controller
   {
       use ResetsPasswords;

       // Customize if needed
   }
   ```

5. Update the user model: Make sure your User model implements the `Illuminate\Contracts\Auth\CanResetPassword` contract. This is required to utilize the password reset functionality in Laravel. Example:
   ```php
   <?php

   namespace App\Models;

   use Illuminate\Contracts\Auth\CanResetPassword;
   use Illuminate\Foundation\Auth\User as Authenticatable;

   class User extends Authenticatable implements CanResetPassword
   {
       // Your user model implementation
   }
   ```

6. Configure email settings: Update your `.env` file with the necessary email settings to enable sending password reset emails. Example:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS=your-email@example.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

   Make sure to replace the values with your own email service provider details.

With these steps completed, your Laravel application should have the necessary components to handle password reset functionality with a manual authentication system. Users will be able to initiate the password reset process by providing their email address, and a password reset link will be sent to their email. They can then click on the link, enter a new password, and reset their password successfully.
