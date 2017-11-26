<?php

namespace Botble\ACL\Http\Controllers;

use Assets;
use Botble\ACL\Repositories\Interfaces\FeatureInterface;
use Botble\ACL\Repositories\Interfaces\PermissionInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class FeatureController extends BaseController
{
    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * @var PermissionInterface
     */
    protected $permissionRepository;

    /**
     * FeatureController constructor.
     * @param FeatureInterface $featureRepository
     * @param PermissionInterface $permissionRepository
     */
    public function __construct(FeatureInterface $featureRepository, PermissionInterface $permissionRepository)
    {
        $this->featureRepository = $featureRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * List All available Features
     *
     * @return mixed List All available Features
     * @internal param null
     * @author Sang Nguyen
     */
    public function getList()
    {

        page_title()->setTitle(trans('acl::feature.edit'));

        Assets::addStylesheets(['jquery-ui']);
        Assets::addJavascript(['jquery-ui', 'jqueryTree']);
        Assets::addAppModule(['feature']);

        $features = $this->permissionRepository->getVisibleFeatures(['id', 'name', 'parent_flag']);

        // Make a key value pair
        $featuresArray = [];
        foreach ($features as $featureElement) {
            $featuresArray[$featureElement->id] = $featureElement;
        }

        $sortedFeatures = $featuresArray;
        sort($sortedFeatures);
        $featuresWithChildren[0] = $this->getChildren(0, $sortedFeatures);

        foreach ($featuresArray as $flagDetails) {
            $childrenReturned = $this->getChildren($flagDetails->id, $featuresArray);
            if (count($childrenReturned) > 0) {
                $featuresWithChildren[$flagDetails->id] = $childrenReturned;
            }
        }

        $featuresEnabled = $this->featureRepository->pluck('feature_id');


        return view('acl::features.features-edit')
            ->with('active', $featuresEnabled)
            ->with('featuresWithChildren', $featuresWithChildren)
            ->with('features', $featuresArray);

    }

    /**
     * Returns children of a selected system.feature.
     *
     * @param $parentId
     * @param $featuresArray
     * @return array
     * @author Sang Nguyen
     */
    private function getChildren($parentId, $featuresArray)
    {
        $newFeatureArray = [];
        foreach ($featuresArray as $featureDetails) {
            if ($featureDetails->parent_flag == $parentId) {
                $newFeatureArray[] = $featureDetails->id;

            }
        }
        return $newFeatureArray;
    }

    /**
     * Edit Function (Don't remove it, it will prevent the error: method not allow exception)
     * @author Sang Nguyen
     */
    public function getEdit()
    {

    }

    /**
     * Store the feature details.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit(Request $request)
    {
        $this->featureRepository->getModel()->truncate();

        $features = $request->input('features');

        if (is_array($features) && count($features) > 0) {
            foreach ($features as $feature) {
                $this->featureRepository->firstOrCreate([
                    'feature_id' => $feature,
                ]);
            }
        }

        return redirect()->route('system.feature.list')
            ->with('success_msg', trans('acl::feature.update_success'));

    }

    /**
     * Returns enabled features
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function getFeatures()
    {
        return $this->featureRepository->all();
    }
}
