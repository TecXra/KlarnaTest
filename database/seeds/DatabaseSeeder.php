<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Eloquent::unguard();

    	$this->call('ProductCategoriesTableSeeder');
        $this->command->info('ProductCategories table seeded!');
        $this->call('SuppliersTableSeeder');
        $this->command->info('Suppliers table seeded!');
        $this->call('ProductTypeTableSeeder');
        $this->command->info('ProductType table seeded!');
        $this->call('UserTypeTableSeeder');
        $this->command->info('UserType table seeded!');
        $this->call('ProfitsTableSeeder');
        $this->command->info('Profits table seeded!');
        $this->call('ProductsTableSeeder');
        $this->command->info('Products table seeded!');
     	$this->call('ProductImagesTableSeeder');
    	$this->command->info('ProductImages table seeded!');
        $this->call('ShippingCostTableSeeder');
        $this->command->info('ShippingCost table seeded!');
        $this->call('PaymentsTableSeeder');
        $this->command->info('Payments table seeded!');
        $this->call('PaymentMethodsTableSeeder');
        $this->command->info('PaymentMethods table seeded!');
        $this->call('OrderStatusTableSeeder');
        $this->command->info('OrderStatus table seeded!');
        $this->call('DeliveryMethodsTableSeeder');
        $this->command->info('DeliveryMethods table seeded!');
        $this->call('PagesTableSeeder');
        $this->command->info('Default pages created!');
        $this->call('MenusTableSeeder');
        $this->command->info('Menues Categories added!');
        $this->call('UsersTableSeeder');
        $this->command->info('Admin User added!');
        $this->call('SettingsTableSeeder');
        $this->command->info('Settings table seeded!');
        // $this->call('OrderTableSeeder');
        // $this->command->info('Order table seeded!');
        // $this->call('OrderDetailTableSeeder');
        // $this->command->info('OrderDetail table seeded!');
    }
}
