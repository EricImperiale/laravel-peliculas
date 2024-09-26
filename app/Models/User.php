<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;

// Para que una clase de Eloquent sirva para el módulo de autenticación, hay algunas cosas que tenemos que
// tener en cuenta:
// 1. Necesitamos heredar no de la clase "Model" de Eloquent, sino de la clase
//  [Illuminate\Foundation\Auth\User] de Laravel.
//  Esta clase hereda a su vez de Eloquent, pero le suma varias cosas más.
// 2. Necesitamos agregar los traits que queremos aprovechar, como Notifiable.
// 3. Agregar los valores "sensibles" a la propiedad $hidden de Eloquent. Esta propiedad le dice a Eloquent
//  que esas propiedades deben ser ignoradas cuando se "serialice" un modelo de la clase a texto (por
//  ejemplo a JSON).
/**
 * App\Models\User
 *
 * @property int $user_id
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserId($value)
 * @mixin \Eloquent
 */
class User extends BaseUser
{
//    use HasFactory;
    use Notifiable;

    protected $primaryKey = "user_id";

    protected $hidden = ['password', 'remember_token'];
}
