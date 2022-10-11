LARAVEL TRANSFLOW
==================
Step1: composer install

Step2: php artisan migrate ( Which run the migrations for db tables structure)

Step3: Create tenants

 - Run php artisan tinker

After running tinker you will be psy shell in the shell execute below for domains creation.

- use Stancl\Tenancy\Tenant;
   Tenant::new()->withDomains(['localhost', 'transflow.localhost.com'])->withData(['plan' => 'free'])->save();

- After creating tenant publish the vendor
   - php artisan vendor:publish --provider='Stancl\Tenancy\TenancyServiceProvider' --tag=config

- For System db migration need to run below
    php artisan migrate

- After creating the tenants you run the tenants migration
   - php artisan tenants:migrate

For seeding you need to do
   - php artisan db:seed (for system db)
   - php artisan tenants:seed (for tenant db seed)

If you execute migration and seed then no need to generate the model, controller and resources Below are the samples for generation.

   For creating KPTOrganization model & controller
  	 - php artisan make:model --migration --controller --resource Kptorganization

   For Creating SubOrganization Model,Controller & resources
    - php artisan make:model --controller --resource KptSubOrganizations

   For Creating Department Model,Controller & resources
    - php artisan make:model --controller --resource KptDepartments
	
 After above setup . please run this command for additional columns updates.
	- php artisan migrate:fresh --seed


If you getting the issue at composer install do run composer install --ignore-platform-reqs