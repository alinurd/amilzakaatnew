<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\MuzakkiDataTable;
use App\Exports\Report;
use App\Models\Muzakki;
use App\Helpers\AuthHelper;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use App\Models\Kategori;
use App\Models\MuzakkiHeader;
use App\Models\TransHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class MuzakkiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MuzakkiDataTable $dataTable)
    {
        $pageTitle = trans('global-message.list_form_title', ['form' => trans('Muzakki')]);
        $auth_user = AuthHelper::authSession();
        $assets = ['data-table'];
        $headerAction = '<a href="' . route('muzakki.create') . '" class="btn btn-sm btn-primary" role="button">Add Muzakki</a>';
        return $dataTable->render('global.datatable', compact('pageTitle', 'auth_user', 'assets', 'headerAction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agt = User::where("user_type", "pemberi")->where("status", "active")->get()->pluck('nama_lengkap', 'id');
        $ktg = Kategori::pluck('nama_kategori', 'id');

        return view('muzakki.form', compact('agt', 'ktg'));
    }


    public function editmuzzaki($code)
    {
        $agt = User::where("user_type", "pemberi")->where("status", "active")->get()->pluck('nama_lengkap', 'id');
        $ktg = Kategori::pluck('nama_kategori', 'id');

        $old['detail'] = Muzakki::where('code', $code)->with('user', 'kategori')->get();
        $old['header'] = MuzakkiHeader::where('code', $code)->with('user')->get();

        return view('muzakki.formedit', compact('agt', 'ktg','old'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'dibayarkan' => 'required',
            'user' => 'required|array',
            'user.*' => 'exists:users,id',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori,id',
            'type' => 'required|array',
            'satuan' => 'required|array',
            'jumlah' => 'required|array',

        ]);

        $lastId = MuzakkiHeader::orderByDesc('id')->first();
        $x = $lastId ? $lastId->id : 0;

        $MuzakkiHeader = MuzakkiHeader::create([
            'user_id' => $validatedData['dibayarkan'],
            'code' => $this->generateCodeById("MZK", $x + 1),
        ]);
        foreach ($validatedData['user'] as $key => $user) {
            $muzakki = Muzakki::create([
                'code' => $MuzakkiHeader->code,
                'user_id' => $user,
                'jumlah_bayar' => $validatedData['jumlah'][$key],
                'jumlah_jiwa' => $request['jumlah_jiwa'][$key],
                'kategori_id' => $validatedData['kategori'][$key],
                'type' => $validatedData['type'][$key],
                'satuan' => $validatedData['satuan'][$key],
            ]);

            $useHis = User::where('id', $user)->first();
            $kategHis = kategori::where('id', $validatedData['kategori'][$key])->first();
            $history = new TransHistory();
            $history->muzakki_id = $muzakki->id;
            $history->code = $MuzakkiHeader->code;
            $history->method = "Create";
            $history->user = Auth::user()->nama_lengkap;
            $history->changes = json_encode([
                'code_hst' => $this->generateCodeById("HST", $x + 1),
                'user_id' => $useHis->nama_lengkap, // Nilai sebelumnya tidak ada (baru saja dibuat)
                'jumlah_bayar' => $validatedData['jumlah'][$key],
                'jumlah_jiwa' => $request['jumlah_jiwa'][$key],
                'kategori_id' => $kategHis->nama_kategori,
                'type' => $validatedData['type'][$key],
                'satuan' => $validatedData['satuan'][$key],
            ]);
            $history->save();
        
            $x++; // Perbarui nilai x untuk kode berikutnya
        }
        
        $dibayarkan = User::where('id', $validatedData['dibayarkan'])->first();
        //  $no = '6289528518495'; 
        $no = $dibayarkan->nomor_telp;  
        $msg = "Alhamdulillah, telah diterima penunaian zis/fidyah dari Bapak/ibu: " . $dibayarkan->nama_lengkap . ".\n";
        $msg .= "No. Invoice: #" . $MuzakkiHeader->code . "\n\n\n ";
        $msg .= "Lihat detail: https://zis-alhasanah.com/showinvoice/" . $MuzakkiHeader->code;
        $this->cetakinvoice($MuzakkiHeader->code);

        $this->sendMassage1($no, $msg, $MuzakkiHeader->code);
        //  $this->sendMassage($no,$msg);
        //  $this->sendWa($no,$n);

        $key = 'b42be3006183b810feb31c0cc4162822-997e6839-9163-4293-b012-8e9834e6264f';
        $base_url = 'qymz4m.api.infobip.com';
        return redirect()->route('invoice', ['code' => $MuzakkiHeader->code])->withSuccess(__('Pembayaran berhasil dan  invoice telah terkirim kepada ' . $dibayarkan->nama_lengkap));
    }

    public function update(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'dibayarkan' => 'required',
            'user' => 'required|array',
            'user' => 'required|array',
            'user.*' => 'exists:users,id',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori,id',
            'type' => 'required|array',
            'satuan' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        $lastId = MuzakkiHeader::orderByDesc('id')->first();
        $x = $lastId ? $lastId->id : 0;

        $MuzakkiHeader = MuzakkiHeader::where('code', $request->code)
        ->update([
            'user_id' => $validatedData['dibayarkan'],
        ]);
    

     
        foreach ($request->id as $key => $id) {
            $muzakki = Muzakki::find($id);
            $oldValues = $muzakki->getOriginal(); // Get the original values before update
            $oldUser = User::where('id', $oldValues['user_id'])->first();
            $oldUserafter = User::where('id', $muzakki->user_id)->first();

            // Update the Muzakki record
            $muzakki->user_id = $validatedData['user'][$key];
            $muzakki->jumlah_bayar = $validatedData['jumlah'][$key];
            $muzakki->jumlah_jiwa = $request['jumlah_jiwa'][$key];
            $muzakki->kategori_id = $validatedData['kategori'][$key];
            $muzakki->type = $validatedData['type'][$key];
            $muzakki->satuan = $validatedData['satuan'][$key];
            $muzakki->save();
            $history = new TransHistory();
            $history->muzakki_id = $muzakki->id;
            $history->code =$request->code;
            $history->method ="update";
            $history->user =Auth::user()->nama_lengkap;
             $history->changes = json_encode([
                 'code_hst' =>$this->generateCodeById("HST", $x + 1),
                'user_id' => [
                    'before' => $oldUser->nama_lengkap,
                    'after' => $oldUserafter->nama_lengkap,
                 ],
                'jumlah_bayar' => [
                    'before' => $oldValues['jumlah_bayar'],
                    'after' => $muzakki->jumlah_bayar,
                ],
                'jumlah_jiwa' => [
                    'before' => $oldValues['jumlah_jiwa'],
                    'after' => $muzakki->jumlah_jiwa,
                ],
                'kategori_id' => [
                    'before' => $oldValues['kategori_id'],
                    'after' => $muzakki->kategori_id,
                ],
                'type' => [
                    'before' => $oldValues['type'],
                    'after' => $muzakki->type,
                ],
                'satuan' => [
                    'before' => $oldValues['satuan'],
                    'after' => $muzakki->satuan,
                ],
            ]);
            $history->save();
        }
        
    $no = '62895620014242';
        $msg = "Perubahan data muzzaki dilakukan oleh " . Auth::user()->nama_lengkap . ".\n";

        $msg .= "No. Invoice: #" . $request->code . "\n\n\n ";
        $msg .= "Lihat detail: https://zis-alhasanah.com/history/showdataupdate/" . $request->code;
 
        $this->sendMassage2($no, $msg, $request->code);

        
        return redirect()->route('invoice', ['code' => $request->code])->withSuccess(__('Pembayaran berhasil diupdate ' ));
    }

    public function invoice($code)
    {
        $data['detail'] = Muzakki::where('code', $code)->with('user', 'kategori')->get();
        $data['header'] = MuzakkiHeader::where('code', $code)->with('user')->get();
        return view('muzakki.print', compact('data'));
        // return view('invoice', compact('data'));
    }
    public function cetakinvoices($code)
    {
        $detail = Muzakki::where('code', $code)->with('user', 'kategori')->get();
        $header = MuzakkiHeader::where('code', $code)->with('user')->get();
        // return view('muzakki.print', compact('data'));
        return view('invoice', compact('header', 'detail'));
    }

    public function cetakinvoice($code)
    {
        $detail = Muzakki::where('code', $code)->with('user', 'kategori')->get();
        $header = MuzakkiHeader::where('code', $code)->with('user')->get();

        // Render view to PDF
        $pdf = PDF::loadView('invoice', compact('header', 'detail'));

        // // Save PDF to public folder
        $pdf->save(public_path('invoice/invoice_' . $code . '.pdf'));

        // Return view or response
        // return view('invoice', compact('header','detail'));
        return $pdf->stream('invoice_' . $code . '.pdf');
    }


    public function muzakkiCreate()
    {
        $view = view('muzakki.form-user')->render();
        return response()->json(['data' =>  $view, 'status' => true]);
    }
    public function muzakkiUserStore(Request $request)
    {
        $request['user_type'] = "pemberi";

        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nomor_telp' => 'nullable|string',
            'alamat' => 'required|string',
            'user_type' => 'required|in:admin,pemberi,penerima',
        ]);

        $user = User::create([
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'email' => uniqid() . '@example.com', // Kolom email harus unik, kita buat random untuk sementara
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'nomor_telp' => $validatedData['nomor_telp'],
            'alamat' => $validatedData['alamat'],
            'user_type' => $validatedData['user_type'],
            'status' => 'active', // Mengisi status default
            'role' => null, // Kolom role bisa diisi dengan null sesuai permintaan
        ]);

        // $agt = User::where("user_type", "pemberi")->where("status", "active")->get()->pluck('nama_lengkap', 'id');
        // // $agt = Role::where('status',1)->get()->pluck('title', 'id');

        // return view('muzakki.form', compact('agt'));
    }

    public function destroy($code)
    {
        // dd($id);
        $kategori = Muzakki::where('code', $code);
        $status = 'errors';
        $message = __('global-message.delete_form', ['form' => __('muzakki')]);

        if ($kategori != '') {
            $kategori->delete();
            $status = 'success';
            $message = __('global-message.delete_form', ['form' => __('muzakki')]);
        }

        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message, 'datatable_reload' => 'dataTable_wrapper']);
        }

        return redirect()->back()->with($status, $message);
    }
}
