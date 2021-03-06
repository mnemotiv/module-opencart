<?php

/**
 * Class ControllerPaymentMTPayment
 */
class ControllerPaymentMTPayment extends Controller
{

    /**
     * @var array
     */
    private $error = array();

    /**
     *
     */
    public function install()
    {
        $this->load->model('payment/mtpayment');
        $this->model_payment_mtpayment->install();
    }

    /**
     *
     */
    public function uninstall()
    {
        $this->load->model('payment/mtpayment');
        $this->model_payment_mtpayment->uninstall();
    }

    /**
     *
     */
    public function index()
    {
        $this->load->language('payment/mtpayment');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('payment/mtpayment');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('mtpayment', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_edit'] = $this->language->get('text_edit');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');

        $this->data['entry_username'] = $this->language->get('entry_username');
        $this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $this->data['entry_callback_url'] = $this->language->get('entry_callback_url');
        $this->data['entry_standard_redirect'] = $this->language->get('entry_standard_redirect');
        $this->data['entry_total'] = $this->language->get('entry_total');
        $this->data['entry_order_pending_status'] = $this->language->get('entry_order_pending_status');
        $this->data['entry_order_success_status'] = $this->language->get('entry_order_success_status');
        $this->data['entry_order_error_status'] = $this->language->get('entry_order_error_status');
        $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['help_username'] = $this->language->get('help_username');
        $this->data['help_secret_key'] = $this->language->get('help_secret_key');
        $this->data['help_callback_url'] = $this->language->get('help_callback_url');
        $this->data['help_total'] = $this->language->get('help_total');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->load->model('localisation/language');

        if (isset($this->error['username'])) {
            $this->data['error_username'] = $this->error['username'];
        } else {
            $this->data['error_username'] = '';
        }

        if (isset($this->error['secret_key'])) {
            $this->data['error_secret_key'] = $this->error['secret_key'];
        } else {
            $this->data['error_secret_key'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/mtpayment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/mtpayment', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('localisation/language');

        if (isset($this->request->post['mtpayment_username'])) {
            $this->data['mtpayment_username'] = $this->request->post['mtpayment_username'];
        } else {
            $this->data['mtpayment_username'] = $this->config->get('mtpayment_username');
        }

        if (isset($this->request->post['mtpayment_secret_key'])) {
            $this->data['mtpayment_secret_key'] = $this->request->post['mtpayment_secret_key'];
        } else {
            $this->data['mtpayment_secret_key'] = $this->config->get('mtpayment_secret_key');
        }

        if (isset($this->request->post['mtpayment_callback_url'])) {
            $this->data['mtpayment_callback_url'] = $this->request->post['mtpayment_callback_url'];
        } else {
            $this->data['mtpayment_callback_url'] = $this->config->get('mtpayment_callback_url');
        }

        if (isset($this->request->post['mtpayment_standard_redirect'])) {
            $this->data['mtpayment_standard_redirect'] = $this->request->post['mtpayment_standard_redirect'];
        } else {
            $this->data['mtpayment_standard_redirect'] = $this->config->get('mtpayment_standard_redirect');
        }

        if (isset($this->request->post['mtpayment_total'])) {
            $this->data['mtpayment_total'] = $this->request->post['mtpayment_total'];
        } else {
            $this->data['mtpayment_total'] = $this->config->get('mtpayment_total');
        }

        if (isset($this->request->post['mtpayment_order_pending_status_id'])) {
            $this->data['mtpayment_order_pending_status_id'] = $this->request->post['mtpayment_order_pending_status_id'];
        } else {
            $this->data['mtpayment_order_pending_status_id'] = $this->config->get('mtpayment_order_pending_status_id');
        }

        if (isset($this->request->post['mtpayment_order_success_status_id'])) {
            $this->data['mtpayment_order_success_status_id'] = $this->request->post['mtpayment_order_success_status_id'];
        } else {
            $this->data['mtpayment_order_success_status_id'] = $this->config->get('mtpayment_order_success_status_id');
        }

        if (isset($this->request->post['mtpayment_order_error_status_id'])) {
            $this->data['mtpayment_order_error_status_id'] = $this->request->post['mtpayment_order_error_status_id'];
        } else {
            $this->data['mtpayment_order_error_status_id'] = $this->config->get('mtpayment_order_error_status_id');
        }

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['mtpayment_geo_zone_id'])) {
            $this->data['mtpayment_geo_zone_id'] = $this->request->post['mtpayment_geo_zone_id'];
        } else {
            $this->data['mtpayment_geo_zone_id'] = $this->config->get('mtpayment_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['mtpayment_status'])) {
            $this->data['mtpayment_status'] = $this->request->post['mtpayment_status'];
        } else {
            $this->data['mtpayment_status'] = $this->config->get('mtpayment_status');
        }

        if (isset($this->request->post['mtpayment_sort_order'])) {
            $this->data['mtpayment_sort_order'] = $this->request->post['mtpayment_sort_order'];
        } else {
            $this->data['mtpayment_sort_order'] = $this->config->get('mtpayment_sort_order');
        }

        $this->template = 'payment/mtpayment.tpl';

        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    /**
     * @return bool
     */
    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/mtpayment')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['mtpayment_username'])) {
            $this->error['username'] = $this->language->get('error_username');
        }

        if (empty($this->request->post['mtpayment_secret_key'])) {
            $this->error['secret_key'] = $this->language->get('error_secret_key');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
