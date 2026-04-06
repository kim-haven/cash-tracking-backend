<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Blaze accounting export columns → snake_case. Monetary fields use decimal(18,4).
     */
    public function up(): void
    {
        Schema::create('blaze_accounting_summary', function (Blueprint $table) {
            $table->id();

            $table->date('date')->nullable();
            $table->string('shop')->nullable();
            $table->string('company')->nullable();
            $table->string('queue_type')->nullable();

            $this->moneyColumns($table, [
                'adult_retail_value_of_sales',
                'medical_retail_value_of_sales',
                'non_cannabis_retail_value_of_sales',
                'retail_value_of_sales',
                'pre_al_excise_tax',
                'pre_nal_excise_tax',
                'pre_city_tax',
                'pre_county_tax',
                'pre_state_tax',
                'pre_federal_tax',
                'adult_gross_sales',
                'medical_gross_sales',
                'non_cannabis_gross_sales',
                'gross_sales',
                'delivery_fee',
                'ach_fee',
                'blazepay_fee',
                'aeropay_fee',
                'blazepay_ach_fee',
                'cashless_atm_fee',
                'cash_fee',
                'credit_debit_fee',
                'stronghold_fee',
                'pre_tax_discount',
                'adult_net_sales',
                'adult_net_sales_wo_fees',
                'medical_net_sales',
                'medical_net_sales_wo_fees',
                'non_cannabis_net_sales',
                'non_cannabis_net_sales_wo_fees',
                'net_sales',
                'net_sales_wo_fees',
                'post_al_excise_tax',
                'post_nal_excise_tax',
                'post_city_tax',
                'post_county_tax',
                'post_state_tax',
                'post_federal_tax',
                'delivery_fee_excise_tax',
                'city_delivery_fee_tax',
                'county_delivery_fee_tax',
                'state_delivery_fee_tax',
                'federal_delivery_fee_tax',
                'adult_total_tax',
                'medical_total_tax',
                'non_cannabis_total_tax',
                'total_tax',
                'after_tax_discount',
                'rounding',
                'adjustments',
                'adult_total_due',
                'medical_total_due',
                'non_cannabis_total_due',
                'total_due',
                'tips',
                'blazepay_tips',
                'aeropay_tips',
                'blazepay_ach_tips',
            ]);

            $table->unsignedBigInteger('number_of_transactions')->default(0);
            $table->unsignedBigInteger('count_of_completed_sales')->default(0);
            $table->unsignedBigInteger('count_of_refunds')->default(0);
            $table->unsignedBigInteger('new_members')->default(0);
            $table->unsignedBigInteger('returning_members')->default(0);

            $this->moneyColumns($table, [
                'adult_cogs',
                'medical_cogs',
                'non_cannabis_cogs',
                'ach_tendered',
                'blazepay_tendered',
                'aeropay_tendered',
                'blazepay_ach_tendered',
                'cashless_atm_tendered',
                'cash_tendered',
                'check_tendered',
                'credit_debit_tendered',
                'gift_card_tendered',
                'birchmount_tendered',
                'store_credit_tendered',
                'stronghold_tendered',
                'payment_tendered',
                'ach_change_due',
                'blazepay_change_due',
                'aeropay_change_due',
                'blazepay_ach_change_due',
                'cashless_atm_change_due',
                'cash_change_due',
                'check_change_due',
                'credit_debit_change_due',
                'gift_card_change_due',
                'store_credit_change_due',
                'stronghold_change_due',
                'change_due',
            ]);

            $table->unsignedBigInteger('items_sold')->default(0);
            $table->unsignedBigInteger('items_refunded')->default(0);

            $this->moneyColumns($table, [
                'refund_total_due',
                'surcharge_fee_tax',
                'blazepay_cashback',
                'aeropay_cashback',
                'blazepay_ach_cashback',
                'cashless_atm_cashback',
                'untaxed_fee',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blaze_accounting_summary');
    }

    /**
     * @param  list<string>  $columns
     */
    private function moneyColumns(Blueprint $table, array $columns): void
    {
        foreach ($columns as $column) {
            $table->decimal($column, 18, 4)->default(0);
        }
    }
};
