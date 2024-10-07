


//
//
//namespace App\Http\Controllers\Common;
//
//use App\Http\Requests;
//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;
//
//
//class StorageController extends Controller
//{
//
//    public function save(Request $request)
//    {
//        $file = $request->file('file');
//        $rules     = array('file' => 'required|mimes:jpeg,pdf'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
//        $validator = Validator::make(array('file' => $file), $rules);
//        $uploadcount = 0;
//
//        foreach ($files as $file) {
//            if ($validator->passes()) {
//                $newName        = 'prueba';
//                $extension      = $file->getClientOriginalExtension();
//                $upload_success = Storage::disk('local')->put($newName . '.' . $extension, \File::get($file));
//                $uploadcount++;
//            }
//        }
//
////        $name = $file->getClientOriginalName();
////        $extension = $file->getClientOriginalExtension();
////        $newName = 'test';
////
////        Storage::disk('local')->put($newName.'.'.$extension, \File::get($file));
//        return  "Archivo guardado";
//    }
//
//}
