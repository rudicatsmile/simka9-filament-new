<?php

namespace Database\Factories;

use App\Models\TabelPropinsi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelPropinsi>
 */
class TabelPropinsiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TabelPropinsi::class;

    /**
     * List of Indonesian provinces with their codes and names
     *
     * @var array
     */
    private static $provinces = [
        ['kode' => '11', 'nama_propinsi' => 'Aceh'],
        ['kode' => '12', 'nama_propinsi' => 'Sumatera Utara'],
        ['kode' => '13', 'nama_propinsi' => 'Sumatera Barat'],
        ['kode' => '14', 'nama_propinsi' => 'Riau'],
        ['kode' => '15', 'nama_propinsi' => 'Jambi'],
        ['kode' => '16', 'nama_propinsi' => 'Sumatera Selatan'],
        ['kode' => '17', 'nama_propinsi' => 'Bengkulu'],
        ['kode' => '18', 'nama_propinsi' => 'Lampung'],
        ['kode' => '19', 'nama_propinsi' => 'Kepulauan Bangka Belitung'],
        ['kode' => '21', 'nama_propinsi' => 'Kepulauan Riau'],
        ['kode' => '31', 'nama_propinsi' => 'DKI Jakarta'],
        ['kode' => '32', 'nama_propinsi' => 'Jawa Barat'],
        ['kode' => '33', 'nama_propinsi' => 'Jawa Tengah'],
        ['kode' => '34', 'nama_propinsi' => 'DI Yogyakarta'],
        ['kode' => '35', 'nama_propinsi' => 'Jawa Timur'],
        ['kode' => '36', 'nama_propinsi' => 'Banten'],
        ['kode' => '51', 'nama_propinsi' => 'Bali'],
        ['kode' => '52', 'nama_propinsi' => 'Nusa Tenggara Barat'],
        ['kode' => '53', 'nama_propinsi' => 'Nusa Tenggara Timur'],
        ['kode' => '61', 'nama_propinsi' => 'Kalimantan Barat'],
        ['kode' => '62', 'nama_propinsi' => 'Kalimantan Tengah'],
        ['kode' => '63', 'nama_propinsi' => 'Kalimantan Selatan'],
        ['kode' => '64', 'nama_propinsi' => 'Kalimantan Timur'],
        ['kode' => '65', 'nama_propinsi' => 'Kalimantan Utara'],
        ['kode' => '71', 'nama_propinsi' => 'Sulawesi Utara'],
        ['kode' => '72', 'nama_propinsi' => 'Sulawesi Tengah'],
        ['kode' => '73', 'nama_propinsi' => 'Sulawesi Selatan'],
        ['kode' => '74', 'nama_propinsi' => 'Sulawesi Tenggara'],
        ['kode' => '75', 'nama_propinsi' => 'Gorontalo'],
        ['kode' => '76', 'nama_propinsi' => 'Sulawesi Barat'],
        ['kode' => '81', 'nama_propinsi' => 'Maluku'],
        ['kode' => '82', 'nama_propinsi' => 'Maluku Utara'],
        ['kode' => '91', 'nama_propinsi' => 'Papua Barat'],
        ['kode' => '94', 'nama_propinsi' => 'Papua'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $index = 0;
        
        // Get the current province data
        $province = self::$provinces[$index % count(self::$provinces)];
        $index++;

        return [
            'kode' => $province['kode'],
            'nama_propinsi' => $province['nama_propinsi'],
            'status' => '1',
            'urut' => $index,
        ];
    }

    /**
     * Create a specific province by code
     *
     * @param string $kode
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withKode(string $kode): Factory
    {
        $province = collect(self::$provinces)->firstWhere('kode', $kode);
        
        if (!$province) {
            throw new \InvalidArgumentException("Province with code '{$kode}' not found");
        }

        return $this->state([
            'kode' => $province['kode'],
            'nama_propinsi' => $province['nama_propinsi'],
        ]);
    }

    /**
     * Create all Indonesian provinces
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function createAllProvinces()
    {
        $provinces = collect();
        
        foreach (self::$provinces as $index => $province) {
            $provinces->push(TabelPropinsi::factory()->create([
                'kode' => $province['kode'],
                'nama_propinsi' => $province['nama_propinsi'],
                'status' => '1',
                'urut' => $index + 1,
            ]));
        }

        return $provinces;
    }
}
