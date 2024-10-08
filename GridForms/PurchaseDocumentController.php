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

use FacturaScripts\Core\Model\Base\PurchaseDocument;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\Proveedor;

/**
 * Description of PurchaseDocumentController
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 */
abstract class PurchaseDocumentController extends BusinessDocumentController
{
    public function getCustomFields(): array
    {
        return [
            [
                'icon' => 'fas fa-hashtag',
                'label' => 'numsupplier',
                'name' => 'numproveedor'
            ]
        ];
    }

    public function getModel(): PurchaseDocument
    {
        $code = $this->request->get('code');
        $viewName = 'Edit' . $this->getModelClassName();
        $this->views[$viewName]->model->loadFromCode($code);
        return $this->views[$viewName]->model;
    }

    public function getNewSubjectUrl(): string
    {
        $proveedor = new Proveedor();
        return $proveedor->url('new') . '?return=' . $this->url();
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['showonmenu'] = false;
        return $data;
    }

    protected function getLineXMLView(): string
    {
        return 'PurchaseDocumentLine';
    }

    /**
     * @param BusinessDocumentView $view
     * @param array $formData
     *
     * @return string
     */
    protected function setSubject(&$view, $formData): string
    {
        if (empty($formData['codproveedor'])) {
            return 'ERROR: ' . Tools::lang()->trans('supplier-not-found');
        }

        if ($view->model->codproveedor === $formData['codproveedor']) {
            return 'OK';
        }

        $proveedor = new Proveedor();
        if ($proveedor->loadFromCode($formData['codproveedor'])) {
            $view->model->setSubject($proveedor);
            return 'OK';
        }

        return 'ERROR: ' . Tools::lang()->trans('supplier-not-found');
    }
}
