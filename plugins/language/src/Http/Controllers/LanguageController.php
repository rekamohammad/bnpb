<?php

namespace Botble\Language\Http\Controllers;

use Assets;
use Botble\ACL\Models\UserMeta;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Language;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Botble\Language\Http\Requests\LanguageRequest;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Exception;
use Illuminate\Http\Request;
use Setting;

class LanguageController extends BaseController
{
    /**
     * @var LanguageInterface
     */
    protected $languageRepository;

    /**
     * @var LanguageMetaInterface
     */
    protected $LanguageMetaRepository;


    /**
     * LanguageController constructor.
     * @param LanguageInterface $languageRepository
     * @param LanguageMetaInterface $LanguageMetaRepository
     * @author Sang Nguyen
     */
    public function __construct(LanguageInterface $languageRepository, LanguageMetaInterface $LanguageMetaRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->LanguageMetaRepository = $LanguageMetaRepository;
    }

    /**
     * Get list language page
     * @author Sang Nguyen
     */
    public function getList()
    {
        page_title()->setTitle(trans('language::language.name'));

        Assets::addJavascriptDirectly('vendor/core/plugins/language/js/language.js');
        $languages = Language::getListLanguages();
        $flags = Language::getListLanguageFlags();
        $active_languages = $this->languageRepository->all();
        return view('language::index', compact('languages', 'flags', 'active_languages'));
    }

    /**
     * @param LanguageRequest $request
     * @return array
     * @author Sang Nguyen
     */
    public function postStore(LanguageRequest $request)
    {
        try {
            $language = $this->languageRepository->getFirstBy([
                'lang_code' => $request->input('lang_code'),
            ]);
            if ($language) {
                return [
                    'error' => true,
                    'message' => __('This language is added already!'),
                ];
            }
            $language = $this->languageRepository->createOrUpdate($request->except('lang_id'));

            do_action(BASE_ACTION_AFTER_CREATE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

            return [
                'error' => false,
                'message' => trans('bases::notices.create_success_message'),
                'data' => view('language::partials.language-item', ['item' => $language])->render(),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postEdit(Request $request)
    {
        try {
            $language = $this->languageRepository->getFirstBy(['lang_id' => $request->input('lang_id')]);
            if (empty($language)) {
                abort(404);
            }
            $language->fill($request->input());
            $language = $this->languageRepository->createOrUpdate($language);

            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

            return [
                'error' => false,
                'message' => trans('bases::notices.update_success_message'),
                'data' => view('language::partials.language-item', ['item' => $language])->render(),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }

    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeItemLanguage(Request $request)
    {
        $content_id = $request->input('lang_meta_content_id') ? $request->input('lang_meta_content_id') : $request->input('lang_meta_created_from');
        $current_language = $this->LanguageMetaRepository->getFirstBy([
            'lang_meta_content_id' => $content_id,
            'lang_meta_reference' => $request->input('lang_meta_reference'),
        ]);
        $others = $this->LanguageMetaRepository->getModel();
        if ($current_language) {
            $others = $others->where('lang_meta_code', '!=', $request->input('lang_meta_current_language'))
                ->where('lang_meta_origin', $current_language->origin);
        }
        $others = $others->select('lang_meta_content_id', 'lang_meta_code')
            ->get();
        $data = [];
        foreach ($others as $other) {
            $language = $this->languageRepository->getFirstBy(['lang_code' => $other->lang_code], ['lang_flag', 'lang_name', 'lang_code']);
            if (!empty($language) && !empty($current_language) && $language->lang_code != $current_language->lang_meta_code) {
                $data[$language->lang_code]['lang_flag'] = $language->lang_flag;
                $data[$language->lang_code]['lang_name'] = $language->lang_name;
                $data[$language->lang_code]['lang_meta_content_id'] = $other->lang_meta_content_id;
            }
        }

        $languages = $this->languageRepository->all();
        foreach ($languages as $language) {
            if (!array_key_exists($language->lang_code, $data) && $language->lang_code != $request->input('lang_meta_current_language')) {
                $data[$language->lang_code]['lang_flag'] = $language->lang_flag;
                $data[$language->lang_code]['lang_name'] = $language->lang_name;
                $data[$language->lang_code]['lang_meta_content_id'] = null;
            }
        }

        return [
            'error' => false,
            'data' => $data,
        ];
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id)
    {
        try {
            $language = $this->languageRepository->getFirstBy(['lang_id' => $id]);
            $this->languageRepository->delete($language);
            $delete_default = false;
            if ($language->lang_is_default) {
                $default = $this->languageRepository->getFirstBy([
                    'lang_is_default' => 0,
                ]);
                $default->lang_is_default = 1;
                $this->languageRepository->createOrUpdate($default);
                $delete_default = $default->lang_id;
            }

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

            return [
                'error' => false,
                'message' => trans('bases::notices.deleted'),
                'data' => $delete_default,
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => trans('bases::notices.cannot_delete'),
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getSetDefault(Request $request)
    {
        $id = $request->input('lang_id');

        $this->languageRepository->update(['lang_is_default' => 1], ['lang_is_default' => 0]);
        $language = $this->languageRepository->getFirstBy(['lang_id' => $id]);
        $language->lang_is_default = 1;
        $this->languageRepository->createOrUpdate($language);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, LANGUAGE_MODULE_SCREEN_NAME, $request, $language);

        return [
            'error' => false,
            'message' => trans('bases::notices.update_success_message'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getLanguage(Request $request)
    {
        $language = $this->languageRepository->getFirstBy(['lang_id' => $request->input('lang_id')]);
        return [
            'error' => false,
            'data' => $language,
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEditSettings(Request $request)
    {
        Setting::set('language_hide_default', $request->input('language_hide_default', false));
        Setting::set('language_display', $request->input('language_display'));
        Setting::set('language_switcher_display', $request->input('language_switcher_display'));
        Setting::set('language_hide_languages', json_encode($request->input('language_hide_languages', [])));
        Setting::save();
        return redirect()->back()->with('success_msg', trans('bases::notices.update_success_message'));
    }

    /**
     * @param $code
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getChangeDataLanguage($code)
    {
        UserMeta::setMeta('languages_current_data_language', $code);
        return redirect()->back();
    }
}
