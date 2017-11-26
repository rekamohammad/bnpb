<?php

namespace Botble\Note\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Note\Repositories\Interfaces\NoteInterface;
use Illuminate\Http\Request;

class HookServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveNote'], 50, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveNote'], 50, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteNote'], 50, 2);
        add_filter(BASE_FILTER_REGISTER_CONTENT_TABS, [$this, 'addNoteTab'], 50, 2);
        add_filter(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, [$this, 'addNoteContent'], 50, 3);
    }

    /**
     * @param $screen
     * @param $request
     * @param $object
     * @author Sang Nguyen
     */
    public function saveNote($screen, Request $request, $object)
    {
        if (in_array($screen, $this->screenUsingNote()) && $request->input('note')) {
            $note = app(NoteInterface::class)->getModel();
            $note->note = $request->input('note');
            $note->user_id = acl_get_current_user_id();
            $note->created_by = acl_get_current_user_id();
            $note->reference_type = $screen;
            $note->reference_id = $object->id;
            app(NoteInterface::class)->createOrUpdate($note);
        }
    }

    /**
     * @param $content
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteNote($screen, $content)
    {
        /**
         * @var \Eloquent $note
         */
        $note = app(NoteInterface::class)->getFirstBy([
            'reference_id' => $content->id,
            'reference_type' => $screen,
        ]);
        if (!empty($note)) {
            $note->delete();
        }
        return true;
    }

    /**
     * @author Sang Nguyen
     * @since 2.0
     */
    public function screenUsingNote()
    {
        $screen = [PAGE_MODULE_SCREEN_NAME];
        if (defined('POST_MODULE_SCREEN_NAME')) {
            $screen[] = POST_MODULE_SCREEN_NAME;
        }

        return apply_filters(NOTE_FILTER_MODEL_USING_NOTE, $screen);
    }

    /**
     * @param $tabs
     * @param $screen
     * @return string
     * @author Sang Nguyen
     * @since 2.0
     */
    public function addNoteTab($tabs, $screen)
    {
        if (in_array($screen, $this->screenUsingNote())) {
            return $tabs . view('note::tab')->render();
        }
        return $tabs;
    }

    /**
     * @param $tabs
     * @param $screen
     * @param $data
     * @return string
     * @author Sang Nguyen
     * @since 2.0
     */
    public function addNoteContent($tabs, $screen, $data = null)
    {
        if (in_array($screen, $this->screenUsingNote())) {
            $notes = [];
            if (!empty($data)) {
                $notes = app(NoteInterface::class)->allBy([
                    'reference_id' => $data->id,
                    'reference_type' => $screen,
                ]);
            }
            return $tabs . view('note::content', compact('notes'))->render();
        }
        return $tabs;
    }
}
