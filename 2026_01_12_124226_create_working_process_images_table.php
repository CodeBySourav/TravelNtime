use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('working_process_images', function (Blueprint $table) {
            $table->id();
            $table->string('page')->default('home');
            $table->string('image_1')->nullable(); // ✈️ Airplane
            $table->string('image_2')->nullable(); // 👩 Girl
            $table->string('image_3')->nullable(); // 🦁 Lion
            $table->string('image_4')->nullable(); // 👨‍👩‍👧 Family
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('working_process_images');
    }
};

