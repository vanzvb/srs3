<?php

namespace App\Models\SRS3_Model;

use App\Models\CrmInvoice;
use App\Models\CrmMain;
use App\Models\CrmVehicle;
use App\Models\CRMXI3_Model\CRMXICategory;
use App\Models\CRMXI3_Model\CRMXIHoa;
use App\Models\CRMXI3_Model\CRMXIHoaType;
use App\Models\CRMXI3_Model\CRMXIMain;
use App\Models\CRMXI3_Model\CRMXISubcat;
use App\Models\CRMXI3_Model\CRMXIVehicleOwnershipStatus;
use App\Models\CRMXI3_Model\CRXMIVehicle;
use App\Models\SPCCategory;
use App\Models\SPCSubCat;
use App\Models\SRS3_Model\SrsRequestStatus;
use App\Models\SrsAppointment;
use App\Models\SrsApptResend;
use App\Models\SrsApptReset;
use App\Models\SrsHoa;
use App\Models\SrsNrHoa;
use App\Models\SrsRequirementFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SrsRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'srs3_requests';
    protected $primaryKey = 'request_id';
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'hoa_notif_resend',
        'hoa_renotif_at',
        'appt_resend',
        'appt_resend_at'
    ];

    protected $casts = [
        'contact_no' => 'encrypted',
        'email' => 'encrypted',
        'signature' => 'encrypted',
        'created_at' => 'datetime:M d, Y h:i A'
    ];

    protected $hidden = [
        'house_no',
        'street',
        'hoa_id',
        'contact_no',
        'email',
        'signature',
    ];

    public function category()
    {
        return $this->belongsTo(CRMXICategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(CRMXISubcat::class);
    }

    public function appointment()
    {
        return $this->hasOne(SrsAppointment::class, 'srs_request_id');
    }

    public function vehicles()
    {
        // return $this->hasMany(SrsVehicle::class, 'srs_request_id');
        return $this->hasMany(CRXMIVehicle::class, 'srs_request_id');
    }

    public function hoa()
    {
        // return $this->belongsTo(SrsHoa::class);
        return $this->belongsTo(CRMXIHoa::class);
    }

    public function nrHoa()
    {
        return $this->belongsTo(SrsNrHoa::class);
    }

    public function files()
    {
        return $this->hasMany(SrsRequirementFile::class, 'srs_request_id');
    }

    public function statuses()
    {
        return $this->belongsToMany(SrsRequestStatus::class, 'srs_request_status_logs', 'request_id', 'status_id')
            ->withPivot('action_by')
            ->withTimestamps();
    }

    public function stats()
    {
        return $this->belongsToMany(SrsRequestStatus::class, 'srs_request_status_logs', 'request_id', 'status_id');
    }

    public function customer()
    {
        // return $this->belongsTo(CrmMain::class, 'customer_id', 'customer_id');
        return $this->belongsTo(CRMXIMain::class, 'account_id', 'account_id');
    }

    public function invoice()
    {
        return $this->belongsTo(CrmInvoice::class, 'invoice_no', 'invoice_no');
    }

    public function crmVehicles()
    {
        return $this->belongsToMany(CRXMIVehicle::class, 'request3_vehicle', 'request_id', 'vehicle_id')->withTimestamps();
    }

    public function appointmentResets()
    {
        return $this->hasMany(SrsApptReset::class, 'request_id');
    }

    public function appointmentResends()
    {
        return $this->hasMany(SrsApptResend::class, 'request_id');
    }

    public function latestApptReset()
    {
        return $this->hasOne(SrsApptReset::class, 'request_id')->orderBy('created_at', 'desc');
    }

    public function latestApptResend()
    {
        return $this->hasOne(SrsApptResend::class, 'request_id')->orderBy('created_at', 'desc');
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // ================== SRS Request 3.0 ==================

    /**
     * Get the hoa record associated with the SrsRequest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hoa3()
    {
        

        if($this->srs3_hoa_id == null) {
            return $this->belongsTo(CRMXIHoa::class, 'hoa_id');
        } else {
            // This is only for migrated requests (so if this is null its not from migration)
            return $this->belongsTo(CRMXIHoa::class, 'srs3_hoa_id');
        }
    }

    /**
     * Get the hoa type record associated with the SrsRequest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category3()
    {
        return $this->belongsTo(CRMXICategory::class, 'category_id');
    }

    /**
     * Get the sub category record associated with the SrsRequest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCategory3()
    {
        if($this->srs3_sub_category_id == null) {
            return $this->belongsTo(CRMXISubcat::class, 'sub_category_id');
        } else {
            // This is only for migrated requests (so if this is null its not from migration)
            return $this->belongsTo(CRMXISubcat::class, 'srs3_sub_category_id');
        }
        
    }

    /**
     * Get the vehicle record associated with the SrsRequest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicles3()
    {
        // return $this->hasMany(SrsVehicle::class, 'srs_request_id');
        return $this->hasMany(CRXMIVehicle::class, 'srs_request_id', 'request_id');
    }

    /**
     * Get the customer record associated with the SrsRequest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer3()
    {
        return $this->belongsTo(CRMXIMain::class, 'customer_id', 'crm_id');
    }

    // ================== SRS Request 3.0 ==================
}
