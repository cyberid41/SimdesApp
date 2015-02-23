<?php namespace SimdesApp\Repositories\StandarSatuanHarga;

use SimdesApp\Models\StandarSatuanHarga;
use SimdesApp\Repositories\AbstractRepository;
use SimdesApp\Services\LaraCacheInterface;

class StandarSatuanHargaRepository extends AbstractRepository {

    /**
     * @var LaraCacheInterface
     */
    protected $cache;

    public function __construct(StandarSatuanHarga $standarSatuanHarga, LaraCacheInterface $cache)
    {
        $this->model = $standarSatuanHarga;
        $this->cache = $cache;
    }

    /**
     * Instant find or search with paging, limit, and query
     *
     * @param int $page
     * @param int $limit
     * @param null $term
     * @return mixed
     */
    public function find($page = 1, $limit = 10, $term = null)
    {
        // Create Key for cache
        $key = 'find-standar-satuan-harga-' . $page . $limit . $term;

        // Create Section
        $section = 'standar-satuan-harga';

        // If cache is exist get data from cache
        if ($this->cache->has($section, $key)) {
            return $this->cache->get($section, $key);
        }

        // Search data
        $standarSatuanHarga = $this->model
            ->where('barang', 'like', '%' . $term . '%')
            ->paginate($limit)
            ->toArray();

        // Create cache
        $this->cache->put($section, $key, $standarSatuanHarga, 10);

        return $standarSatuanHarga;
    }

    /**
     * Create data
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        try {
            $standarSatuanHarga = $this->getNew();

            $standarSatuanHarga->kode_rekening = e($data['kode_rekening']);
            $standarSatuanHarga->barang = e($data['barang']);
            $standarSatuanHarga->spesifikasi = e($data['spesifikasi']);
            $standarSatuanHarga->satuan = e($data['satuan']);
            $standarSatuanHarga->harga = $data['harga'];

            $standarSatuanHarga->save();

            // Return result success
            return $this->successInsertResponse();

        } catch (\Exception $ex) {
            \Log::error('StandarSatuanHargaRepository create action something wrong -' . $ex);
            return $this->errorInsertResponse();
        }
    }

    /**
     * Show the Record
     *
     * @param $id
     * @return \Illuminate\Support\Collection|null|static
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Update the record
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        try {
            $standarSatuanHarga = $this->findById($id);

            $standarSatuanHarga->kode_rekening = e($data['kode_rekening']);
            $standarSatuanHarga->barang = e($data['barang']);
            $standarSatuanHarga->spesifikasi = e($data['spesifikasi']);
            $standarSatuanHarga->satuan = e($data['satuan']);
            $standarSatuanHarga->harga = $data['harga'];

            $standarSatuanHarga->save();

            // Return result success
            return $this->successUpdateResponse();

        } catch (\Exception $ex) {
            \Log::error('StandarSatuanHargaRepository update action something wrong -' . $ex);
            return $this->errorUpdateResponse();
        }
    }

    /**
     * Destroy the record
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            $standarSatuanHarga = $this->findById($id);

            if ($standarSatuanHarga){
                $standarSatuanHarga->delete();

                // Return result success
                return $this->successDeleteResponse();
            }

            return $this->emptyDeleteResponse();

        } catch (\Exception $ex) {
            \Log::error('StandarSatuanHargaRepository destroy action something wrong -' . $ex);
            return $this->errorDeleteResponse();
        }
    }
}
