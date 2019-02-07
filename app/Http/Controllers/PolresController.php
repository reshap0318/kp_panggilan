<?php

namespace App\Http\Controllers;

use App\polres;
use DB;
use App\rekapPanggilan as panggilan;
use App\Charts\Echarts;
use Illuminate\Http\Request;

class PolresController extends Controller
{
  public function index()
  {
      try {
          $polress = polres::all();
          return view('backend.polres.index',compact('polress'));
      } catch (\Exception $e) {
          toast()->error($e, 'Eror');
          toast()->error('Terjadi Eror Saat Meng-Load Data, Silakan Ulang Login kembali', 'Gagal Load Data');
          return redirect()->back();
      }
  }


  public function create()
  {
      return view('backend.polres.create');
  }

  public function store(Request $request)
  {
      $request->validate([
        'nama' => 'required',
        'email' => 'required',
        'alamat' => 'required'
      ]);

      try {

        $polres = new polres;
        $polres->nama = $request->nama;
        $polres->alamat = $request->alamat;
        $polres->email = $request->email;
        $polres->save();
          toast()->success('Berhasil Me-nyimpan Data Polres', 'Berhasil');
          return redirect()->route('polres.index');

      } catch (\Exception $e) {
        toast()->error($e, 'Eror');
        toast()->error('Terjadi Eror Saat Meng-Nyimpan Data', 'Gagal Load Data');
        return redirect()->back();
      }
  }

  public function show($id)
  {
      $panggilanselesai = DB::SELECT(DB::RAW('select polres.nama, sum(rekap_panggilans.panggilan_terselesaikan) as terselesaikan from rekap_panggilans JOIN polres on rekap_panggilans.polres_id = polres.id GROUP by polres.nama order by terselesaikan asc limit 1'));
      $panggilanprank = DB::SELECT(DB::RAW('select polres.nama, sum(rekap_panggilans.panggilan_prank) as prank from rekap_panggilans JOIN polres on rekap_panggilans.polres_id = polres.id GROUP by polres.nama order by prank desc limit 1'));
      $panggilantidakmax = DB::SELECT(DB::RAW('select polres.nama, sum(rekap_panggilans.panggilan_tidak_terjawab) as tidak_terjawab from rekap_panggilans JOIN polres on rekap_panggilans.polres_id = polres.id GROUP by polres.nama order by tidak_terjawab desc limit 1'));
      $banyakmax = DB::SELECT(DB::RAW('select polres.nama, sum(rekap_panggilans.panggilan_prank+rekap_panggilans.panggilan_tidak_terjawab+rekap_panggilans.panggilan_terselesaikan) as total from rekap_panggilans JOIN polres on rekap_panggilans.polres_id = polres.id GROUP by polres.nama order by total desc limit 1'));
      $nilais = DB::SELECT(DB::RAW("select polres.nama, sum(rekap_panggilans.panggilan_terselesaikan+rekap_panggilans.panggilan_prank)/sum(rekap_panggilans.panggilan_terselesaikan+rekap_panggilans.panggilan_prank+rekap_panggilans.panggilan_tidak_terjawab)*100 as nilai from polres join rekap_panggilans on polres.id = rekap_panggilans.polres_id GROUP BY polres.nama ORDER BY nilai desc limit 6"));

      $polress = panggilan::select(DB::RAW('polres.nama, sum(rekap_panggilans.panggilan_terselesaikan) as terjawab, SUM(rekap_panggilans.panggilan_tidak_terjawab) as tidak_terjawab,sum(rekap_panggilans.panggilan_prank) as prank, sum(rekap_panggilans.panggilan_tidak_terjawab+rekap_panggilans.panggilan_prank+rekap_panggilans.panggilan_terselesaikan) as total'))
      ->leftjoin('polres','rekap_panggilans.polres_id','=','polres.id')
      ->whereday('rekap_panggilans.created_at',now()->day)
      ->groupby('polres.nama')->get();

      $chart = new Echarts;
      $label = [];
      foreach ($polress as $polres) {
        Array_push($label,$polres->nama);
      }

      $polres = polres::find($id);


      $chart->labels($label)->load(url('datadash'));
      // dd([$panggilanprank,$panggilanselesai,$panggilantidakmax,$banyakmax]);
      return view('backend.polres.show', compact('chart','panggilanselesai','panggilanprank','panggilantidakmax','banyakmax','nilais','polres'));
  }

  public function edit($id)
  {
    try {
      $polres = polres::find($id);
      return view('backend.polres.edit',compact('polres'));
    } catch (\Exception $e) {
        toast()->error($e, 'Eror');
        toast()->error('Terjadi Eror Saat Meng-Load Data, Silakan Ulang Login kembali', 'Gagal Load Data');
        return redirect()->back();
    }
  }

  public function update(Request $request, $id)
  {

    $request->validate([
      'nama' => 'required',
      'alamat' => 'required',
      'email' => 'required'
    ]);

    try {
      $polres = polres::find($id);
      $polres->nama = $request->nama;
      $polres->email = $request->email;
      $polres->alamat = $request->alamat;
      $polres->update();
      toast()->success('Berhasil Update Data Polres', 'Berhasil');
      return redirect()->route('polres.index');
    } catch (\Exception $e) {
        toast()->error($e, 'Eror');
        toast()->error('Terjadi Eror Saat Meng-update Data', 'Gagal Load Data');
        return redirect()->back();

    }
  }

  public function destroy($id)
  {
      try {
        $polres = polres::find($id);
        $polres->delete();
        toast()->success('Hapus Data Polres', 'Berhasil');
        return redirect()->route('polres.index');
      } catch (\Exception $e) {
        toast()->error($e, 'Eror');
        toast()->error('Terjadi Eror Saat Meng-Hapus Data', 'Gagal Load Data');
        return redirect()->back();
      }
  }

  public function data(Request $request, $id)
  {
    $data = explode(",",$request->data);
    $mulai = date('Ymd', strtotime($data[0]));
    $akhir = date('Ymd', strtotime($data[1]));
    $pembagi = DB::SELECT(DB::RAW('select datediff("'.date('Y-m-d', strtotime($data[1])).'","'.date('Y-m-d', strtotime($data[0])).'")+1 as bagi'));

    $panggilanselesai = DB::SELECT(DB::RAW("Select tanggal, panggilan_terselesaikan from rekap_panggilans where DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' AND rekap_panggilans.polres_id=$id order by panggilan_terselesaikan desc limit 1"));
    $panggilanprank = DB::SELECT(DB::RAW("Select tanggal, panggilan_prank from rekap_panggilans where DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' AND rekap_panggilans.polres_id=$id order by panggilan_prank desc limit 1"));
    $panggilantidakmax = DB::SELECT(DB::RAW("Select tanggal, panggilan_tidak_terjawab from rekap_panggilans where DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' AND rekap_panggilans.polres_id=$id order by panggilan_tidak_terjawab desc limit 1"));
    $panggilanmax = DB::SELECT(DB::RAW("Select tanggal, panggilan_terselesaikan+panggilan_prank+panggilan_tidak_terjawab as total from rekap_panggilans where DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' AND rekap_panggilans.polres_id=$id order by total desc limit 1"));
    $nilais = DB::SELECT(DB::RAW("select case when date_format(tanggal,'%w') = 0 THEN 'MINGGU' when date_format(tanggal,'%w') = 1 THEN 'SENIN' when date_format(tanggal,'%w') = 2 THEN 'SELASA' when date_format(tanggal,'%w') = 3 THEN 'RABU' when date_format(tanggal,'%w') = 4 THEN 'KAMIS' when date_format(tanggal,'%w') = 5 THEN 'JUMAT' when date_format(tanggal,'%w') = 6 THEN 'Sabtu' end as 'hari',sum(panggilan_terselesaikan+panggilan_prank+panggilan_tidak_terjawab) as nilai from `rekap_panggilans` where DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' AND rekap_panggilans.polres_id=$id group by `hari` order by date_format(tanggal,'%w') asc"));
    $panggilans = panggilan::select('*')->whereRAW("DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' AND rekap_panggilans.polres_id=$id")->get();
    // dd([$data,$mulai,$akhir,$pembagi, $panggilanselesai, $panggilanprank, $panggilantidakmax, $panggilanmax, $nilais, $panggilans]);

    $chart = new Echarts;
    $label = [];
    $masuk = [];
    $jawab = [];
    $tjawab = [];
    $prank = [];

    foreach ($panggilans as $panggilan) {
      Array_push($label,$panggilan->tanggal);
      Array_push($masuk,$panggilan->panggilan_prank+$panggilan->panggilan_terselesaikan+$panggilan->panggilan_tidak_terjawab);
      Array_push($jawab,$panggilan->panggilan_terselesaikan);
      Array_push($tjawab,$panggilan->panggilan_tidak_terjawab);
      Array_push($prank,$panggilan->panggilan_prank);
    }

    $chart->labels($label);


    $chart->dataset('Panggilan Masuk', 'bar', $masuk)->color('#006400');
    $chart->dataset('Panggilan Terselesaikan', 'bar', $jawab)->color('#00008B');
    $chart->dataset('Panggilan Prank', 'bar', $prank)->color('#a54d00');
    $chart->dataset('Panggilan Tidak Terjawab', 'bar', $tjawab)->color('#b20000');
    $hasil = [];
    // dd($chart->datasets[0]);
    $dat = [];
    foreach($chart->datasets as $data){
         foreach ($data->options as $key) {
             $color = $key;
         }
        array_push($dat,array("data"=>$data->values,"name"=>$data->name,"type"=>$data->type,"color"=>$color));
    }
    $hasil = ['angka'=>$dat,'label'=>$label,'pselesai'=>$panggilanselesai,'ptotal'=>$panggilanmax,'ptidak'=>$panggilantidakmax,'pprank'=>$panggilanprank,'nilais'=>$nilais];
    return $hasil;

  }

  public function cetak(Request $request)
  {
    // try {
      $waktu = str_replace(" - ", ",", $request->waktu);
      $waktu = explode(",",$waktu);
      $mulai = date('Ymd', strtotime($waktu[0]));
      $akhir = date('Ymd', strtotime($waktu[1]));

      $panggilans = polres::Select(DB::RAW('sum(panggilan_terselesaikan) as panggilan_terselesaikan, sum(panggilan_prank) as panggilan_prank, sum(panggilan_tidak_terjawab) as panggilan_tidak_terjawab, polres.nama'))
      ->leftjoin('rekap_panggilans','polres.id','=','rekap_panggilans.polres_id');

      $panggilans = $panggilans->whereRAW("DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir' or polres.id not in (select polres_id rekap_panggilans where DATE_FORMAT(rekap_panggilans.tanggal, '%Y%m%d') BETWEEN '$mulai' AND '$akhir')");

      if($request->kategori){
        $panggilans = $panggilans->orderby($request->kategori,'desc');
      }

      $panggilans = $panggilans->groupby('polres.nama')->get();
      return view('backend.polres.cetak',compact('panggilans'));
    // } catch (\Exception $e) {
    //     toast()->error($e, 'Eror');
    //     toast()->error('Terjadi Eror Saat Meng-hapus Data', 'Gagal Load Data');
    //     return redirect()->route('panggilan.index');
    // }
  }
}
