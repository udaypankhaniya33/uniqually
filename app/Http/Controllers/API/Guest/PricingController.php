<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\API\BaseController;
use App\Package;
use App\PackageAddonAssociation;
use App\PackageCategory;
use Illuminate\Http\Request;

class PricingController extends BaseController
{

    /**
     * Subscribe an email
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $packageCategories = PackageCategory::all();
        $activeCategory = $packageCategories[0];

        if(request()->has('catId')){
            $activeCategory = PackageCategory::find((int)request('catId'));
            if($activeCategory === null) $activeCategory = $packageCategories[0];
        }

        $packagesOfActiveCat = Package::where('package_category_id', $activeCategory->id)
            ->with('attributes')
            ->with('questionAnswers')
            ->get();

        foreach ($packagesOfActiveCat as $index => $package) {
            $packageAddons = PackageAddonAssociation::where('package_id', $package->id)
                ->join('package_addons', 'package_addon_associations.package_addon_id','package_addons.id')
                ->get();
            $packagesOfActiveCat[$index]['addons'] = $packageAddons;
        }

        return $this->sendResponse([
            'packageCategories' => $packageCategories,
            'activeCategoryPackages' => $packagesOfActiveCat,
            'activeCategory' => $activeCategory
        ],
            'Successfully retrieved all pricing details');

    }

}
