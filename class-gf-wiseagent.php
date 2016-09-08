<?php
GFForms::include_feed_addon_framework();

class GFWiseAgent extends GFFeedAddOn {

    protected $_version = GF_WiseAgent_VERSION;
    protected $_min_gravityforms_version = '1.9.16';
    protected $_slug = 'wiseagent';
    protected $_path = 'gravity-forms-wiseagent/class-gf-wiseagent.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms: Wise Agent';
    protected $_short_title = 'Wise Agent';

    private static $_instance = null;

    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFWiseAgent();
        }

        return self::$_instance;
    }

    public function init() {
        parent::init();
    }

    public function process_feed( $feed, $entry, $form ) {
        $api_url = 'https://sync.thewiseagent.com/http/webconnect.asp';
        $apikey = $this->get_plugin_setting( 'wiseagent_apikey');
        $debug = $this->get_plugin_setting( 'wiseagent_debug');

        $contact = array('requestType'=>'webcontact','apikey'=>$apikey);
        $contact_info = $this->get_field_map_fields($feed,'wiseagent_contact');
        foreach($contact_info as $field_name => $field_id){
            $field_value = $this->get_field_value($form,$entry,$field_id);
            if($field_value){$contact[$field_name] = $field_value;}
        }
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => http_build_query($contact),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if($debug){echo '<pre>';var_dump($result).'</pre>';}
    }


    public function plugin_settings_fields() {
        return array(
            array(
                'title'  => esc_html__( 'wiseagent Settings', 'wiseagent' ),
                'fields' => array(
                    array(
                        'label'             => esc_html__( 'API Key', 'wiseagent' ),
                        'type'              => 'text',
                        'name'              => 'wiseagent_apikey',
                        'tooltip'           => esc_html__( 'API Key for your Wise Agent account', 'wiseagent' ),
                        'class'             => 'medium',
                        //'feedback_callback' => array( $this, 'is_valid_setting' ),
                    ),
                    array(
                        'label'             => esc_html__( 'Debug', 'wiseagent' ),
                        'type'              => 'checkbox',
                        'name'              => 'wiseagent_debug',
                        'tooltip'           => esc_html__( 'Displays the response from the Wise Agent API', 'wiseagent' ),
                        'choices'           => array(
                            array(
                                'label'     => esc_html( 'Enabled', 'wiseagent' ),
                                'name'      => 'wiseagent_debug'
                            ),
                        ),
                        //'feedback_callback' => array( $this, 'is_valid_setting' ),
                    ),
                )
            )
        );
    }

    public function feed_settings_fields() {
        return array(
            array(
                'title'  => esc_html__( 'wise Agent Settings', 'wiseagent' ),
                'fields' => array(
                    array(
                        'label'   => esc_html__( 'Feed name', 'wiseagent' ),
                        'type'    => 'text',
                        'name'    => 'feedName',
                        'tooltip' => esc_html__( 'Name of the feed. You can use multiple feeds to create different contacts', 'wiseagent' ),
                        'class'   => 'small',
                    ),
                    array(
                        'name'      => 'wiseagent_contact',
                        'label'     => esc_html__( 'wiseagent Contact Fields', 'wiseagent' ),
                        'type'      => 'field_map',
                        'field_map' => array(
                            array(
                                'name'          => 'CFirst',
                                'label'         => esc_html__( 'First Name', 'wiseagent' ),
                                'required'      => true,
                                'field_type'    => array( 'name', 'text', 'hidden' ),
                                'default_value' => $this->get_first_field_by_type( 'name', 3 ),
                            ),
                            array(
                                'name'          => 'CLast',
                                'label'         => esc_html__( 'Last Name', 'wiseagent' ),
                                'required'      => true,
                                'field_type'    => array( 'name', 'text', 'hidden' ),
                                'default_value' => $this->get_first_field_by_type( 'name', 6 ),
                            ),
                            array(
                                'name'          => 'CEmail',
                                'label'         => esc_html__( 'Email Address', 'wiseagent' ),
                                'required'      => true,
                                'field_type'    => array( 'email', 'hidden' ),
                                'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Phone',
                                'label'         => esc_html__( 'Phone Number', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'phone', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Cell',
                                'label'         => esc_html__( 'Cell Number', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'phone', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Work',
                                'label'         => esc_html__( 'Work Number', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'phone', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Fax',
                                'label'         => esc_html__( 'Fax Number', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'phone', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Message',
                                'label'         => esc_html__( 'Message', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'textarea', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Company',
                                'label'         => esc_html__( 'Company Name', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'address',
                                'label'         => esc_html__( 'Address', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'address', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'SuiteNo',
                                'label'         => esc_html__( 'Suite Number', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'address', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'city',
                                'label'         => esc_html__( 'City', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'address', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'state',
                                'label'         => esc_html__( 'State', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'select', 'address', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'zip',
                                'label'         => esc_html__( 'Zip Code', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'address', 'text', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                            array(
                                'name'          => 'Source',
                                'label'         => esc_html__( 'Source', 'wiseagent' ),
                                'required'      => false,
                                'field_type'    => array( 'text', 'select', 'hidden' ),
                                //'default_value' => $this->get_first_field_by_type( 'email' ),
                            ),
                        ),
                    ),
                    array(
                        'name'           => 'condition',
                        'label'          => esc_html__( 'Condition', 'wiseagent' ),
                        'type'           => 'feed_condition',
                        'checkbox_label' => esc_html__( 'Enable Condition', 'wiseagent' ),
                        'instructions'   => esc_html__( 'Process this simple feed if', 'wiseagent' ),
                    ),
                ),
            ),
        );
    }

    public function feed_list_columns() {
        return array(
            'feedName'  => esc_html__( 'Name', 'wiseagent' ),
        );
    }

    public function can_create_feed() {return true;}
}