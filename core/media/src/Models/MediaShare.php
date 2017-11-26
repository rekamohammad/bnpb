<?php

namespace Botble\Media\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaShare extends Eloquent
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     * @author Sang Nguyen
     */
    protected $table = 'media_shares';

    /**
     * The date fields for the model.clear
     *
     * @var array
     * @author Sang Nguyen
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['share_type', 'share_id', 'shared_by', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Sang Nguyen
     */
    public function user()
    {
        /**
         * @var Model $this
         */
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Sang Nguyen
     */
    public function folder()
    {
        /**
         * @var Model $this
         */
        return $this->belongsTo(MediaFolder::class, 'share_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Sang Nguyen
     */
    public function file()
    {
        /**
         * @var Model $this
         */
        return $this->belongsTo(MediaFile::class, 'share_id');
    }
}
