<?php

namespace MySociety\TheyWorkForYou\SectionView;

class PbcView extends SectionView {
    protected $major = 6;
    protected $class = 'StandingCommittee';
    protected $index_template = 'section/pbc_index';

    private $bill;
    private $session;

    public function __construct() {
        $this->session = get_http_var('session');
        $this->bill = str_replace('_', ' ', get_http_var('bill'));
        $this->list = new \StandingCommittee($this->session, $this->bill);
        parent::__construct();
    }

    # Public Bill Committees have a somewhat different structure to the rest
    public function display() {
        return $this->public_bill_committees();
    }

    protected function public_bill_committees() {
        global $this_page, $DATA, $PAGE;

        $id = get_http_var('id');

        $bill_id = null;
        if ($this->session && $this->bill) {
            $q = $this->list->db->query('select id,standingprefix from bills where title = :bill
                and session = :session', array(
                ':bill' => $this->bill,
                ':session' => $this->session
            ));
            if ($q->rows()) {
                $bill_id = $q->field(0, 'id');
                $standingprefix = $q->field(0, 'standingprefix');
            }
        }

        if ($bill_id && $id) {
            return $this->display_section_or_speech(array(
                'gid' => $standingprefix . $id,
            ));
        } elseif ($bill_id) {
            # Display the page for a particular bill
            $this_page = 'pbc_bill';
            $args = array (
                'id' => $bill_id,
                'title' => $this->bill,
                'session' => $this->session,
            );
            $data = array();
            $data['content'] = $this->list->display('bill', $args, 'none');
            $data['session'] = $this->session;
            $data['template'] = 'section/pbc_bill';
            return $this->addCommonData($data);
        } elseif ($this->session && $this->bill) {
            # Illegal bill title, redirect to session page
            $URL = new \URL('pbc_session');
            header('Location: ' . $URL->generate() . urlencode($this->session));
            exit;
        } elseif ($this->session) {
            # Display the bills for a particular session
            $this_page = 'pbc_session';
            $DATA->set_page_metadata($this_page, 'title', "Session $this->session");
            $args = array (
                'session' => $this->session,
            );
            $data = array();
            $data['rows'] = $this->list->display('session', $args, 'none');
            $data['template'] = 'section/pbc_session';
            $data['session'] = $this->session;
            return $this->addCommonData($data);
        } else {
            return $this->display_front();
        }
    }

    protected function getViewUrls() {
        $urls = array();
        $day = new \URL('pbc_front');
        $urls['pbcday'] = $day;
        return $urls;
    }

    protected function getSearchSections() {
        return array(
            array( 'section' => 'pbc' )
        );
    }

    protected function front_content() {
        return $this->list->display( 'recent_pbc_debates', array( 'num' => 50 ), 'none' );
    }

    protected function display_front() {
        global $DATA, $this_page;
        $this_page = 'pbc_front';

        $data = array();
        $data['template'] = $this->index_template;

        $content = array();
        $content['data'] = $this->front_content();

        $content['rssurl'] = $DATA->page_metadata($this_page, 'rss');

        $data['content'] = $content;
        return $this->addCommonData($data);
    }
}
