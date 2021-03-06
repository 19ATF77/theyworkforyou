<?php

namespace MySociety\TheyWorkForYou;

/**
 * Member Multiple Exception
 *
 * For when there are multiple results identifying a member.
 */

class MemberMultipleException extends MemberException {
    public $ids;

    public function __construct($ids) {
        $this->ids = $ids;
        parent::__construct();
    }
}
