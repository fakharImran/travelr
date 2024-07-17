<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use App\Models\Activity;
use App\Models\PriceAudit;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class PriceAuditController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();

        $timeSheet = $user->companyUser->timeSheets()->latest()->first();

        //for edit the timesheet if the last visit is not checkout
        if ($timeSheet) 
        {
            $records = $timeSheet->timeSheetRecords; //getting last timesheeet records
            foreach ($records as $key => $record) {
                if ($record->status == 'check-out') 
                {
                    return $this->sendError('already-checkout');       
                }  
            }
                $stores = $user->companyUser->company->stores;
                $categories = $user->companyUser->company->categories;
                $products = [];
                foreach ($categories as $category) {
                    $categoryProducts = $category->products->pluck('id', 'product_name')->toArray(); // Pluck product IDs
                    $products = array_merge($products, $categoryProducts); // Merge product IDs
                // return $this->sendResponse(['products'=>$products, 'categoryProducts'=>$categoryProducts], 'here are products of company named:');
        
                }
                $productsList = Product::whereIn('id', $products)->get();
                

                return $this->sendResponse(['productsList'=>$productsList , 'categories'=>$categories, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id], 'check-in');
        
            // }
        }

        // for create a new visit from frontend
        return $this->sendError('already-checkout');       



       
       
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id'=> 'required',
            'store_location_id'=>'required',
            'category_id'=>'required',
            'product_id'=>'required',
            'Product_SKU'=>'required',
            'product_store_price'=>'required',
            'tax_in_percentage'=>'required',
            'competitor_product_tax'=>'required',
            'competitor_product_name'=>'required',
            'competitor_product_price'=>'required',
            'notes'=>'required',
            
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_location= StoreLocation::where ('id', $request->store_location_id)->first();
        $store = $store_location->store;
        
        $user = Auth::user();
        $company_user_id=$user->companyUser->id;

        $priceAuditArr= ['store_location_id'=>$store_location->id,'store_id'=>$store->id, 'company_user_id'=>$company_user_id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'Product_SKU'=>$request->Product_SKU, 'product_store_price'=>$request->product_store_price, 'tax_in_percentage'=>$request->tax_in_percentage, 'competitor_product_name'=>$request->competitor_product_name, 'competitor_product_price'=>$request->competitor_product_price,'competitor_product_tax'=>$request->competitor_product_tax, 'notes'=>$request->notes];
        // return $this->sendResponse(['priceAuditArr'=>$priceAuditArr], 'checking:');
        $responseofQuery= PriceAudit::create($priceAuditArr);

       
        $activity= new Activity;
        $activity->company_user_id= $company_user_id;
        $activity->activity_description= 'You did Price Audit Check on '. $product->product_name;
        $activity->activity_type= 'Price Audit';
        $activity->activity_detail= json_encode($priceAuditArr);
        // return $this->sendResponse(['activity'=>$activity], 'activity to be stored successfully.');
        $activity->save();

        return $this->sendResponse(['responseofQuery'=>$responseofQuery], 'here is an priceAuditArr be stored:');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
