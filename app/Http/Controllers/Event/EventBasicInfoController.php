<?php

namespace App\Http\Controllers\Event;

use App\Event\EventBasicInfo;
use App\Event\EventPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Event\EventInfoBasicResource;
use App\Http\Resources\Event\EventInfoBasicResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use MetaTag;
class EventBasicInfoController extends Controller
{
    protected $path = '../public/storage/Event/Banner/';
    protected $path_photo = '../public/storage/Event/Photos/';
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check the entry Fee
        if($request->entry_fee == null){
            $request->entry_fee = 0;
        }
        // Image upload script in php
        if ($request->banner) {
            $name = time() . '.'
                . explode('/', explode(
                    ':',
                    substr(
                        $request->banner,
                        0,
                        strpos($request->banner, ';')
                    )
                )[1])[1];
            // Card
            $card = time() . '-card.'
                . explode('/', explode(
                    ':',
                    substr(
                        $request->banner,
                        0,
                        strpos($request->banner, ';')
                    )
                )[1])[1];
            // Thumb
            $thumb = time() . '-thumb.'
                . explode('/', explode(
                    ':',
                    substr(
                        $request->banner,
                        0,
                        strpos($request->banner, ';')
                    )
                )[1])[1];
            // Original
            \Image::make($request->banner)->save($this->path. $name);
            $Original = \Image::make($request->banner)->save($this->path. $name);
            // Card 500 X
            $Original->resize(420, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            \Image::make($Original)->save($this->path. $card);
            // Thumbnail 240 X 
            $Original->resize(220, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            \Image::make($Original)->save($this->path. $thumb);

        }
        // return $name;
        $job = EventBasicInfo::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'banner' => $name,
            'card' => $card,
            'thumb' => $thumb,
            'rate' =>0,
            'email' => $request->email,
            'entry_free'=>$request->entry_free,
            'location' => $request->location,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'category' => $request->category,
            'address' => $request->address,
            'mobile_no' => $request->mobile_no,
            'entry_fee' => $request->entry_fee,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'status' => true,
            'featured_ad' => false,
            'sidebar_ad' => false,
            'home_ad' => false,
            'facebook' => $request->facebook,
            'website' => $request->website,
            'instagram' => $request->instagram,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return new EventInfoBasicResource(EventBasicInfo::find($id));
        $event = EventBasicInfo::find($id);
        return $event->toArray($event);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->end_date;
        // Resetting the end date if null
        if($request->end_date === null){
            $request->end_date = $request->start_date;
        }
        // Update
        $event = EventBasicInfo::find($id);
        $event->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $event = EventBasicInfo::find($id);
        $unlink = $this->path. $event->banner;
        $unlink_card= $this->path. $event->card;
        $unlink_thumb = $this->path. $event->thumb;
        unlink($unlink);
        unlink($unlink_card);
        unlink($unlink_thumb);
        // Photos Delete
        $photos = EventPhoto::where('event_basic_info_id', $id)->get();
        for ($i = 0; $i < $photos->count(); $i++) {
            $photos[$i]->delete();
            $photos_detach = $this->path_photo . $photos[$i]->path;
            $photos_thumb = $this->path_photo . $photos[$i]->thumb;
            unlink($photos_detach);
            unlink($photos_thumb);
        }
        $event->delete();
        $event->event_interests()->delete();
        $event->event_reviews()->delete();
        $event->event_photos()->delete();
    }

    /**
     *  Updating Star rating
     * Restaurant
     * Star Rate
     * 
     *  */
    public function update_rate(Request $request, $id)
    {
        $rate = EventBasicInfo::find($id);
        $rate->rate=$request->rate;
        $rate->update();
        // $rate->update($request->all());
    }
    /**
     * Customized API
     * Routes
     *  */
    // Event View page
    public function view(EventBasicInfo $eventBasicInfo, $id)
    {
        // Meta Description
        $meta = EventBasicInfo::find($id);
        if($meta->entry_fee ==0){
            $meta->entry_fee = 'Entry Free';
        }else{
            $meta->entry_fee = 'Rs: '.$meta->entry_fee;
        }; 
        // Date with Day of the week
        $start_date = date_create($meta->start_date);
        MetaTag::set('title', $meta->name. ' | '. date_format($start_date,"l\,jS F Y"));
        MetaTag::set('description', $meta->location. ' - ' . $meta->entry_fee);
        MetaTag::set('image', asset('storage/Event/Banner/'.$meta->banner));
        // Meta Description End
        return view('event.show', ['id' => EventBasicInfo::find($id)]);
    }
    // Dashboard Edit
    public function event_edit(EventBasicInfo $eventBasicInfo, $id)
    {
        // Meta Description
        $meta = EventBasicInfo::find($id);
        if($meta->entry_fee ==0){
            $meta->entry_fee = 'Entry Free';
        }else{
            $meta->entry_fee = 'Rs: '.$meta->entry_fee;
        };
        // Date with Day of the week
        $start_date = date_create($meta->start_date);
        MetaTag::set('title', $meta->name. ' | '. date_format($start_date,"l\,jS F Y"));
        MetaTag::set('description', $meta->location. ' - ' . $meta->entry_fee);
        MetaTag::set('image', asset('storage/Event/Banner/'.$meta->banner));
        if (Auth::user()->id === EventBasicInfo::find($id)->user_id) {
            return view('dashboard.event.edit', ['id' => EventBasicInfo::find($id)]);
        } else {
            $this->authorize('event_edit', $eventBasicInfo);

        }
    }
    // Display page
    public function display($id)
    {
        // return "hellow";
        return new EventInfoBasicResource(EventBasicInfo::find($id));
    }
    // User Job
    public function user_event()
    {
        $events = EventBasicInfo::where('user_id','=', Auth::user()->id)
        ->orderBy('created_at', 'desc')->get();
        return $events->toArray($events);
    }
    // Banner update
    public function banner_update(Request $request, $id)
    {
        // return $request;
        $name = '';
        // Image upload script in php
        if ($request->banner) {
            $name = time() . '.'
                . explode('/', explode(
                    ':',
                    substr(
                        $request->banner,
                        0,
                        strpos($request->banner, ';')
                    )
                )[1])[1];
            // Card
            $card = time() . '-card.'
                . explode('/', explode(
                    ':',
                    substr(
                        $request->banner,
                        0,
                        strpos($request->banner, ';')
                    )
                )[1])[1];
            // Thumb
            $thumb = time() . '-thumb.'
                . explode('/', explode(
                    ':',
                    substr(
                        $request->banner,
                        0,
                        strpos($request->banner, ';')
                    )
                )[1])[1];
                // Store Image
            \Image::make($request->banner)->save($this->path. $name);
            $Original = \Image::make($request->banner)->save($this->path. $name);
            // Card 500 X
            $Original->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            \Image::make($Original)->save($this->path. $card);
            // Thumbnail 240 X 
            $Original->resize(240, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            \Image::make($Original)->save($this->path. $thumb);
        }
        // upate
        $banner = EventBasicInfo::find($id);
        // Remove old photos
        $unlink = $this->path. $banner->banner;
        $unlink_card = $this->path. $banner->card;
        $unlink_thumb = $this->path. $banner->thumb;
        unlink($unlink);
        unlink($unlink_card);
        unlink($unlink_thumb);
        // Update fields
        $banner->update(['banner' => $name,'card'=>$card,'thumb'=>$thumb]);
    }
    // Update status
    public function status_update(Request $request, $id)
    {
        // return $id;
        $status = EventBasicInfo::find($id);
        // update
        $status->update($request->all());
    }
    public function all()
    {
        $events = EventInfoBasicResource::collection(EventBasicInfo::where('status', '=', true)
            ->where('end_date', '>=', date('Y-m-d'))
            ->inRandomOrder()
            ->orderBy('created_at', 'desc')->get());
        return $events->toArray($events);
    }
    public function featured_ad()
    {
        $events = EventInfoBasicResource::collection(EventBasicInfo::where('featured_ad', '=', true)
            ->where('end_date', '>=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->inRandomOrder()->get());
        return $events->toArray($events);
    }
    // Front
    public function home_ad()
    {
        $events = EventInfoBasicResource::collection(EventBasicInfo::where('home_ad', '=', true)
            ->where('end_date', '>=', date('Y-m-d'))
            ->inRandomOrder()
            ->orderBy('created_at', 'desc')->get());
        return $events->toArray($events);
    }
    // Sidebar
    public function sidebar_ad()
    {
        $events = EventInfoBasicResource::collection(EventBasicInfo::where('sidebar_ad', '=', true)
            ->where('end_date', '>=', date('Y-m-d'))
            ->inRandomOrder()
            ->orderBy('created_at', 'desc')->get());
        return $events->toArray($events);
    }
    // Entry Fee
    public function max_fee()
    {
        $max = EventBasicInfo::where('status', '=', true)->max('entry_fee');
        return $max;
    }
    public function  max_date(){
        $max = EventBasicInfo::where('status', '=', true)->max('end_date');
        return $max;


    }
    // Popup ad
    public function popup_ad()
    {
        $events = EventInfoBasicResource::collection(EventBasicInfo::where('popup_ad', '=', true)
            ->where('end_date', '>=', date('Y-m-d'))
            ->inRandomOrder()
            ->limit('1')
            ->orderBy('created_at', 'desc')->get());
        return $events->toArray($events);
    }
    // location
    public function location(){
        $location = EventBasicInfo::inRandomOrder()
        ->limit('1')
        ->get('location');
        return $location->toArray($location);
    }
    // sidebar location
    public function sidebar(Request $request,$location){
        $sales =  EventBasicInfo::where('location', 'like', "$location%")
        ->where('status', '=', true)
        ->where('end_date', '>=', date('Y-m-d'))
        ->inRandomOrder()
        ->orderBy('created_at', 'desc')->paginate('4');
        return $sales->toArray($sales);

    }
    // Search View
    public function search_engine(Request $request)
    {
        MetaTag::set('title',$request->location);

        return view('event.search', ['location' => $request->location]);
    }
    // Search Query
    public function search(Request $request)
    {
        MetaTag::set('title', $request->location);
        MetaTag::set('description', $request->location .'Tibetanbusiness.com' );
        MetaTag::set('image', asset('/img/tibetanbusiness.com'));
        // return $request;
        $min = (int)$request->fee_min;
        $max = (int)$request->fee_max;

        if($request->entry_free === 'true'){
            $status = true;
        }else{
            $status = false;
        }

        if (!$status) {
            return new EventInfoBasicResourceCollection(EventBasicInfo::where('name', 'like', "$request->name%")
                ->where('location', 'like', "$request->location%")
                ->where('category', 'like', "$request->category%")
                ->whereBetween('end_date', [$request->from, $request->to])
                ->whereBetween('entry_fee', [$min, $max])
            //     // ->Where('entry_free','=','')
                ->where('status', '=', true)->orderBy('created_at', 'desc')->paginate('10'));
        } else {
            return new EventInfoBasicResourceCollection(EventBasicInfo::where('name', 'like', "$request->name%")
                ->where('location', 'like', "$request->location%")
                ->where('category', 'like', "$request->category%")
                ->where('entry_free', '=', true)
                ->whereBetween('end_date', [$request->from, $request->to])
                ->where('status', '=', true)->orderBy('created_at', 'desc')->paginate('10'));
        }

    }
}
