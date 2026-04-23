<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankDetailsToWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->string('currency')->nullable()->after('reject_reason');
            $table->string('account_holder_name')->nullable()->after('currency');
            $table->string('bank_name')->nullable()->after('account_holder_name');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->dropColumn(['currency', 'account_holder_name', 'bank_name', 'bank_account_number', 'ifsc_code']);
        });
    }
}
