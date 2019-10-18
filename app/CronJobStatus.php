<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CronJobStatus extends Model
{
    /**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'cron_job_status';
}
