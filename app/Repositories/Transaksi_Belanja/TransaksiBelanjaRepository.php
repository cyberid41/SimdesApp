<?php namespace SimdesApp\Repositories\Transaksi_Belanja;

use SimdesApp\Models\TransaksiBelanja;
use SimdesApp\Repositories\AbstractRepository;
use SimdesApp\Repositories\Organisasi\OrganisasiRepository;
use SimdesApp\Services\LaraCacheInterface;

class TransaksiBelanjaRepository extends AbstractRepository
{

    /**
     * @var LaraCacheInterface
     */
    protected $cache;

    protected $organisasi;

    public function __construct(TransaksiBelanja $transaksiBelanja, LaraCacheInterface $cache, OrganisasiRepository $organisasi)
    {
        $this->model = $transaksiBelanja;
        $this->cache = $cache;
        $this->organisasi = $organisasi;
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
        // set key
        $key = 'transaksi-belanja-find' . $page . $limit . $term;

        // set section
        $section = 'transaksi-belanja';

        // has section and key
        if ($this->cache->has($section, $key)) {
            return $this->cache->get($section, $key);
        }

        // query to database
        $belanja = $this->model
            ->where('belanja', 'like', '%' . $term . '%')
            ->paginate($limit)
            ->toArray();

        // store to cache
        $this->cache->put($section, $key, $belanja, 10);

        return $belanja;
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
            $belanja = $this->getNew();

            $kode_desa = $this->organisasi->getKodeDesa($data['organisasi_id']);
            $kode_rekening = e($data['kode_rekening']);
            $no_bukti = e($data['nomor_bukti']);
            $nomor_bukti = $no_bukti . '/STS-' . $kode_rekening . '/' . $kode_desa . '/' . date('Y');
            $nomor_bku_sts = $no_bukti . '/BKT.STS-' . $kode_rekening . '/' . $kode_desa . '/' . date('Y');

            $belanja->kode_rekening = $kode_rekening;
            $belanja->nomor_bukti = $no_bukti;
            $belanja->nomor_bku = $nomor_bukti;
            $belanja->nomor_bku_sts = $nomor_bku_sts;

            $belanja->belanja = e($data['belanja']);
            $belanja->belanja_id = e($data['belanja_id']);
            $belanja->tanggal = e($data['tanggal']);
            $belanja->jumlah = e($data['jumlah']);
            $belanja->item = e($data['item']);
            $belanja->harga = e($data['harga']);
            $belanja->standar_satuan_harga_id = e($data['standar_satuan_harga_id']);
            $belanja->kode_barang = e($data['kode_barang']);
            $belanja->pejabat_desa_id = e($data['pejabat_desa_id']);
            $belanja->penerima = e($data['penerima']);
            $belanja->dana_desa_id = e($data['dana_desa_id']);
            $belanja->save();

            /*Return result success*/
            return $this->successInsertResponse();

        } catch (\Exception $ex) {
            \Log::error('TransaksiBelanjaRepository create action something wrong -' . $ex);
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
            $belanja = $this->findById($id);

            $kode_desa = $this->organisasi->getKodeDesa($data['organisasi_id']);
            $kode_rekening = e($data['kode_rekening']);
            $no_bukti = e($data['nomor_bukti']);
            $nomor_bukti = $no_bukti . '/STS-' . $kode_rekening . '/' . $kode_desa . '/' . date('Y');
            $nomor_bku_sts = $no_bukti . '/BKT.STS-' . $kode_rekening . '/' . $kode_desa . '/' . date('Y');

            $belanja->kode_rekening = $kode_rekening;
            $belanja->nomor_bukti = $no_bukti;
            $belanja->nomor_bku = $nomor_bukti;
            $belanja->nomor_bku_sts = $nomor_bku_sts;

            $belanja->belanja = e($data['belanja']);
            $belanja->belanja_id = e($data['belanja_id']);
            $belanja->tanggal = e($data['tanggal']);
            $belanja->jumlah = e($data['jumlah']);
            $belanja->item = e($data['item']);
            $belanja->harga = e($data['harga']);
            $belanja->standar_satuan_harga_id = e($data['standar_satuan_harga_id']);
            $belanja->kode_barang = e($data['kode_barang']);
            $belanja->pejabat_desa_id = e($data['pejabat_desa_id']);
            $belanja->penerima = e($data['penerima']);
            $belanja->dana_desa_id = e($data['dana_desa_id']);
            $belanja->save();

            /*Return result success*/
            return $this->successUpdateResponse();

        } catch (\Exception $ex) {
            \Log::error('TransaksiBelanjaRepository update action something wrong -' . $ex);
            //return $this->errorUpdateResponse();
            return $ex;
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
            $belanja = $this->findById($id);

            if ($belanja) {
                $belanja->delete();

                return $this->successDeleteResponse();
            }

            return $this->successResponseOk([
                'success' => false,
                'message' => [
                    'msg' => 'Record sudah tidak ada atau sudah dihapus.',
                ],
            ]);
        } catch (\Exception $ex) {
            \Log::error('TransaksiBelanjaRepository destroy action something wrong -' . $ex);
            return $this->errorDeleteResponse();
        }
    }

}