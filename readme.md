## Kenneth McInturf Coding Test

### Running the App
- Clone the project from this repo onto your local machine
- Navigate into the repo in your local machine, and run `composer install`
- Be sure to set your `.env` with your local MySQl setup (can use .env.example as template)
- Run `php artisan migrate`
- Application can be run locally with `php artisan serve`

## Features
Along with the base requirements of the app, I added a few additional features

### Add Order
- Top section allows users to add orders, by setting key and tech

![image](https://user-images.githubusercontent.com/43353352/144747028-ad61ff18-ce46-453a-bc6c-b0adef03c5b8.png)

### Update Order
- Clicking Update Order enables an update form that allows users to make changes to an order

![image](https://user-images.githubusercontent.com/43353352/144747258-106a0556-5003-4fe2-b3fb-42d14f4278de.png)


### Delete Order

![image](https://user-images.githubusercontent.com/43353352/144747094-3e9ee692-3513-448a-a44d-caf06d436d7a.png)

### Testing
Included a few basic test, which can be run with `vendor/bin/phpunit` command
