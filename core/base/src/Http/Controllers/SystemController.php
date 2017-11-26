<?php

namespace Botble\Base\Http\Controllers;

use Artisan;
use Assets;
use Botble\Base\Repositories\Interfaces\PluginInterface;
use Botble\Base\Supports\SystemManagement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class SystemController extends Controller
{

    /**
     * @var PluginInterface
     */
    protected $pluginRepository;

    /**
     * SystemController constructor.
     * @param PluginInterface $pluginRepository
     */
    public function __construct(PluginInterface $pluginRepository)
    {
        $this->pluginRepository = $pluginRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getInfo()
    {
        page_title()->setTitle(trans('bases::system.info.title'));

        $composerArray = SystemManagement::getComposerArray();
        $packages = SystemManagement::getPackagesAndDependencies($composerArray['require']);
        $systemEnv = SystemManagement::getSystemEnv();
        $serverEnv = SystemManagement::getServerEnv();
        $serverExtras = SystemManagement::getServerExtras();
        $systemExtras = SystemManagement::getSystemExtras();
        $extraStats = SystemManagement::getExtraStats();
        return view('bases::system.info', compact('packages', 'systemEnv', 'serverEnv', 'extraStats', 'serverExtras', 'systemExtras'));
    }

    /**
     * Show all plugins in system
     *
     * @author Sang Nguyen
     */
    public function getListPlugins()
    {
        page_title()->setTitle(trans('bases::system.plugins'));

        Assets::addAppModule(['plugin']);

        /**
         * @var Collection $all
         */
        $all = app(PluginInterface::class)->allBy(['status' => 1]);
        $plugins = scan_folder(config('cms.plugin_path'));
        if (!empty($plugins)) {
            $installed = !empty($all) ? $all->pluck('provider')->all() : [];
            foreach ($plugins as $plugin) {
                $content = get_file_data(config('cms.plugin_path') . DIRECTORY_SEPARATOR . $plugin . '/plugin.json');
                if (!empty($content)) {
                    if (!in_array($content['provider'], $installed)) {
                        $content['status'] = 0;
                        $content['path'] = $plugin;
                        $list[] = (object) $content;
                    } else {
                        foreach ($all as $item) {
                            if ($item->provider == $content['provider']) {
                                $item->path = $plugin;
                                $list[] = $item;
                                break;
                            }
                        }
                    }
                }
            }
        }
        return view('bases::plugins.list', compact('list'));
    }

    /**
     * Activate or Deactivate plugin
     *
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function getChangePluginStatus(Request $request)
    {
        $alias = $request->input('alias');

        $content = get_file_data(config('cms.plugin_path') . DIRECTORY_SEPARATOR . $alias . '/plugin.json');
        if (!empty($content)) {
            $namespace = str_replace('\\Plugin', '\\', $content['plugin']);
            $composer = get_file_data(base_path() . '/composer.json');

            if (!empty($composer) && !isset($composer['autoload']['psr-4'][$namespace])) {
                $composer['autoload']['psr-4'][$namespace] = 'plugins/' . strtolower($alias) . '/src';
                save_file_data(base_path() . '/composer.json', $composer);
                Artisan::call('dump-autoload');
            }

            try {
                $plugin = $this->pluginRepository->getFirstBy(['provider' => $content['provider']]);
                if (empty($plugin)) {
                    call_user_func([$content['plugin'], 'activate']);
                    $plugin = $this->pluginRepository->getModel();
                    $plugin->fill($content);
                    $plugin->alias = $alias;
                    $plugin->status = 1;
                } else {
                    $plugin->alias = $alias;

                    if ($plugin->status != 1) {
                        call_user_func([$content['plugin'], 'activate']);
                        $plugin->status = 1;
                    } else {
                        call_user_func([$content['plugin'], 'deactivate']);
                        $plugin->status = 0;
                    }
                }

                $this->pluginRepository->createOrUpdate($plugin);

                Artisan::call('cache:clear');
                return [
                    'error' => false,
                    'message' => trans('bases::system.update_plugin_status_success'),
                ];
            } catch (Exception $ex) {
                info($ex->getMessage());
                return [
                    'error' => true,
                    'message' => $ex->getMessage(),
                ];
            }
        }
        return [
            'error' => true,
            'message' => trans('bases::system.invalid_plugin'),
        ];
    }
}
