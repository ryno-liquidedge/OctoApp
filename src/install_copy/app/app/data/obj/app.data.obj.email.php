<?php

namespace app\data\obj;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * @property int $id
 * @property string $name
 *
 * @property int $ema_id
 * @property string $ema_subject
 * @property string $ema_body
 * @property int $ema_status
 * @property int $ema_type
 * @property int $ema_direction
 * @property bool $ema_is_deleted
 * @property int $ema_ref_person
 * @property \db_person $person
 * @property int $ema_priority
 * @property int $ema_importance
 * @property string $ema_error_message
 * @property string $ema_read_receipt_email
 * @property int $ema_retry_count
 * @property string $ema_date_added
 * @property string $ema_date_sent
 * @property string $ema_date_schedule
 * @property string $ema_connection
 * @property bool $ema_append_newlines
 * @property string $ema_message_id
 * @property int $ema_ref_email
 * @property \app\data\obj\email $email
 * @property int $ema_ref_email_start
 * @property \app\data\obj\email $email_start
 * @property int $ema_ref_email_anchor
 * @property \app\data\obj\email $email_anchor
 * @property int $ema_ref_person_comment
 * @property \db_person_comment $person_comment
 * @property int $ema_ref_process_queue
 * @property \db_process_queue $process_queue
 * @property int $ema_ref_communication_person
 * @property \db_communication_person $communication_person
 * @property int $ema_ref_communication
 * @property \db_communication $communication
 * @property bool $ema_is_read
 * @property bool $ema_is_test
 * @property int $ema_size
 * @property int $ema_ref_person_mailbox
 * @property \db_person $person_mailbox
 * @property string $ema_external_mailbox_id
 * @property string $ema_tracking_id
 * @property string $ema_date_opened
 */
class email extends \com\core\obj\email {
	//--------------------------------------------------------------------------------
}