<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property int $orders_id
 * @property int $users_id
 * @property int $doctors_id
 * @property string $status
 * @property string|null $closed_at
 * @property string|null $closed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $doctor
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereDoctorsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereOrdersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatRooms whereUsersId($value)
 */
	class ChatRooms extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone_number
 * @property string $email
 * @property string $open_time
 * @property string $close_time
 * @property string|null $website
 * @property string|null $description
 * @property string|null $image
 * @property string|null $spesialis
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereCloseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereOpenTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereSpesialis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clinic whereWebsite($value)
 */
	class Clinic extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $chat_room_id
 * @property int $patient_id
 * @property int $doctor_id
 * @property string $service
 * @property int $price
 * @property string|null $payment_url
 * @property string $status
 * @property int $duration
 * @property int $clinic_id
 * @property string $schedule
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status_service
 * @property-read \App\Models\Clinic $clinic
 * @property-read \App\Models\User $doctor
 * @property-read \App\Models\User $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereChatRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereClinicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatusService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialist whereUpdatedAt($value)
 */
	class Specialist extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property string|null $google_id
 * @property string|null $ktp_number
 * @property string|null $phone_number
 * @property string|null $address
 * @property string|null $birth_date
 * @property string|null $gender
 * @property string|null $certification
 * @property int|null $telemedicine_fee
 * @property int|null $chat_fee
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int|null $clinic_id
 * @property string|null $image
 * @property int|null $specialist_id
 * @property string|null $one_signal_token
 * @property-read \App\Models\Clinic|null $clinic
 * @property-read mixed $image_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Specialist|null $specialist
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCertification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereChatFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereClinicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereKtpNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOneSignalToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSpecialistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTelemedicineFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

