<?php
/**
 * This file is part of OldForms plugin for FacturaScripts
 * Copyright (C) 2017-2024 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Plugins\OldForms\GridForms;

use FacturaScripts\Core\Model\Base\SalesDocument;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\Cliente;

/**
 * Description of SalesDocumentController
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 */
abstract class SalesDocumentController extends BusinessDocumentController
{
    public function getCustomFields(): array
    {
        return [
            [
                'icon' => 'fas fa-hashtag',
                'label' => 'number2',
                'name' => 'numero2'
            ]
        ];
    }

    public function getNewSubjectUrl(): string
    {
        $cliente = new Cliente();
        return $cliente->url('new') . '?return=' . $this->url();
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['showonmenu'] = false;
        return $data;
    }

    protected function getLineXMLView(): string
    {
        return 'SalesDocumentLine';
    }

    public function getModel(): SalesDocument
    {
        $code = $this->request->get('code');
        $viewName = 'Edit' . $this->getModelClassName();
        $this->views[$viewName]->model->loadFromCode($code);
        return $this->views[$viewName]->model;
    }

    /**
     * Loads custom contact data for additional address details.
     *
     * @param BusinessDocumentView $view
     */
    protected function loadCustomContactsWidget($view)
    {
        $cliente = new Cliente();
        if (!$cliente->loadFromCode($view->model->codcliente)) {
            return;
        }

        $addresses = [];
        foreach ($cliente->getAddresses() as $contacto) {
            $addresses[] = ['value' => $contacto->idcontacto, 'title' => $contacto->descripcion];
        }

        // billing address
        $columnBilling = $view->columnForName('billingaddr');
        if ($columnBilling) {
            $columnBilling->widget->setValuesFromArray($addresses, false);
        }

        // shipping address
        $columnShipping = $view->columnForName('shippingaddr');
        if ($columnShipping) {
            $columnShipping->widget->setValuesFromArray($addresses, false, true);
        }
    }

    /**
     * @param string $viewName
     * @param BusinessDocumentView $view
     */
    protected function loadData($viewName, $view)
    {
        parent::loadData($viewName, $view);
        if ($viewName == 'Edit' . $this->getModelClassName()) {
            $this->loadCustomContactsWidget($view);
        }
    }

    /**
     * @param BusinessDocumentView $view
     * @param array $formData
     *
     * @return string
     */
    protected function setSubject(&$view, $formData): string
    {
        if (empty($formData['codcliente'])) {
            return 'ERROR: ' . Tools::lang()->trans('customer-not-found');
        }

        if ($view->model->codcliente === $formData['codcliente']) {
            return 'OK';
        }

        $cliente = new Cliente();
        if ($cliente->loadFromCode($formData['codcliente'])) {
            $view->model->setSubject($cliente);
            return 'OK';
        }

        return 'ERROR: ' . Tools::lang()->trans('customer-not-found');
    }
}
