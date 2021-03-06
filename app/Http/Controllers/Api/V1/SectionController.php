<?php

namespace App\Http\Controllers\Api\V1;

use App\Bridge;
use App\Events\BridgeCreated;
use App\Events\BridgeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\BridgeStoreRequest;
use App\Http\Requests\BridgeUpdateRequest;
use App\Http\Requests\CreateSectionRequest;
use App\Http\Requests\SectionDescriptionRequest;
use App\Http\Requests\SectionTitleRequest;
use App\Jobs\CreateSection;
use App\Jobs\DeleteBridge;
use App\Jobs\DeleteSection;
use App\Jobs\UpdateBridgeName;
use App\Jobs\CreateBridge;
use App\Section;
use App\SectionType;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
//    public function index()
//    {
//        $bridges = Bridge::all();
//        return response()->json([
//            'bridges' => $bridges
//        ]);
//    }
//
//    public function show($id)
//    {
//        try{
//            $bridge = Bridge::findOrFail($id);
//            return response()->json([
//                'bridge' => $bridge
//            ]);
//        }catch (ModelNotFoundException $e){
//            return response()->json([
//                'error' => 'Entry not found'
//            ]);
//        }
//
//    }

    public function store(CreateSectionRequest $request, $bridgeId)
    {

        try{
            $user = Auth::user();
            $bridge = Bridge::findOrFail($bridgeId);
            if($user->id !== $bridge->user_id)
                throw new ModelNotFoundException();

            (new CreateSection($bridge, SectionType::findOrFail($request->get('section_type'))))->handle();
            // event(new BridgeUpdated($bridge));
            $bridge = Bridge::with('sections', 'icons', 'icons.converted', 'images', 'images.converted', 'fonts', 'fonts.variant', 'fonts.variant.fontFamily', 'colors')->findOrFail($bridgeId);
            return response()->json([
                'bridge' => $bridge
            ]);
        }catch (\Exception $e){
            dd($e);
            return response()->json([
                'error' => 'Server error'
            ]);
        }

    }

    public function destroy($bridgeId, $sectionId)
    {

        try{

            $section = Section::findOrFail($sectionId);

            $user = Auth::user();
            $bridge = Bridge::findOrFail($section->bridge_id);
            if($user->id !== $bridge->user_id)
                throw new ModelNotFoundException();


            $section->delete();

            $bridge = Bridge::with('sections', 'icons', 'icons.converted', 'images', 'images.converted', 'fonts', 'fonts.variant', 'fonts.variant.fontFamily', 'colors')->findOrFail($bridgeId);
            try{
                event(new BridgeUpdated($bridge));
            }catch(\Exception $e){}
            return response()->json([
                'bridge' => $bridge
            ]);

        }catch (ModelNotFoundException $e){
            return response()->json([
                'error' => 'Entry not found'
            ]);
        }catch (\Exception $e){
            return response()->json([
                'error' => 'Server error'
            ]);
        }

    }

    public function updateTitle(SectionTitleRequest $request, $bridgeId, $sectionId)
    {
        try{
            $section = Section::findOrFail($sectionId);

            $user = Auth::user();
            $bridge = Bridge::findOrFail($section->bridge_id);
            if($user->id !== $bridge->user_id)
                throw new ModelNotFoundException();

            $section->title = $request->get('title');
            $section->save();

            $bridge = Bridge::with('sections')->findOrFail($bridgeId);
            $bridge = Bridge::with('sections', 'icons', 'icons.converted', 'images', 'images.converted', 'fonts', 'fonts.variant', 'fonts.variant.fontFamily', 'colors')->findOrFail($bridgeId);
            try{
                event(new BridgeUpdated($bridge));
            }catch(\Exception $e){}
            return response()->json([
                'bridge' => $bridge
            ]);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Entry not found'
            ]);
        }catch (\Exception $e) {

        }
    }

    public function updateDescription(SectionDescriptionRequest $request, $bridgeId, $sectionId)
    {
        try{
            $section = Section::findOrFail($sectionId);

            $user = Auth::user();
            $bridge = Bridge::findOrFail($section->bridge_id);
            if($user->id !== $bridge->user_id)
                throw new ModelNotFoundException();

            $section->description = $request->get('description');
            $section->save();

            $bridge = Bridge::with('sections')->findOrFail($bridgeId);
            $bridge = Bridge::with('sections', 'icons', 'icons.converted', 'images', 'images.converted', 'fonts', 'fonts.variant', 'fonts.variant.fontFamily', 'colors')->findOrFail($bridgeId);
            try{
                event(new BridgeUpdated($bridge));
            }catch(\Exception $e){}
            return response()->json([
                'bridge' => $bridge
            ]);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Entry not found'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error'
            ]);
        }
    }

    /**
     * TODO: Make a trait or something
     *
     * @param BridgeUpdateRequest $request
     * @param $id
     */
    public function updateOrder(BridgeUpdateRequest $request, $id) {
//        try{
//            $bridge = Bridge::findOrFail($id);
//            $bridge->name = $request->get('name');
//            $bridge->save();
//            event(new BridgeUpdated($bridge));
//            return response()->json([
//                'bridge' => $bridge
//            ]);
//        }catch (ModelNotFoundException $e){
//            dd($e);
//            return response()->json([
//                'error' => 'Entry not found'
//            ]);
//        }catch (\Exception $e){
//            dd($e);
//            return response()->json([
//                'error' => 'Server error'
//            ]);
//        }
    }
}
