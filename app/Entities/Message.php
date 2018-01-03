<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 07.12.2017
 * Time: 15:17
 */

namespace App\Entities;


/**
 * App\Entities\Message
 *
 * @property int $id
 * @property int $user_id
 * @property int $external_id
 * @property string $text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Message whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Message whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends AbstractEntity
{

    public $fillable = ['user_id', 'external_id', 'text'];
}