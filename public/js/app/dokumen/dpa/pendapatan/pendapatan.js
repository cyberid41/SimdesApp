app.controller('modVerifikasiDPAPendapatan', ['$scope', 'dpa_pendapatan', '$modalInstance', 'id', 'token', function ($scope, dpa_pendapatan, $modalInstance, id, token) {

    //Enable button
    $scope.process = false;

    //Inisialisasi variabel
    $scope.id = id;
    $scope.token = token;
    $scope.hasil = {
        class: '',
        msg: ''
    };

    //Jika diklik ok
    $scope.ok = function () {
        //Disable button
        $scope.process = true;

        $scope.param = {};
        $scope.param.id = $scope.id;
        $scope.param.is_finish = 1;
        $scope.param._token = $scope.token;

        dpa_pendapatan.verifikasi($scope.param)
            .success(function (data) {
                if (data.success == true) {
                    $scope.hasil.class = "success";
                } else {
                    $scope.hasil.class = "danger";
                }
                $scope.hasil.msg = data.message;
                $modalInstance.close($scope.hasil);

                //Enable button
                $scope.process = false;
            })
            .error(function (data) {
                //TODO grab the error from server
            });
    };

    //Jika diklik batal
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
}]);
app.controller('DPAPendapatanCtrl', ['$scope', 'dpa_pendapatan', '$modal', '$stateParams', function ($scope, dpa_pendapatan, $modal) {

    $scope.inputData = {};

    //siapkan scope pagination data dan pencarian data
    $scope.main = {
        page: 1,
        term: ''
    };

    //Init loading
    $scope.isFirstLoading = true;
    $scope.isFirstLoaded = false;

    //Disable Control Umum
    $scope.disUtama = {
        btnRefresh: true,
        txtCari: true,
        btnCari: true,
        btnPagging: true
    };

    //Disable Control Aksi
    $scope.disAksi = {
        btnVerifikasi: false
    };

    //Disable Control Eksekusi
    $scope.btnEksekusi = false;

    //Class Alert
    $scope.alertset = {
        class: 'success',
        msg: {}
    };

    $scope.formHidden = {
        alert: 'hidden'
    };

    //init get data
    dpa_pendapatan.get($scope.main.page, $scope.main.term)
        .success(function (data) {
            //Hide loading
            $scope.isFirstLoading = false;
            $scope.isFirstLoaded = true;

            //result data
            $scope.dpa_pendapatan = data.data;

            //set the current page
            $scope.current_page = data.current_page;

            //set the last page
            $scope.last_page = data.last_page;

            //set the data from
            $scope.from = data.from;

            //set the data until to
            $scope.to = data.to;

            //set the total result data
            $scope.total = data.total;

            //Disable All Controller
            $scope.disConAksi(false);
            $scope.disConUmum(false)
        })
        .error(function (data) {
            $scope.redirect(data);
        });

//refresh data
    $scope.setDelay = function (back) {
        //set timeout 3 seconds
        window.setTimeout(function () {
            $scope.$apply(function () {
                if ($scope.formHidden.alert == 'hidden') {
                    return false;
                }
                $scope.formHidden.alert = "hidden";
            });
        }, 5000);
    };

//Enable or Disable Contoller Umum
    $scope.disConUmum = function (opt) {
        if (opt == true) { //Disable
            $scope.disUtama = {
                btnRefresh: true,
                txtCari: true,
                btnCari: true,
                btnPagging: true

            };
        } else { //Enable
            $scope.disUtama = {
                btnRefresh: false,
                txtCari: false,
                btnCari: false,
                btnPagging: false
            };
        }
    };

//Enable or Disable Aksi controller
    $scope.disConAksi = function (opt) {
        if (opt == true) { //Disable Controller
            $scope.disAksi = {
                btnVerifikasi: true
            };
        } else { //Enable controller
            $scope.disAksi = {
                btnVerifikasi: false
            };
        }
    };

//get data
    $scope.getData = function () {

        //Start loading
        $scope.start();

        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        dpa_pendapatan.get($scope.main.page, $scope.main.term)
            .success(function (data) {
                //result data
                $scope.dpa_pendapatan = data.data;

                //set the current page
                $scope.current_page = data.current_page;

                //set the last page
                $scope.last_page = data.last_page;

                //set the data from
                $scope.from = data.from;

                //set the data until to
                $scope.to = data.to;

                //set the total result data
                $scope.total = data.total;

                $scope.done();

                //Disable All Controller
                $scope.disConUmum(false);
                $scope.disConAksi(false);
            })
            .error(function (data) {
                $scope.redirect(data);
            });
    };

//Navigasi halaman terakhir
    $scope.lastPage = function () {
        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        $scope.main.page = $scope.last_page;

        $scope.getData();
    };

//navigasi halaman selanjutnya
    $scope.nextPage = function () {
        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        //jika page = 1 < halaman terakhir
        if ($scope.main.page < $scope.last_page) {
            //halaman saat ini ditambah increment++
            $scope.main.page++
        }
        //panggil ajax data
        $scope.getData();
    };

//navigasi halaman sebelumnya
    $scope.previousPage = function () {
        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        //jika page = 1 > 1
        if ($scope.main.page > 1) {
            //page dikurangi decrement --
            $scope.main.page--
        }
        //panggil ajax data
        $scope.getData();
    };

//Navigasi halaman pertama
    $scope.firstPage = function () {
        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        $scope.main.page = 1;

        $scope.getData()
    };

//refresh data
    $scope.refreshData = function () {
        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        //panggil ajax data
        $scope.getData();
    };

//Cari Data
    $scope.cari = function () {
        //Close Alert
        $scope.closeMe();

        //Disable All Controller
        $scope.disConUmum(true);
        $scope.disConAksi(true);

        $scope.main.page = 1;
        $scope.getData();
    };

    //Close alert
    $scope.closeMe = function () {
        $scope.formHidden.alert = 'hidden';
    };


    $scope.verifikasi = function (id) {
        $scope.closeMe();
        var modalInstance = $modal.open({
            templateUrl: 'modVerifikasi.html',
            controller: 'modVerifikasiDPAPendapatan',
            size: '',
            backdrop: 'static',
            resolve: {
                id: function () {
                    return id;
                },
                token: function () {
                    return $scope.csrf;
                }
            }
        });

        modalInstance.result.then(function (hasil) {
            $scope.alertset = {
                class: hasil.class,
                msg: hasil.msg
            };

            $scope.formHidden = {
                alert: '',
                list: '',
                add: 'hidden',
                edit: 'hidden'
            };
            $scope.setDelay();
            $scope.sup();
            $scope.refreshData();
        }, function () {
            //$log.info('Modal dismissed at: ' + new Date());
        });
    };
}]);