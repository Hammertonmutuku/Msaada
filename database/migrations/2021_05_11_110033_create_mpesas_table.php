 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('result_desc')->nullable();
            $table->string('result_code')->nullable();
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('b2c_utility_account_available_funds')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpesas');
    }
}
